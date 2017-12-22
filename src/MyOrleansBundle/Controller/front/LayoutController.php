<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 12/09/2017
 * Time: 12:20
 */

namespace MyOrleansBundle\Controller\front;


use MyOrleansBundle\Entity\Accueil;
use MyOrleansBundle\Entity\Article;
use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Pack;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Entity\Service;
use MyOrleansBundle\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LayoutController extends Controller
{
    public function navBarAction(Request $request)
    {
        // Fin recuperation des villes
        $simpleSearch = $this->createForm('MyOrleansBundle\Form\SimpleSearchType',
            null,
            ['action' => $this->generateUrl('nosresidences')]);

        $simpleSearchMobile = $this->createForm('MyOrleansBundle\Form\SimpleSearchMobileType',
            null,
            ['action' => $this->generateUrl('nosresidences')]);

        return $this->render('MyOrleansBundle::navBar.html.twig', [
            'simpleSearch' => $simpleSearch->createView(),
            'simpleSearchMobile' => $simpleSearchMobile->createView(),
        ]);
    }

    public function footerAction()
    {
        $em = $this->getDoctrine()->getManager();

        $programmes = $em->getRepository(Residence::class)->findBy([], ['tri' => 'ASC'], 7,0);

        $services = $em->getRepository(Service::class)->findBy([], ['tri' => 'ASC']);

        $packs = $em->getRepository(Pack::class)->findBy([], ['tri' => 'ASC']);

        $articles = $em->getRepository(Article::class)->articleByTri();

        $accueil = $em->getRepository(Accueil::class)->find(1);

        return $this->render('MyOrleansBundle::footer.html.twig', [
            'programmes' => $programmes,
            'services' => $services,
            'packs' => $packs,
            'articles' => $articles,
            'accueil' => $accueil
        ]);
    }
}