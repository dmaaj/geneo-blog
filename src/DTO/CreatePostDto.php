<?php

namespace App\DTO;

use App\Entity\Post;
use Symfony\Component\Validator\Constraints as Assert;



class CreatePostDto
{

    /**
     * @Assert\NotBlank
     */
    public $title;

    /**
     * @Assert\NotBlank
     */
    public $content;

    
    public static function fromPost(Post $post)
    {
        $dto = new self();
        $dto->title = $post->getTitle();
        $dto->content = $post->getContent();
        return $dto;
    }
}