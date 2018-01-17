<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 21/11/2017
 * Time: 14:30
 */

namespace MyOrleansBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;
use MyOrleansBundle\Entity\Article;
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

        //recupération des routes des pages principales du site
        $menu = [
            'my-orleans',
            'expertise',
            'nosresidences',
            'immo_pratique',
            'nosanciens',
            'noslocations',
            'faq'
            ];

        //géneration des url
        foreach ($menu as $page) {
            $urls[] = array(
                'loc' => $this->router->generate($page)
            );
        }

        //récupérations des routes de chaque résidence
        $residences = $this->em->getRepository(Residence::class)->findAll();

        //génération des url
        foreach ($residences as $residence) {
            $urls[] = array(
                'loc' => $this->router->generate('residences', array('Ville' => $residence->getVille()->getSlug(),'slug' => $residence->getSlug()),true)
            );
        }

        //récupération des routes de chaque appartement
        $flats = $this->em->getRepository(Flat::class)->findAll();

        //génération des url
        foreach ($flats as $flat) {
            $urls[] = array(
                'loc' => $this->router->generate('appartement', array('slug' => $flat->getResidence()->getSlug(),'reference' => $flat->getReference()),true)
            );
        }

        //récupération des routes de chaque appartement
        $articles = $this->em->getRepository(Article::class)->findAll();

        //génération des url
        foreach ($articles as $article) {
            $urls[] = array(
                'loc' => $this->router->generate('article', array('slug' => $article->getSlug()),true)
            );
        }

        $requete = [
            '/neufs/search?complete_search%5Bville%5D=Olivet&complete_search%5BbudgetMin%5D=&complete_search%5BbudgetMax%5D=&complete_search%5Bsubmit%5D=&complete_search%5B',
            '/neufs/search?complete_search%5Bville%5D=Saint-Pryvé-Saint-Mesmin&complete_search%5BbudgetMin%5D=&complete_search%5BbudgetMax%5D=&complete_search%5Bsubmit%5D=&complete_search%5B',
            '/neufs/search?complete_search%5Bville%5D=Orléans&complete_search%5BbudgetMin%5D=&complete_search%5BbudgetMax%5D=&complete_search%5Bsubmit%5D=&complete_search%5B',
            '/neufs/search?complete_search%5Bville%5D=Saint-Jean-de-Braye&complete_search%5BbudgetMin%5D=&complete_search%5BbudgetMax%5D=&complete_search%5Bsubmit%5D=&complete_search%5B',
            '/neufs/search?complete_search%5Bville%5D=Saint-Jean-le-Blanc&complete_search%5BbudgetMin%5D=&complete_search%5BbudgetMax%5D=&complete_search%5Bsubmit%5D=&complete_search%5B',
            '/neufs/search?complete_search%5Bville%5D=Ormes&complete_search%5BbudgetMin%5D=&complete_search%5BbudgetMax%5D=&complete_search%5Bsubmit%5D=&complete_search%5B',
            '/neufs/search?complete_search%5Bville%5D=Ingré&complete_search%5BbudgetMin%5D=&complete_search%5BbudgetMax%5D=&complete_search%5Bsubmit%5D=&complete_search%5B',
        ];
        //géneration des url
        foreach ($requete as $resultat) {
            $urls[] = array(
                'loc' => $resultat
            );
        }

        return $urls;
    }
}