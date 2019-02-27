<?php
/**
 * Created by PhpStorm.
 * User: jean-baptiste
 * Date: 15/06/17
 * Time: 10:48
 */

namespace MyToursBundle\Controller\front;

use MyToursBundle\Entity\Client;
use MyToursBundle\Service\FormulaireContact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyToursBundle\Entity\Pack;
use MyToursBundle\Entity\Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class ExpertiseController extends Controller
{

    /**
     * @Route("/expertise", name="expertise")
     */

    public function expertiseAction( Request $request, FormulaireContact $formulaireContact)
    {
        $em = $this->getDoctrine()->getManager();

        //récupération des services
        $services = $em->getRepository(Service::class)->findBy([], ['tri'=>'ASC']);

        //récupération des packs
        $packs = $em->getRepository(Pack::class)->findBy([], ['tri'=>'ASC']);

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

            return $this->redirectToRoute('expertise');
        }



        return $this->render('MyToursBundle::expertise.html.twig',  [
            'services' => $services,
            'packs' => $packs,
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView()
        ]);
    }

}



