<?php

namespace NftPortfolioTracker\Controller;

use NftPortfolioTracker\Entity\Account;
use NftPortfolioTracker\Repository\AccountAssetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAssetsController extends AbstractController
{
    private AccountAssetRepository $accountAssetRepository;

    public function __construct(AccountAssetRepository $accountAssetRepository)
    {
        $this->accountAssetRepository = $accountAssetRepository;
    }

    /**
     * @Route("/account/{address}/assets", name="account_assets")
     */
    public function index(string $address): Response
    {
        $transactions = $this->accountAssetRepository->findBy(['account' => $address]);

        return $this->render('account_assets/index.html.twig', [
            'controller_name' => 'AccountAssetsController',
            'transactions' => $transactions
        ]);
    }
}
