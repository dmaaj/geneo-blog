<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;

use App\Repository\ScopeRepository;
use App\Repository\UserRepository;
use App\Security\LoginAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationController extends AbstractController
{
    private $scopeRepository;

    private $userRepository;

    public function __construct(ScopeRepository $scopeRepository, UserRepository $userRepository)
    {
        $this->scopeRepository = $scopeRepository;

        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, GuardAuthenticatorHandler $guardHandler, LoginAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $this->userRepository->create($user, $form->get('plainPassword')->getData());

            $this->scopeRepository->setDefaultScope($user);

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
