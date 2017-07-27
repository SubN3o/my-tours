<?php
/**
 * Created by PhpStorm.
 * User: wilder3
 * Date: 10/07/17
 * Time: 18:48
 */

namespace MyOrleansBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyOrleansBundle\Entity\TypeLogement;

class LoadTypeLogementData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $typeLogement1 = new TypeLogement();
        $typeLogement2 = new TypeLogement();
        $typeLogement3 = new TypeLogement();
        $typeLogement4 = new TypeLogement();

        $typeLogement1->setNom('T1');
        $typeLogement2->setNom('T2');
        $typeLogement3->setNom('T3');
        $typeLogement4->setNom('T4+');

        $manager->persist($typeLogement1);
        $manager->persist($typeLogement2);
        $manager->persist($typeLogement3);
        $manager->persist($typeLogement4);

        $manager->flush();

    }

}