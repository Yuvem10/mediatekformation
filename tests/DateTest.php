<?php

namespace App\Tests;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;


class DateTest extends TestCase {

    public function testGetPublishedAtString(){
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime('2023-01-01'));
        $this->assertEquals("01/01/2023", $formation->getPublishedAtString());
    }
}