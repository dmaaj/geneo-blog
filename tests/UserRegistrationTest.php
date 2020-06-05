<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRegistrationTest extends WebTestCase
{
    public function testRegistration()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Register')->form();

        $form['registration_form[username]'] = 'user5';
        $form['registration_form[email]'] = 'user5@user.com';
        $form['registration_form[plainPassword]'] = 'P3ssword';
        $form['registration_form[agreeTerms]'] = true;

        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertSelectorTextContains('h2', 'Your Posts');
    }
    
}
