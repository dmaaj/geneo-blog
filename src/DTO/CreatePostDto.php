<?php

namespace App\DTO;

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
}