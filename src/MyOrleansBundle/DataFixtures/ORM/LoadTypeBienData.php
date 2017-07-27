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

        $typeBien1->setNom('Appartement');
        $typeBien2->setNom('Maison');

        $manager->persist($typeBien1);
        $manager->persist($typeBien2);

        $manager->flush();

    }

}