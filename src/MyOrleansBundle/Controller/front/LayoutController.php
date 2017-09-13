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
use MyOrleansBundle\Entity\Pack;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Entity\Service;
use MyOrleansBundle\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LayoutController extends Controller
{
    public function footerAction()
    {
        $em = $this->getDoctrine()->getManager();

        $programmes = $em->getRepository(Residence::class)->findAll();

        $services = $em->getRepository(Service::class)->findBy([], ['tri'=>'ASC']);

        $packs = $em->getRepository(Pack::class)->findBy([], ['tri'=>'ASC']);

        $articles = $em->getRepository(Article::class)->findBy([], ['date'=>'DESC'], 3,0);

        $accueil = $em->getRepository(Accueil::class)->find(1);

        return $this->render('MyOrleansBundle::footer.html.twig',[
            'programmes'=>$programmes,
            'services'=>$services,
            'packs'=>$packs,
            'articles'=>$articles,
            'accueil'=>$accueil
        ]);
    }
}