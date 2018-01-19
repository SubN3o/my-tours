<?php

namespace MyOrleansBundle\Controller\front;

use MyOrleansBundle\Entity\Accueil;
use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Collaborateur;
use MyOrleansBundle\Entity\Temoignage;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Service\FormulaireContact;
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
    public function indexAction(Request $request, FormulaireContact $formulaireContact)
    {
        $em = $this->getDoctrine()->getManager();

        //récupération des collaborateurs dans la limite de 5 pour le barrillet
        //prévoir de modifier les angles dans le CSS si besoin de changer le nb de collaborateur
        $collaborateurs = $em->getRepository(Collaborateur::class)->findBy([], ['tri'=>'ASC'],5,0);

        //récupération de l'id1 de Accueil où sont stocké la video d'intro, les mentions légales et les honoraires
        $accueil = $em->getRepository(Accueil::class)->find(1);

        //récupération des résidences par colonne pour la mosaïque
        $residenceCol1 = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC'],3,2);
        $residenceCol2 = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC'],2,0);
        $residenceCol3 = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC'],2,5);

        //récupération des temoignages
        $temoignages = $em->getRepository(Temoignage::class)->findAll();


        // Formulaire de contact
        $telephoneNumber = $this->getParameter('telephone_number');

        $client = new Client();

        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $formulaireContact->formulaireContact($client);

            $client->setDate(new \Datetime());

            $em->persist($client);
            $em->flush();

            $this->addFlash('success', 'Votre message a bien été envoyé');

            return $this->redirectToRoute('home');
        }

        return $this->render('MyOrleansBundle::index.html.twig', [
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView(),
            'collaborateurs' => $collaborateurs,
            'residenceCol1' => $residenceCol1,
            'residenceCol2' => $residenceCol2,
            'residenceCol3' => $residenceCol3,
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
