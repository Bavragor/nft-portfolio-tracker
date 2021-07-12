<?php

namespace NftPortfolioTracker\Controller;

use NftPortfolioTracker\Entity\Project;
use NftPortfolioTracker\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @Route("/project", name="project")
     */
    public function index(): Response
    {
        return $this->render('projects/index.html.twig', [
            'controller_name' => 'ProjectsController',
            'projects' => $this->projectRepository->findAll(),
        ]);
    }

    /**
     * @Route("/project/create", name="project_create")
     */
    public function create(Request $request): Response
    {
        $project = new Project();
        $project->setEtherscanUrl('https://api.etherscan.io/api');

        $form = $this->createFormBuilder($project)
            ->add('name', TextType::class)
            ->add('tokenName', TextType::class)
            ->add('tokenSymbol', TextType::class)
            ->add('contract', TextType::class)
            ->add('etherscanUrl', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Project'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $projectData = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projectData);
            $entityManager->flush();

            return $this->redirectToRoute('project_create');
        }

        return $this->render('projects/create.html.twig', [
            'controller_name' => 'ProjectController',
            'form' => $form->createView()
        ]);
    }
}
