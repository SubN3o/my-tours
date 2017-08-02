<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 02/08/2017
 * Time: 13:57
 */

namespace MyOrleansBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyOrleansBundle\Entity\Zone;

class LoadZoneData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $zone1 = new Zone();
        $zone2 = new Zone();
        $zone3 = new Zone();
        $zone4 = new Zone();

        $zone1->setNom('A');
        $zone2->setNom('B1');
        $zone3->setNom('B2');
        $zone4->setNom('C');

        $manager->persist($zone1);
        $manager->persist($zone2);
        $manager->persist($zone3);
        $manager->persist($zone4);

        $manager->flush();
    }
}