<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\PostComment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PostCommentVoter extends Voter
{
    const VIEW = 'view';
    const CREATE = 'create';


    private $security;

    public function __construct(Security $security)
    {   
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::VIEW, self::CREATE])
            && $subject instanceof \App\Entity\PostComment;
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
            case self::VIEW:
                return $this->canView($subject, $user);

            case self::CREATE:
                return $this->canCreate($subject, $user);
        }

        return false;
    }

    protected function canView(PostComment $comment, User $user)
    {
        // anybody can view comment
        return true;
    }

    protected function canCreate(PostComment $comment, User $user)
    {
        // everyone can comment except this guy
        if ($this->security->isGranted(User::GUEST)){
            return false;
        };

        return true;
    }
}
