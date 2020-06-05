<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\ScopeRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class PostVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';

    private $security;

    private $scopeRepository;

    public function __construct(Security $security, ScopeRepository $scopeRepository)
    {   
        $this->security = $security;
        $this->scopeRepository = $scopeRepository;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::CREATE])
            && $subject instanceof \App\Entity\Post;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted(User::ADMIN)) {
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);

            case self::VIEW:
                return $this->canView($subject, $user);

            case self::CREATE:
                return $this->canCreate($subject, $user);
        }

        return false;
    }

    protected function canView(Post $post, User $user)
    {
        // anybody can view a post
        return true;
    }

    protected function canEdit(Post $post, User $user)
    {
        return $user === $post->getAuthor();
    }

    // ACL is deprecated for symfony 4.0 upward
    // implemented a scope/grant structure for voter
    protected function canCreate(Post $post, User $user)
    {
        $required_permission = $this->scopeRepository->findOneBy(['grants' => 'post.create']);
        
        if(!$user->getScopes()->contains($required_permission)) {
            return false;
        }

        return true;
    }
}
