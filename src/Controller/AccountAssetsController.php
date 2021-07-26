<?php

namespace NftPortfolioTracker\Controller;

use Doctrine\ORM\Query\Expr\Join;
use Knp\Component\Pager\PaginatorInterface;
use NftPortfolioTracker\Entity\Account;
use NftPortfolioTracker\Entity\AssetPrice;
use NftPortfolioTracker\Enum\TransactionDirectionEnum;
use NftPortfolioTracker\Repository\AccountAssetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Doctrine\ORM\QueryBuilder;

class AccountAssetsController extends AbstractController
{
    private AccountAssetRepository $accountAssetRepository;

    public function __construct(AccountAssetRepository $accountAssetRepository)
    {
        $this->accountAssetRepository = $accountAssetRepository;
    }

    /**
     * @Route("/account/{address}/transactions", name="account_transactions")
     */
    public function transactions(Request $request, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$this->isGranted('ROLE_ADMIN') && $this->getUser()->getAccount()->getAddress() !== $request->get('address')) {
            $this->createAccessDeniedException('Trying to access another account');
        }

        //$transactions = $this->accountAssetRepository->findBy(['account' => $address], ['timestamp' => 'desc']);

        $queryBuilder = $this->accountAssetRepository
            ->createQueryBuilder('transaction');

        $assetsQuery = $queryBuilder
            ->select('transaction')
            ->where(
                $queryBuilder->expr()->eq('transaction.account', $queryBuilder->expr()->literal($request->get('address')))
            )
            ->orderBy('transaction.timestamp', 'desc')
        ;

        $pagination = $paginator->paginate(
            $assetsQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('limit', 100) /*limit per page*/
        );

        return $this->render('account/transactions.html.twig', [
            'transactions' => $pagination
        ]);
    }

    /**
     * @Route("/account/{address}/inventory", name="account_inventory")
     */
    public function inventory(Request $request, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$this->isGranted('ROLE_ADMIN') && $this->getUser()->getAccount()->getAddress() !== $request->get('address')) {
            $this->createAccessDeniedException('Trying to access another account');
        }

        //$transactions = $this->accountAssetRepository->findBy(['account' => $address], ['timestamp' => 'desc']);

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
            ->addSelect('transactionIn')
            ->addSelect('assetPrice.price')
            ->join(AssetPrice::class, 'assetPrice', Join::WITH, 'transactionIn.tokenSymbol = assetPrice.tokenSymbol AND transactionIn.tokenId = assetPrice.tokenId')
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

        $transactionCountQueryBuilder = $this->accountAssetRepository->createQueryBuilder('transactionCount', 'transactionCount.transactionHash');
        $transactionCountQueryBuilder
            ->select('COUNT(transactionCount.id) as count')
            ->addSelect('transactionCount.transactionHash')
            ->where(
                $transactionCountQueryBuilder->expr()->eq('transactionCount.account', $transactionCountQueryBuilder->expr()->literal($request->get('address')))
            )
            ->groupBy('transactionCount.transactionHash')
        ;

        $pagination = $paginator->paginate(
            $assetsQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('limit', 100) /*limit per page*/
        );

        return $this->render('account/inventory.html.twig', [
            'transactions' => $pagination,
            'countByTransactionHash' => $transactionCountQueryBuilder->getQuery()->getArrayResult(),
        ]);
    }
}
