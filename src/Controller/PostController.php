<?php

namespace App\Controller;

use App\Entity\Post;
use App\DTO\CreatePostDto;
use App\Entity\PostComment;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{Request, Response};
use App\Repository\{PostCommentRepository, PostRepository};
use App\Form\{CreatePostCommentFormType, CreatePostFormType, DeletePostFormType};
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
     * @Route("/@{author}/{slug}", name="index", methods={"GET","POST"})
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

            $this->denyAccessUnlessGranted('create', new PostComment());
            
            $this->postCommentRepository->create($form->getData(), $post, $this->getUser());

            $this->addFlash('success', 'Successfully commented');
            
            $this->redirect($request->headers->get('referer'));
        }

        return $this->render('post/index.html.twig', [
            'post' => $post,
            'commentForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard/create", name="create", methods={"GET","POST"})
     */
    public function create(Request $request, CreatePostDto $post) :Response
    {
        $this->denyAccessUnlessGranted('create', new Post());

        $form = $this->createForm(CreatePostFormType::class, $post);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $this->postRepository->create($post, $this->getUser());

            $this->addFlash('success', 'Successfully created post');
            
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('post/create.html.twig', [
            'createPost' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard/p/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Post $post, Request $request)
    {
        $this->denyAccessUnlessGranted('edit', $post);
        
        $postDto = CreatePostDto::fromPost($post);

        $form = $this->createForm(CreatePostFormType::class, $postDto);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $this->postRepository->update($postDto, $post);

            $this->addFlash('success', 'Successfully updated post');
            
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('post/create.html.twig', [
            'createPost' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard/p/{id}/delete", name="delete", methods={"GET","Delete"})
     */
    public function delete(Post $post, Request $request)
    {
        $this->denyAccessUnlessGranted('edit', $post);

        $postDto = CreatePostDto::fromPost($post);

        $form = $this->createForm(DeletePostFormType::class, $postDto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postRepository->delete($post);

            $this->addFlash('success', 'Successfully deleted post');

            return $this->redirectToRoute('dashboard');
        }
        
        return $this->render('post/delete_modal.html.twig', [
            'delete_form' => $form->createView(),
            'post' => $post,
        ]);

    }


}
