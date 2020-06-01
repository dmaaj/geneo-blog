<?php

namespace App\Controller;

use App\DTO\CreatePostDto;
use App\Form\CreatePostFormType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/dashboard", name="post.")
 */
class PostController extends AbstractController
{
    private $post;

    private $postRepository;
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }
    
    /**
     * @Route("/post", name="index")
     */
    public function index()
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, CreatePostDto $post)
    {
        $form = $this->createForm(CreatePostFormType::class, $post);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $this->postRepository->create($post, $this->getUser());
        }
        return $this->render('post/create.html.twig', [
            'createPost' => $form->createView(),
        ]);
    }
}
