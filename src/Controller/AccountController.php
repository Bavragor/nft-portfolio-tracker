<?php

namespace NftPortfolioTracker\Controller;

use NftPortfolioTracker\Entity\Account;
use NftPortfolioTracker\Event\Account\AccountAddedEvent;
use NftPortfolioTracker\Event\Account\AccountDeletedEvent;
use NftPortfolioTracker\Event\Account\AccountUpdatedEvent;
use NftPortfolioTracker\Repository\AccountRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    private EventDispatcherInterface $eventDispatcher;
    private AccountRepository $accountRepository;

    public function __construct(EventDispatcherInterface $eventDispatcher, AccountRepository $accountRepository)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->accountRepository = $accountRepository;
    }

    /**
     * @Route("/account", name="account")
     */
    public function index(): Response
    {
        $accounts = $this->accountRepository->findAll();

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'accounts' => $accounts,
        ]);
    }

    /**
     * @Route("/account/create", name="account_create")
     */
    public function create(Request $request): Response
    {
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
            'controller_name' => 'AccountController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/delete/{address}", name="account_delete")
     */
    public function delete(string $address): Response
    {
        $this->eventDispatcher->dispatch(AccountDeletedEvent::create($address));

        return $this->redirectToRoute('account');
    }

    /**
     * @Route("/account/refresh/{address}", name="account_refresh")
     */
    public function refresh(string $address): Response
    {
        $this->eventDispatcher->dispatch(AccountUpdatedEvent::create($address, []));

        return $this->redirectToRoute('account');
    }
}
