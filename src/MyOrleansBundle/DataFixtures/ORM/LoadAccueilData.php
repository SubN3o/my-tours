<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 28/08/2017
 * Time: 09:35
 */

namespace MyOrleansBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use MyOrleansBundle\Entity\Accueil;

class LoadAccueilData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $accueil = new Accueil();

        $accueil->setId('1');
        $accueil->setPresentation('Presentation');
        $accueil->setMentions('Mentions');

        $manager->persist($accueil);

        $manager->flush();
    }
}