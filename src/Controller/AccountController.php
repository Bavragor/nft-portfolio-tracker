<?php

namespace NftPortfolioTracker\Controller;

use Doctrine\ORM\Query\Expr\Join;
use NftPortfolioTracker\Entity\Account;
use NftPortfolioTracker\Entity\AssetPrice;
use NftPortfolioTracker\Enum\TransactionDirectionEnum;
use NftPortfolioTracker\Event\Account\AccountAddedEvent;
use NftPortfolioTracker\Event\Account\AccountDeletedEvent;
use NftPortfolioTracker\Event\Account\AccountUpdatedEvent;
use NftPortfolioTracker\Repository\AccountAssetRepository;
use NftPortfolioTracker\Repository\AccountBalanceRepository;
use NftPortfolioTracker\Repository\AccountRepository;
use NftPortfolioTracker\Repository\AssetPriceRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Doctrine\ORM\QueryBuilder;

class AccountController extends AbstractController
{
    private EventDispatcherInterface $eventDispatcher;
    private AccountRepository $accountRepository;
    private AccountBalanceRepository $accountBalanceRepository;
    private AssetPriceRepository $assetPriceRepository;
    private AccountAssetRepository $accountAssetRepository;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        AccountRepository $accountRepository,
        AccountBalanceRepository $accountBalanceRepository,
        AssetPriceRepository $assetPriceRepository,
        AccountAssetRepository $accountAssetRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->accountRepository = $accountRepository;
        $this->accountBalanceRepository = $accountBalanceRepository;
        $this->assetPriceRepository = $assetPriceRepository;
        $this->accountAssetRepository = $accountAssetRepository;
    }

    /**
     * @Route("/account", name="account")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $accounts = $this->accountRepository->findAll();

        return $this->render('account/index.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * @Route("/account/{address}", name="account_single")
     */
    public function single(string $address, Request $request): Response
    {
        if ($address === 'create') {
            return $this->create($request);
        }

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$this->isGranted('ROLE_ADMIN') && $this->getUser()->getAccount()->getAddress() !== $address) {
            throw $this->createAccessDeniedException('Trying to access another account');
        }

        $overallBalance = $this->accountBalanceRepository->findOneBy(['account' => $address, 'projectName' => null]);

        $queryBuilder = $this->accountAssetRepository
            ->createQueryBuilder('transactionIn');

        $queryBuilderOutgoingQuery = $this->accountAssetRepository
            ->createQueryBuilder('transactionOut');

        $queryBuilderOutgoingQuery = $queryBuilderOutgoingQuery
            ->resetDQLPart('select')
            ->addSelect($queryBuilderOutgoingQuery->expr()->concat('transactionOut.tokenSymbol', 'transactionOut.tokenId'))
            ->where(
                $queryBuilderOutgoingQuery->expr()->andX(
                    $queryBuilderOutgoingQuery->expr()->eq('transactionOut.account', $queryBuilderOutgoingQuery->expr()->literal($request->get('address'))),
                    $queryBuilderOutgoingQuery->expr()->eq('transactionOut.direction', TransactionDirectionEnum::OUT)
                )
            )
        ;

        $assetsQuery = $queryBuilder
            ->select($queryBuilderOutgoingQuery->expr()->concat('transactionIn.tokenSymbol', 'transactionIn.tokenId'))
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('transactionIn.account', $queryBuilder->expr()->literal($request->get('address'))),
                    $queryBuilder->expr()->notIn(
                        $queryBuilderOutgoingQuery->expr()->concat('transactionIn.tokenSymbol', 'transactionIn.tokenId'),
                        $queryBuilderOutgoingQuery->getDQL()
                    )
                )
            )
            ->addOrderBy('transactionIn.timestamp', 'desc')
            ->addOrderBy('transactionIn.tokenSymbol')
        ;

        $floorPriceQueryBuilder = $this->assetPriceRepository->createQueryBuilder('floorPrice', 'floorPrice.tokenSymbol');
        $floorPriceQueryBuilder
            ->select('SUM(floorPrice.price) as inventoryBalance')
            ->addSelect('floorPrice.tokenSymbol')
            ->where(
                $floorPriceQueryBuilder->expr()->andX(
                    $floorPriceQueryBuilder->expr()->in(
                        $queryBuilderOutgoingQuery->expr()->concat('floorPrice.tokenSymbol', 'floorPrice.tokenId'),
                        $assetsQuery->getDQL()
                    ),
                    $floorPriceQueryBuilder->expr()->orX(
                        $floorPriceQueryBuilder->expr()->isNull('floorPrice.createdByAddress'),
                        $floorPriceQueryBuilder->expr()->eq('floorPrice.createdByAddress', $floorPriceQueryBuilder->expr()->literal($address))
                    )
                )
            )
            ->groupBy('floorPrice.tokenSymbol')
        ;

        $balances = $this->accountBalanceRepository
            ->createQueryBuilder('balance')
            ->select('balance')
            ->where('balance.projectName is not null')
            ->andWhere('balance.account = :account')
            ->setParameter('account', $address)
            ->orderBy('balance.balance', 'asc')
            ->getQuery()->getResult();

        if ($overallBalance !== null) {
            array_unshift($balances, $overallBalance);
        }

        $overallInventoryPrice = 0.0;

        $inventoryPrices = $floorPriceQueryBuilder->getQuery()->getResult();

        foreach ($inventoryPrices as $inventoryPrice) {
            $overallInventoryPrice += $inventoryPrice['inventoryBalance'];
        }

        $inventoryPrices[""] = ['inventoryBalance' => $overallInventoryPrice];

        return $this->render('account/single.html.twig', [
            'balances' => $balances,
            'inventoryPrices' => $inventoryPrices,
        ]);
    }

    /**
     * @Route("/account/create", name="account_create")
     */
    public function create(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $account = new Account();

        $form = $this->createFormBuilder($account)
            ->add('name', TextType::class)
            ->add('address', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Account'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $accountData = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($accountData);
            $entityManager->flush();

            $this->eventDispatcher->dispatch(AccountAddedEvent::create($account->getAddress()));

            return $this->redirectToRoute('account');
        }

        return $this->render('account/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/{address}/delete", name="account_delete")
     */
    public function delete(string $address): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->eventDispatcher->dispatch(AccountDeletedEvent::create($address));

        return $this->redirectToRoute('account');
    }

    /**
     * @Route("/account/{address}/refresh", name="account_refresh")
     */
    public function refresh(string $address): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->eventDispatcher->dispatch(AccountUpdatedEvent::create($address, []));

        return $this->redirectToRoute('account');
    }
}
