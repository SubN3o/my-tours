<?php
/**
 * Created by PhpStorm.
 * User: wilder3
 * Date: 10/07/17
 * Time: 18:48
 */

namespace MyToursBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyToursBundle\Entity\Residence;
use MyToursBundle\Entity\Tag;
use MyToursBundle\Entity\TypeArticle;
use MyToursBundle\Entity\Ville;

class LoadResidenceData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $res = new Residence();
        $res->setNom('Dunois');
        $res->setAdresse('1 rue Bannier');
        $res->setCodePostal('45000');
        $res->setVille($this->getReference('ville11'));
        $res->setAffichagePrix(true);
        $res->setNbTotalLogements(30);

        $manager->persist($res);

        $res2 = new Residence();
        $res2->setNom('Coté Sud');
        $res2->setAdresse('12, rue Emile Zola');
        $res2->setCodePostal('45000');
        $res2->setVille($this->getReference('ville11'));
        $res2->setAffichagePrix(true);
        $res2->setNbTotalLogements(45);


        $manager->persist($res2);

        $res3 = new Residence();
        $res3->setNom('Les Terrasses du Loiret');
        $res3->setAdresse('1 rue du Champ de Mars');
        $res3->setCodePostal('45000');
        $res3->setVille($this->getReference('ville11'));
        $res3->setAffichagePrix(true);
        $res3->setNbTotalLogements(50);


        $manager->persist($res3);

        $res4 = new Residence();
        $res4->setNom('Private');
        $res4->setAdresse('1 rue de Montauban');
        $res4->setCodePostal('45000');
        $res4->setVille($this->getReference('ville11'));
        $res4->setAffichagePrix(true);
        $res4->setNbTotalLogements(20);


        $manager->persist($res4);


        $manager->flush();

    }

    public function getOrder()
    {
        return 2;
    }

}