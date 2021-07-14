<?php

namespace NftPortfolioTracker\Controller\Project\Calendar;

use NftPortfolioTracker\Entity\NftEvent;
use NftPortfolioTracker\Repository\NftEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private NftEventRepository $nftEventRepository;

    public function __construct(NftEventRepository $nftEventRepository)
    {
        $this->nftEventRepository = $nftEventRepository;
    }

    /**
     * @Route("/project/calendar", name="calendar_index")
     */
    public function index(): Response
    {
        return $this->render('projects/calendar/index.html.twig', [
            'account' => $this->getUser()
        ]);
    }

    /**
     * @Route("/project/calendar/create", name="nft_event_create")
     */
    public function create(Request $request): Response
    {
        $account = new NftEvent();

        $form = $this->createFormBuilder($account)
            ->add('name', TextType::class)
            ->add('type', ChoiceType::class, ['choices' => ['Listed' => 'Listed', 'Mint' => 'Mint', 'Reveal' => 'Reveal']])
            ->add('url', UrlType::class)
            ->add('eventDateStart', DateTimeType::class)
            ->add('eventDateEnd', DateTimeType::class)
            ->add('save', SubmitType::class, ['label' => 'Create NFT Event'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $nftEvent = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($nftEvent);
            $entityManager->flush();

            return $this->redirectToRoute('calendar_index');
        }

        return $this->render('projects/calendar/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/project/calendar/schedules", name="calendar_schedules")
     */
    public function schedules(Request $request): Response
    {
        $nftEvents = [];

        if ($request->get('start_date') !== null && $request->get('end_date') !== null) {
            $nftEvents = $this->nftEventRepository->findBetweenDates($request->get('start_date'), $request->get('end_date'));
        }

        $nftEventsAsJson = [];

        foreach ($nftEvents as $nftEvent) {
            $nftEventsAsJson[] = $nftEvent->toArray();
        }

        return JsonResponse::fromJsonString(json_encode($nftEventsAsJson, JSON_THROW_ON_ERROR));
    }
}