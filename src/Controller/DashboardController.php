<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
        $posts = $this->getUser()->getPosts();
        
        return $this->render('dashboard/index.html.twig', [
            'posts'           => $posts
        ]);
    }
}
