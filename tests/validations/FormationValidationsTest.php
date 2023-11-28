<?php

namespace App\Tests\Validations;

use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;




class FormationValidationsTest extends KernelTestCase {

    public function getFormation(): Formation{
      return (new Formation())
      ->setPublishedAt(new \DateTime("2023-01-01"))
      ->setTitle("Test")
      ->setVideoId("")
      ->setPlaylist(new Playlist());
    }

    public function assertErrors(Formation $formation, int $nbErreursAttendues, string $message=""){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error, $message);
    }
    public function testValidDateFormation(){
        $formation = $this->getFormation()->setPublishedAt(new \DateTime("2023-01-02"));
        $this->assertErrors($formation, 0);
    }
    public function testNonValidDateFormarion(){
        $formation = $this->getFormation()->setPublishedAt(new \DateTime("2024-01-02"));
        $this->assertErrors($formation, 1);
    }

}