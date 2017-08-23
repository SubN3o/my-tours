<?php
/**
 * Created by PhpStorm.
 * User: wilder3
 * Date: 10/07/17
 * Time: 18:48
 */

namespace MyOrleansBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyOrleansBundle\Entity\TypeArticle;
use MyOrleansBundle\Entity\Ville;

class LoadVilleData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $ville1 = new Ville();
        $ville2 = new Ville();
        $ville3 = new Ville();
        $ville4 = new Ville();
        $ville5 = new Ville();
        $ville6 = new Ville();
        $ville7 = new Ville();
        $ville8 = new Ville();
        $ville9 = new Ville();
        $ville10 = new Ville();
        $ville11 = new Ville();
        $ville12 = new Ville();
        $ville13 = new Ville();
        $ville14 = new Ville();
        $ville15 = new Ville();
        $ville16 = new Ville();
        $ville17 = new Ville();
        $ville18 = new Ville();
        $ville19 = new Ville();
        $ville20 = new Ville();


        $ville1->setNom('Boigny-sur-bionne');
        $ville2->setNom('Bou');
        $ville3->setNom('Chanteau');
        $ville4->setNom('Chécy');
        $ville5->setNom('Fleury-les-Aubrais');
        $ville6->setNom('Ingré');
        $ville7->setNom('La Chapelle-Saint-Mesmin');
        $ville8->setNom('Mardié');
        $ville9->setNom('Marigny-les-Usages');
        $ville10->setNom('Olivet');
        $ville11->setNom('Orléans');
        $ville12->setNom('Ormes');
        $ville13->setNom('Saint-Cyr-en-Val');
        $ville14->setNom('Saint-Denis-en-Val');
        $ville15->setNom('Saint-Hilaire-Saint-Mesmin');
        $ville16->setNom('Saint-Jean-de-Braye');
        $ville17->setNom('Saint-Jean-de-la-Ruelle');
        $ville18->setNom('Saint-Jean-le-Blanc');
        $ville19->setNom('Saint-Pryvé-Saint-Mesmin');
        $ville20->setNom('Semoy');


        $manager->persist($ville1);
        $manager->persist($ville2);
        $manager->persist($ville3);
        $manager->persist($ville4);
        $manager->persist($ville5);
        $manager->persist($ville6);
        $manager->persist($ville7);
        $manager->persist($ville8);
        $manager->persist($ville9);
        $manager->persist($ville10);
        $manager->persist($ville11);
        $manager->persist($ville12);
        $manager->persist($ville13);
        $manager->persist($ville14);
        $manager->persist($ville15);
        $manager->persist($ville16);
        $manager->persist($ville17);
        $manager->persist($ville18);
        $manager->persist($ville19);
        $manager->persist($ville20);

        $this->addReference('ville11', $ville11);

        $manager->flush();

    }

    public function getOrder()
    {
        return 1;
    }

}