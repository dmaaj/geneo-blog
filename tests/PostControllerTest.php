<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class PostControllerTest extends WebTestCase
{
    public function testCreatePost()
    {
        $client = $this->createAuthorizedClient();

        $crawler = $client->request('GET', '/dashboard/create');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Publish')->form();

        $form['create_post_form[title]'] = 'test';
        $form['create_post_form[content]'] = 'test is a good thing';

        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertSelectorTextContains('h2', 'Your Posts');

    }

    public function testViewPost()
    {
        $client = $this->createAuthorizedClient();

        $crawler = $client->request('GET', '/@admin/test');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p', 'test is a good thing');
       
    }

    public function testPostComment()
    {
        $client = $this->createAuthorizedClient();
        
        $crawler = $client->request('GET', '/@admin/test');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Comment')->form();

        $form['create_post_comment_form[comment]'] = 'I think this works';

        $crawler = $client->submit($form);

        $this->assertResponseIsSuccessful();
        
        $this->assertSelectorTextContains('p.mt-2', 'I think this works');

    }

    protected function createAuthorizedClient()
    {
        $client = static::createClient();
        // Get session
        $session = self::$container->get('session');


        $person = self::$container->get('doctrine')->getRepository(User::class)->findOneByEmail('admin@geneo.com');

        $token = new UsernamePasswordToken($person,$person->getPassword(), 'main', $person->getRoles());
        
        $session->set('_security_main', serialize($token));
        $session->save();
 
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }
}
