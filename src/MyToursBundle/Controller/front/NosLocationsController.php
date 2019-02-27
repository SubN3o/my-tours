<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 08/01/2018
 * Time: 14:46
 */

namespace MyToursBundle\Controller\front;


use MyToursBundle\Entity\Client;
use MyToursBundle\Entity\Location;
use MyToursBundle\Service\FormulaireContact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NosLocationsController extends Controller
{
    /**
     * @Route("/location", name="noslocations")
     */
    public function locationAction(Request $request, FormulaireContact $formulaireContact)
    {
        $em = $this->getDoctrine()->getManager();

        $locations = $em->getRepository(Location::class)->findBy([],['statut'=>'DESC']);

        $locationFiltre = $this->createForm('MyToursBundle\Form\FiltreLocationType');
        $locationFiltre->handleRequest($request);

        if ($locationFiltre->isSubmitted() && $locationFiltre->isValid()){

            $data = $locationFiltre->getData();

            $selectedFilter = $data['filter'];

            if ($selectedFilter === 1){
                $locations = $em->getRepository(Location::class)->findByStatut(1,['loyer'=>'ASC']);
            } elseif ($selectedFilter === 2) {
                $locations = $em->getRepository(Location::class)->findByStatut(1,['loyer'=>'DESC']);
            } elseif ($selectedFilter === 3) {
                $locations = $em->getRepository(Location::class)->findByStatut(1,['surface'=>'ASC']);
            } elseif ($selectedFilter === 4) {
                $locations = $em->getRepository(Location::class)->findByStatut(1,['surface'=>'DESC']);
            }

        }

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

            return $this->redirectToRoute('noslocations');
        }

        return $this->render('MyToursBundle::nosLocations.html.twig', [
            'locations' => $locations,
            'telephone_number' =>$telephoneNumber,
            'form' => $formulaire->createView(),
            'locationFiltre' => $locationFiltre->createView(),

        ]);
    }
}