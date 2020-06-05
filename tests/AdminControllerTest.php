<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class AdminControllerTest extends WebTestCase
{
    public function testRedirectToIfNotLoggedIn()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin');

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertTrue($client->getRequest()->getRequestUri() === '/login');
    }

    public function testOnlyAdminAllowedAccess()
    {
        $client = $this->createAuthorizedClient();

        $crawler = $client->request('GET', '/admin');


        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('h2', 'Admin Dashboard');
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
