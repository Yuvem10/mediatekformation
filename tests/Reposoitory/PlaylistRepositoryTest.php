<?php

namespace App\Tests\Repository;

use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class PlaylistRepositoryTest extends KernelTestCase {

    public function recupRepository(): PlaylistRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }
    public function testFindAllOrderByFormationsDesc(){
        $repository = $this->recupRepository();
        $lstPlaylits = $repository->findAllOrderByFormations('DESC');
        $this->assertEquals("Bases de la programmation (C#)", $lstPlaylits[0]->getName());
    }
    public function testFindAllOrderByFormationsAsc(){
        $repository = $this->recupRepository();
        $lstPlaylits = $repository->findAllOrderByFormations('ASC');
        $this->assertEquals("Cours Informatique embarquée", $lstPlaylits[0]->getName());
    }
}