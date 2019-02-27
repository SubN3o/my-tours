<?php
/**
 * Created by PhpStorm.
 * User: HaGii
 * Date: 12/06/2017
 * Time: 16:49
 */

namespace MyToursBundle\Controller\front;

use MyToursBundle\Entity\Accueil;
use MyToursBundle\Entity\Client;
use MyToursBundle\Entity\Collaborateur;
use MyToursBundle\Entity\Evenement;
use MyToursBundle\Entity\Media;
use MyToursBundle\Entity\Partenaire;
use MyToursBundle\Entity\Realisation;
use MyToursBundle\Service\FormulaireContact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AgenceController extends Controller
{
    /**
     * @Route("/my-tours", name="my-tours")
     */
    public function agencyAction(FormulaireContact $formulaireContact, Request $request)
    {
        //tableaux des mois en français
        $mois = [
            '01' => 'janvier',
            '02' => 'février',
            '03' => 'mars',
            '04' => 'avril',
            '05' => 'mai',
            '06' => 'juin',
            '07' => 'juillet',
            '08' => 'août',
            '09' => 'septembre',
            '10' => 'octobre',
            '11' => 'novembre',
            '12' => 'décembre',
        ];

        $em = $this->getDoctrine()->getManager();

        //récupération des partenaires
        $partenaires = $em->getRepository(Partenaire::class)->findBy([], ['tri'=>'ASC']);

        //récupération des collaborateurs dans la limite de 4 pour le barrillet
        //prévoir de modifier les angles dans le CSS si besoin de changer le nb de collaborateur
        $collaborateurs = $em->getRepository(Collaborateur::class)->findBy([], ['tri'=>'ASC'],4,0);

        //récupération des evenements et de leurs images
        $evenements = $em->getRepository(Evenement::class)->findBy([], ['dateDebut'=>'ASC']);
        $cover = $em->getRepository(Media::class)->findAll();

        //récupération de l'id1 de Accueil où sont stocké la video d'intro, le texte de présentation et les images pour le slider My Tours
        $accueil = $em->getRepository(Accueil::class)->find(1);

        //récupération des realisations
        $realisations = $em->getRepository(Realisation::class)->findBy([], ['tri' => 'ASC']);

        // Formulaire de contact

        //Récupération du téléphone de l'agence depuis les paramètres
        $telephone_number = $this->getParameter('telephone_number');

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

            return $this->redirectToRoute('my-tours');
        }

        return $this->render('MyToursBundle::my-tours.html.twig',
            [
                'realisations' => $realisations,
                'telephone_number' => $telephone_number,
                'mois' => $mois,
                'partenaires' => $partenaires,
                'collaborateurs' => $collaborateurs,
                'evenements' => $evenements,
                'cover' => $cover,
                'accueil' => $accueil,
                'form' => $formulaire->createView()

            ]
        );
    }
}