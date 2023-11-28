<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class FormationsControllerTest extends WebTestCase {

    public function testTriNameAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/ASC');
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }
    public function testTriNameDesc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/DESC');
        $this->assertSelectorTextContains('h5', 'UML : Diagramme de paquetages');
    }
    public function testTriPlaylistAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/name/ASC/playlist');
        $this->assertSelectorTextContains('h5', 'Bases de la programmation n°74 - POO : collections');
    }
    public function testTriPlaylistDesc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/name/DESC/playlist');
        $this->assertSelectorTextContains('h5', 'C# : ListBox en couleur');
    }
    public function testTriDateAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/publishedAt/ASC');
        $this->assertSelectorTextContains('h5', 'Cours UML (1 à 7 / 33) : introduction et cas d\'utilisation');
    }
    public function testTriDateDesc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/publishedAt/DESC');
        $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiement');
    }
    public function testFiltreName(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Eclipse n°8 : Déploiement'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiement');
    }
    public function testLink(){
        $client = static::createClient();
        $client->request('GET', '/formations');
        $client->clickLink('miniature');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertEquals('/formations/formation/1', $uri);
        $client->request('GET', '/formations/formation/1');
        $this->assertSelectorTextContains('h4', 'Eclipse n°8 : Déploiement');
    }


}