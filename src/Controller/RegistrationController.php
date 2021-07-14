<?php

namespace NftPortfolioTracker\Controller;

use Gregwar\CaptchaBundle\Type\CaptchaType;
use NftPortfolioTracker\Entity\Account;
use NftPortfolioTracker\Entity\AccountUser;
use NftPortfolioTracker\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private FormFactory $formFactory;

    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new AccountUser();
        $form = $this->formFactory->createNamedBuilder('user', RegistrationFormType::class, $user);
        $form->add('address', TextType::class, ['attr' => ['minlength' => 42, 'maxlength' => 42]]);
        $form->add('timezone', TextType::class);
        $form->add('captcha', CaptchaType::class);
        $form->add('save', SubmitType::class, ['label' => 'Create Account']);
        $form = $form->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $accountRepository = $this->getDoctrine()->getManager()->getRepository(Account::class);
            $account = $accountRepository->createOrGetAccountByUser($form->getData());

            $user->setAccount($account);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('calendar_index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
