<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class UserRepositoryTest extends KernelTestCase {

    public function recupRepository(): UserRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(UserRepository::class);
        return $repository;
    }

    public function newUser(): User{
        $user = (new User())
                ->setEmail("Test@test.test")
                ->setPassword("exemple");
        return $user;
    }

    public function testAddUser(){
        $repository = $this->recupRepository();
        $user = $this->newUser();
        $nbUsers = $repository->count([]);
        $repository->add($user, true);
        $this->assertEquals($nbUsers + 1, $repository->count([]), "erreur lors de l'ajout");
    }

    public function testRemoveUser(){
        $repository = $this->recupRepository();
        $user = $this->newUser();
        $repository->add($user, true);
        $nbUsers = $repository->count([]);
        $repository->remove($user, true);
        $this->assertEquals($nbUsers - 1, $repository->count([]), "erreur lors de la suppression");        
    }
}