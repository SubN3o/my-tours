<?php
/**
 * Created by PhpStorm.
 * User: wilder8
 * Date: 27/06/17
 * Time: 11:52
 */

namespace MyOrleansBundle\Controller\front;

use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Flat;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Entity\TypeLogement;
use MyOrleansBundle\Service\CalculateurCaracteristiquesResidence;
use MyOrleansBundle\Service\FormulaireContact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ResidencesController extends Controller
{
    /**
     * @Route("/{Ville}/residence-{slug}/", name="residences")
     * @ParamConverter("residence", class="MyOrleansBundle:Residence", options={"slug" = "slug"})
     * @ParamConverter("residence", class="MyOrleansBundle:Residence", options={"Ville" = "Ville"})
     */
    public function residenceAction(Residence $residence, FormulaireContact $formulaireContact, Request $request, CalculateurCaracteristiquesResidence $calculator)
    {

        $em = $this->getDoctrine()->getManager();

        //récupération des appartements par type
        $flatsT1 = $em->getRepository(Flat::class)->flatByType($residence, 'T1');
        $flatsT2 = $em->getRepository(Flat::class)->flatByType($residence, 'T2');
        $flatsT3 = $em->getRepository(Flat::class)->flatByType($residence, 'T3');
        $flatsT4 = $em->getRepository(Flat::class)->flatByType($residence, 'T4');
        $flatsT5 = $em->getRepository(Flat::class)->flatByType($residence, 'T5+');

        //récupération des tpes de logement
        $typelogment = $em->getRepository(TypeLogement::class)->findAll();

        //récupération du 1er prix
        $prixMin = $calculator->calculPrix($residence);

        //récupération des appartements disponibles
        $flatsDispo = $calculator->calculFlatDispo($residence);

        //récupération du type min et max disponible
        $typeMinMax = $calculator->calculSizes($residence);

        //récupération des medias de la résidence
        $medias = $residence->getMedias();

        //récupération des appartements disponibles par type
        $T1Dispo = $em->getRepository(Flat::class)->flatDispoByType($residence, 'T1');
        $T2Dispo = $em->getRepository(Flat::class)->flatDispoByType($residence, 'T2');
        $T3Dispo = $em->getRepository(Flat::class)->flatDispoByType($residence, 'T3');
        $T4Dispo = $em->getRepository(Flat::class)->flatDispoByType($residence, 'T4');
        $T5Dispo = $em->getRepository(Flat::class)->flatDispoByType($residence, 'T5+');

        //récupération de l'id de la résidence pour suggerer d'autre résidence
        $idResidence [] = $residence->getId();
        $residencesSuggerees = $em->getRepository(Residence::class)->suggestResidence($idResidence);

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
            return $this->redirectToRoute('residences',['slug'=>$residence->getSlug(),'Ville'=>$residence->getVille()->getSlug()]);
        }


        return $this->render('MyOrleansBundle::residence.html.twig', [
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView(),
            'residence' => $residence,
            'flatsT1' => $flatsT1,
            'flatsT2' => $flatsT2,
            'flatsT3' => $flatsT3,
            'flatsT4' => $flatsT4,
            'flatsT5' => $flatsT5,
            'media' => $medias,
            'prixMin' => $prixMin,
            'flatsDispo' => $flatsDispo,
            'typeMin' => $typeMinMax[0],
            'typeMax' => $typeMinMax[1],
            'typeLogement' => $typelogment,
            'T1Dispo' => $T1Dispo,
            'T2Dispo' => $T2Dispo,
            'T3Dispo' => $T3Dispo,
            'T4Dispo' => $T4Dispo,
            'T5Dispo' => $T5Dispo,
            'residencesSuggerees' => $residencesSuggerees,

        ]);


    }

}