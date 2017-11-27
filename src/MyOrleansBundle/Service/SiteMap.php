<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 21/11/2017
 * Time: 14:30
 */

namespace MyOrleansBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;
use MyOrleansBundle\Entity\Flat;
use MyOrleansBundle\Entity\Residence;
use Symfony\Component\Routing\RouterInterface;

class SiteMap
{
    private $router;
    private $em;

    public function __construct(RouterInterface $router, ObjectManager $em)
    {
        $this->router = $router;
        $this->em = $em;
    }

    /**
     * Génère l'ensemble des valeurs des balises <url> du sitemap.
     *
     * @return array Tableau contenant l'ensemble des balise url du sitemap.
     */
    public function generer()
    {
        $urls = array();

        $menu = [
            'my-orleans',
            'expertise',
            'nosresidences'
            ];

        foreach ($menu as $page) {
            $urls[] = array(
                'loc' => $this->router->generate($page)
            );
        }

        $residences = $this->em->getRepository(Residence::class)->findAll();

        foreach ($residences as $residence) {
            $urls[] = array(
                'loc' => $this->router->generate('residences', array('Ville' => $residence->getVille()->getSlug(),'slug' => $residence->getSlug()),true)
            );
        }

        $flats = $this->em->getRepository(Flat::class)->findAll();

        foreach ($flats as $flat) {
            $urls[] = array(
                'loc' => $this->router->generate('appartement', array('slug' => $flat->getResidence()->getSlug(),'reference' => $flat->getReference()),true)
            );
        }

        return $urls;
    }
}