<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 12/09/2017
 * Time: 12:20
 */

namespace MyToursBundle\Controller\front;


use MyToursBundle\Entity\Accueil;
use MyToursBundle\Entity\Article;
use MyToursBundle\Entity\Pack;
use MyToursBundle\Entity\Residence;
use MyToursBundle\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LayoutController extends Controller
{
    public function navBarAction(Request $request)
    {
        // Fin recuperation des villes
        $simpleSearch = $this->createForm('MyToursBundle\Form\SimpleSearchType',
            null,
            ['action' => $this->generateUrl('nosresidences')]);

        $simpleSearchMobile = $this->createForm('MyToursBundle\Form\SimpleSearchMobileType',
            null,
            ['action' => $this->generateUrl('nosresidences')]);

        return $this->render('MyToursBundle::navBar.html.twig', [
            'simpleSearch' => $simpleSearch->createView(),
            'simpleSearchMobile' => $simpleSearchMobile->createView(),
        ]);
    }

    public function footerAction()
    {
        $em = $this->getDoctrine()->getManager();

        $programmes = $em->getRepository(Residence::class)->allCity();

        $services = $em->getRepository(Service::class)->findBy([], ['tri' => 'ASC']);

        $packs = $em->getRepository(Pack::class)->findBy([], ['tri' => 'ASC']);

        $articles = $em->getRepository(Article::class)->articleByTri();

        $accueil = $em->getRepository(Accueil::class)->find(1);

        return $this->render('MyToursBundle::footer.html.twig', [
            'programmes' => $programmes,
            'services' => $services,
            'packs' => $packs,
            'articles' => $articles,
            'accueil' => $accueil
        ]);
    }
}