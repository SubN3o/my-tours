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
use MyOrleansBundle\Entity\Tag;
use MyOrleansBundle\Entity\TypeArticle;

class LoadTagData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tag1 = new Tag();
        $tag2 = new Tag();


        $tag1->setNom('Investissement');
        $tag2->setNom('Residence Principale');

        $manager->persist($tag1);
        $manager->persist($tag2);

        $manager->flush();

    }

}