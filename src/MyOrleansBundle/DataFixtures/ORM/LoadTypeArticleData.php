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
use MyOrleansBundle\Entity\TypeArticle;

class LoadTypeArticleData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $typeArticle1 = new TypeArticle();
        $typeArticle2 = new TypeArticle();
        $typeArticle3 = new TypeArticle();


        $typeArticle1->setNom('Fiches conseils');
        $typeArticle2->setNom('Dossier thématiques');
        $typeArticle3->setNom('Actualités immobilières');


        $manager->persist($typeArticle1);
        $manager->persist($typeArticle2);
        $manager->persist($typeArticle3);

        $manager->flush();

    }

}