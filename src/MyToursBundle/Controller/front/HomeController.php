<?php

namespace MyToursBundle\Controller\front;

use MyToursBundle\Entity\Accueil;
use MyToursBundle\Entity\Client;
use MyToursBundle\Entity\Collaborateur;
use MyToursBundle\Entity\Temoignage;
use MyToursBundle\Entity\Residence;
use MyToursBundle\Service\FormulaireContact;
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

        //récupération des collaborateurs dans la limite de 4 pour le barrillet
        //prévoir de modifier les angles dans le CSS si besoin de changer le nb de collaborateur
        $collaborateurs = $em->getRepository(Collaborateur::class)->findBy([], ['tri'=>'ASC'],4,0);

        //récupération de l'id1 de Accueil où sont stocké la video d'intro, les mentions légales et les honoraires
        $accueil = $em->getRepository(Accueil::class)->find(1);

        //récupération des résidences par colonne pour la mosaïque
        $residenceCol1 = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC'],3,2);
        $residenceCol2 = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC'],2,0);
        $residenceCol3 = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC'],2,5);

        //récupération des temoignages
        $temoignages = $em->getRepository(Temoignage::class)->findAll();


        // Formulaire de contact

        //Récupération du téléphone de l'agence depuis les paramètres
        $telephoneNumber = $this->getParameter('telephone_number');

        //Instantacion d'un nouveau client
        $client = new Client();

        //Création du formulaire
        $formulaire = $this->createForm('MyToursBundle\Form\FormulaireType', $client);

        //Récupération des infos rentrées du formulaire
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            //Utilisation du service FormulaireContact
            $formulaireContact->formulaireContact($client);

            //La date est setter à la création du client
            $client->setDate(new \Datetime());

            //Enregistrement du client dans la BDD
            $em->persist($client);
            $em->flush();

            //Création d'un message de réussite
            $this->addFlash('success', 'Votre message a bien été envoyé');

            return $this->redirectToRoute('home');
        }

        return $this->render('MyToursBundle::index.html.twig', [
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
        return $this->render('MyToursBundle::admin.html.twig');
    }


}
