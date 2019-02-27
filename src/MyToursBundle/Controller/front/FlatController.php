<?php
/**
 * Created by PhpStorm.
 * User: wilder8
 * Date: 02/07/17
 * Time: 14:48
 */

namespace MyToursBundle\Controller\front;



use MyToursBundle\Entity\Flat;
use MyToursBundle\Entity\Client;
use MyToursBundle\Service\FormulaireContact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class FlatController extends Controller
{
    /**
     * @Route("/residence-{slug}/{reference}", name="appartement")
     * @ParamConverter("appartement", class="MyToursBundle:Flat", options={"reference" = "reference"})
     * @ParamConverter("residence", class="MyToursBundle:Residence", options={"slug" = "slug"})
     */
    public function flat(Flat $flat, Request $request, FormulaireContact $formulaireContact)
    {
        $em = $this->getDoctrine()->getManager();

        //récupération de la résidence
        $residence = $flat->getResidence();

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

            return $this->redirectToRoute('appartement',['slug'=>$residence->getSlug(),'reference'=>$flat->getReference()]);
        }

            return $this->render('MyToursBundle::appartement.html.twig',[
                'flat'=>$flat,
                'residence'=>$residence,
                'telephone_number' => $telephoneNumber,
                'form' => $formulaire->createView()
            ]);
        }

}