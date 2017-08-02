<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 02/08/2017
 * Time: 15:00
 */

namespace MyOrleansBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyOrleansBundle\Entity\NormeThermique;

class LoadNormeThermiqueData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $norme1 = new NormeThermique();
        $norme2 = new NormeThermique();

        $norme1->setNom('RT2012');
        $norme2->setNom('BBC');

        $manager->persist($norme1);
        $manager->persist($norme2);

        $manager->flush();
    }
}