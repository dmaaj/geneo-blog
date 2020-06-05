<?php

namespace App\DataFixtures;

use App\Entity\Scope;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ScopeFixtures extends Fixture
{   
    public function load(ObjectManager $manager)
    {
        $scopes = ['post.create', 'post.delete', 'post.edit', 'post.read', 'comment.create', 'comment.delete'];

        foreach($scopes as $scope){
            $product = new Scope();
            $product->setGrants($scope);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
