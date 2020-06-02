<?php

namespace App\Controller;

use App\DTO\CreatePostDto;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{Request, Response};
use App\Repository\{PostCommentRepository, PostRepository};
use App\Form\{CreatePostCommentFormType, CreatePostFormType};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/", name="post.")
 */
class PostController extends AbstractController
{

    private $postRepository;

    private $postCommentRepository;

    public function __construct(PostRepository $postRepository, PostCommentRepository $postCommentRepository)
    {
        $this->postRepository = $postRepository;
        $this->postCommentRepository = $postCommentRepository;
    }
    
    /**
     * @Route("/{author}/{slug}", name="index")
     */
    public function index(String $author, String $slug, Request $request)
    {
        try {
            $post = $this->postRepository->getSinglePost($author, $slug);
        } catch (\Throwable $th) {
            throw $this->createNotFoundException('Page not found');
        }

        $form = $this->createForm(CreatePostCommentFormType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $this->postCommentRepository->create($form->getData(), $post, $this->getUser());
            
            $this->redirect($request->headers->get('referer'));
        }

        return $this->render('post/index.html.twig', [
            'post' => $post,
            'commentForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard/create", name="create")
     */
    public function create(Request $request, CreatePostDto $post) :Response
    {
        $form = $this->createForm(CreatePostFormType::class, $post);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $this->postRepository->create($post, $this->getUser());

            $this->getUser()->setFlash('success', 'Successfully created post');
            
            return $this->redirectToRoute('dashboard');
        }
        return $this->render('post/create.html.twig', [
            'createPost' => $form->createView(),
        ]);
    }
}
