<?php

namespace MyOrleansBundle\Controller\front;

use MyOrleansBundle\Entity\Accueil;
use MyOrleansBundle\Entity\Article;


use MyOrleansBundle\Entity\CategoriePresta;
use MyOrleansBundle\Entity\Flat;
use MyOrleansBundle\Entity\Media;

use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Collaborateur;
use MyOrleansBundle\Entity\Evenement;
use MyOrleansBundle\Entity\Pack;
use MyOrleansBundle\Entity\Service;
use MyOrleansBundle\Entity\Temoignage;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Entity\TypePresta;
use MyOrleansBundle\Entity\Ville;
use MyOrleansBundle\Form\SimpleSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(SessionInterface $session, Request $request)
    {
//        $parcours = null;
//        if ($session->has('parcours')) {
//            $parcours = $session->get('parcours');
//        }
        $em = $this->getDoctrine()->getManager();

        $collaborateurs = $em->getRepository(Collaborateur::class)->findBy([], ['tri'=>'ASC'],5,0);

        $accueil = $em->getRepository(Accueil::class)->find(1);

//        $residenceFav = $em->getRepository(Residence::class)->findOneFav();
//        $residenceTwoFav = $em->getRepository(Residence::class)->findTwoFav();
        $residenceCol1 = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC'],3,2);
        $residenceCol2 = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC'],2,0);
        $residenceCol3 = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC'],2,5);

        $temoignages = $em->getRepository(Temoignage::class)->findAll();

//        $actu = $em->getRepository(Article::class)->findOneActu();
//        $event = $em->getRepository(Evenement::class)->findOneEvent();


        // Recuperation de la liste des villes dans lesquelles se trouvent les residences
        $residences = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC']);
        $villes = [];
        foreach ($residences as $residence) {
            $villes[] = $residence->getVille();
        }


                

        return $this->render('MyOrleansBundle::index.html.twig', [
//            'parcours' => $parcours,
            'collaborateurs' => $collaborateurs,
//            'residenceFav' => $residenceFav,
//            'residenceTwoFav' => $residenceTwoFav,
            'residenceCol1' => $residenceCol1,
            'residenceCol2' => $residenceCol2,
            'residenceCol3' => $residenceCol3,
//            'actu' => $actu,
//            'event' => $event,
            'temoignages' => $temoignages,
            'accueil' => $accueil

        ]);
    }


    /**
     * @Route("/admin")
     */
    public function admin()
    {
        return $this->render('MyOrleansBundle::admin.html.twig');
    }


}
