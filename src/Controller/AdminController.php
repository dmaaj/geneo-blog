<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPermissionFormType;
use App\Form\UserRoleFormType;
use App\Repository\PostRepository;
use App\Repository\ScopeRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin", name="admin.")
 */
class AdminController extends AbstractController
{
    private $userRepository;

    private $scopeRepository;

    public function __construct(UserRepository $userRepository, ScopeRepository $scopeRepository)
    {
        $this->userRepository = $userRepository;
        $this->scopeRepository = $scopeRepository;
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
     * @Route("/{id}", name="user", methods={"GET", "POST"})
     */
    public function user(User $user, Request $request)
    {
        $form = $this->createForm(UserRoleFormType::class);

        $permissionForm = $this->createForm(UserPermissionFormType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $this->userRepository->promoteUser($user, $form->getData());

            $this->addFlash('success', 'Successfully Promoted User');
        }

        return $this->render('admin/user.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'permissionForm' => $permissionForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/change/permission", name="permission", methods={"POST"})
     */
    public function changePermission(User $user, Request $request)
    {
        $form = $this->createForm(UserPermissionFormType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->scopeRepository->changePermission($user, $form->getData());

            $this->addFlash('success', 'Successfully Set User Permission');

            return $this->redirect($request->headers->get('referer'));
            
        }
    }
}
