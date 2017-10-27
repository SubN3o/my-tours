<?php

namespace MyOrleansBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyOrleansBundle\Entity\TypeBien;

class LoadTypeBienData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $typeBien1 = new TypeBien();
        $typeBien2 = new TypeBien();
        $typeBien3 = new TypeBien();

        $typeBien1->setNom('Appartement');
        $typeBien2->setNom('Maison');
        $typeBien3->setNom('Duplex');

        $manager->persist($typeBien1);
        $manager->persist($typeBien2);
        $manager->persist($typeBien3);

        $manager->flush();

    }

}