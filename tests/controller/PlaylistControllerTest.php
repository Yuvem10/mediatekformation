<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class PlaylistControllerTest extends WebTestCase {

    public function testTriNameAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }
    public function testTriNameDesc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/tri/name/DESC');
        $this->assertSelectorTextContains('h5', 'Visual Studio 2019 et C#');
    }
    public function testTriPlaylistAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/tri/formations/DESC');
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }
    public function testTriPlaylistDesc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/tri/formations/ASC');
        $this->assertSelectorTextContains('h5', 'Cours Informatique embarquée');
    }
    public function testFiltreName(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Bases de la programmation (C#)'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }
    public function testLink(){
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $client->clickLink('Voir détail');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get('REQUEST_URI');
        $this->assertEquals('/playlists/playlist/13', $uri);
        $client->request('GET', '/playlists/playlist/13');
        $this->assertSelectorTextContains('h4', 'Bases de la programmation (C#)');
    }


}