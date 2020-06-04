<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="admin.")
 */
class AdminController extends AbstractController
{
    private $userRepository;

    private $postRepository;

    public function __construct(UserRepository $userRepository, PostRepository $postRepository)
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
    }
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $users = $this->userRepository->getAllUserWithPostCount();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/{id}", name="user")
     */
    public function user(User $user)
    {
        return $this->render('admin/user.html.twig', [
            'user' => $user,
        ]);
    }
}
