<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 09/01/2018
 * Time: 10:30
 */

namespace MyToursBundle\Controller\front;


use MyToursBundle\Entity\Client;
use MyToursBundle\Entity\Location;
use MyToursBundle\Service\CalculateurHonoraires;
use MyToursBundle\Service\FormulaireContact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LocationController extends Controller
{
    /**
     * @Route("location/{reference}", name="location")
     * @ParamConverter("location", class="MyToursBundle:Location", options={"reference" = "reference"})
     */
    public function location(Location $location, Request $request, CalculateurHonoraires $calculateurHonoraires, FormulaireContact $formulaireContact)
    {
        $em = $this->getDoctrine()->getManager();

        //calcul des honoraires selon la surface
        $honoraires = $calculateurHonoraires->calculHonoraires($location->getSurface());
        $etatLieux = $calculateurHonoraires->calculHonoraireEtat($location->getSurface());

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

            return $this->redirectToRoute('location',['reference'=>$location->getReference()]);
        }

        return $this->render('MyToursBundle::location.html.twig', [
           'location'=>$location,
            'honoraires'=>$honoraires,
            'etatLieux'=>$etatLieux,
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView()
        ]);
    }
}