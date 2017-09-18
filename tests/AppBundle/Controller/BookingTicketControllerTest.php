<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\BookingTicket;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class BookingTicketControllerTest extends WebTestCase
{
    private $client;

    // tests fonctionnels

    public function testPage1()// on teste la page1 contenu et retour http
    {

        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Formulaire de réservation', $crawler->filter('.container h3')->text());
    }
    public function testPage2()
    {
        $this->client->request('GET', '/ticketOrder');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/');
        $this->client->request('GET', '/ticketOrder');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());


    }
    public function tearDown()//exécuté après chaque test pour préparer le suivant (destruction session)
    {
       $this->client->getRequest()->getSession()->invalidate();
    }


    public function setUp()//exécuter avant chaque test
    {
       $this->client=static::createClient();
    }
}
