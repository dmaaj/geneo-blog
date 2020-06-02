<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Entity\User;
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

    public function __construct(Security $security)
    {   
        $this->security = $security;
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

        if ($this->security->isGranted('ROLE_ADMIN')) {
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
        return true;
    }

    protected function canEdit(Post $post, User $user)
    {
        return $user === $post->getAuthor();
    }

    protected function canCreate(Post $post, User $user)
    {
        if ($this->security->isGranted('ROLE_GUEST')){
            return false;
        };

        return true;
    }
}
