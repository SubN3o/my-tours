<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 15/01/2018
 * Time: 16:05
 */

namespace MyOrleansBundle\Controller\front;


use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Faq;
use MyOrleansBundle\Service\FormulaireContact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FaqController extends Controller
{
    /**
     * @Route("/faq", name="faq")
     */
    public function faqAction(Request $request, FormulaireContact $formulaireContact)
    {
        $em = $this->getDoctrine()->getManager();

        $faqs = $em->getRepository(Faq::class)->findBy([],['tri'=>'ASC']);

        // Formulaire de contact

        //Récupération du téléphone de l'agence depuis les paramètres
        $telephoneNumber = $this->getParameter('telephone_number');

        //Instantacion d'un nouveau client
        $client = new Client();

        //Création du formulaire
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);

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

            return $this->redirectToRoute('faq');
        }



        return $this->render('MyOrleansBundle::faq.html.twig',  [
            'faqs' => $faqs,
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView()
        ]);
    }
}