<?php
/**
 * Created by PhpStorm.
 * User: wilder3
 * Date: 10/07/17
 * Time: 18:48
 */

namespace MyToursBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MyToursBundle\Entity\TypeMedia;

class LoadTypeMediaData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $typeMedia1 = new TypeMedia();
        $typeMedia2 = new TypeMedia();
        $typeMedia3 = new TypeMedia();
        $typeMedia4 = new TypeMedia();
        $typeMedia5 = new TypeMedia();
        $typeMedia6 = new TypeMedia();
        $typeMedia7 = new TypeMedia();
        $typeMedia8 = new TypeMedia();
        $typeMedia9 = new TypeMedia();

        $typeMedia1->setId(TypeMedia::IMAGE_COVER);
        $typeMedia1->setNom('image-cover');

        $typeMedia2->setId(TypeMedia::IMAGE);
        $typeMedia2->setNom('image');

        $typeMedia3->setId(TypeMedia::IMAGE_HEADER);
        $typeMedia3->setNom('image-header');

        $typeMedia4->setId(TypeMedia::VIDEO);
        $typeMedia4->setNom('video');

        $typeMedia5->setId(TypeMedia::PLAN);
        $typeMedia5->setNom('plan');

        $typeMedia6->setId(TypeMedia::PDF);
        $typeMedia6->setNom('pdf');

        $typeMedia7->setId(TypeMedia::ICONE);
        $typeMedia7->setNom('icone');

        $typeMedia8->setId(TypeMedia::PLAQUETTE);
        $typeMedia8->setNom('plaquette');

        $typeMedia9->setId(TypeMedia::NOTICE);
        $typeMedia9->setNom('notice');

        $manager->persist($typeMedia1);
        $manager->persist($typeMedia2);
        $manager->persist($typeMedia3);
        $manager->persist($typeMedia4);
        $manager->persist($typeMedia5);
        $manager->persist($typeMedia5);
        $manager->persist($typeMedia6);
        $manager->persist($typeMedia7);
        $manager->persist($typeMedia8);
        $manager->persist($typeMedia9);


        $manager->flush();

    }

}