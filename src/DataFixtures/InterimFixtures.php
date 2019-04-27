<?php

namespace App\DataFixtures;

use App\Entity\Interim;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class InterimFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $interim = new Interim();
        $interim->setFirstname('John');
        $interim->setName('Doe');
        $interim->setMail('john@test.com');
        $interim->setZipcode('59000');
        $manager->persist($interim);

        $interim2 = new Interim();
        $interim2->setFirstname('Jessie');
        $interim2->setName('James');
        $interim2->setMail('jess@test.com');
        $interim2->setZipcode('38000');
        $interim2->setCity('Grenoble');
        $manager->persist($interim2);

        $interim3 = new Interim();
        $interim3->setFirstname('Jane');
        $interim3->setName('Doe');
        $interim3->setMail('jane@test.com');
        $interim3->setZipcode('38100');
        $manager->persist($interim3);


        $manager->flush();
    }
}