<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 12/01/2018
 * Time: 09:07
 */

namespace MyOrleansBundle\Controller\front;


use MyOrleansBundle\Entity\Ancien;
use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Service\FormulaireContact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NosAnciensController extends Controller
{
    /**
     * @Route("/ancien", name="nosanciens")
     */
    public function locationAction(Request $request, FormulaireContact $formulaireContact)
    {
        $em = $this->getDoctrine()->getManager();

        $anciens = $em->getRepository(Ancien::class)->findAll();

        $ancienFiltre = $this->createForm('MyOrleansBundle\Form\FiltreAncienType');
        $ancienFiltre->handleRequest($request);

        if ($ancienFiltre->isSubmitted() && $ancienFiltre->isValid()){

            $data = $ancienFiltre->getData();

            $selectedFilter = $data['filter'];

            if ($selectedFilter === 1){
                $anciens = $em->getRepository(Ancien::class)->findBy([],['prix'=>'ASC']);
            } elseif ($selectedFilter === 2) {
                $anciens = $em->getRepository(Ancien::class)->findBy([],['prix'=>'DESC']);
            } elseif ($selectedFilter === 3) {
                $anciens = $em->getRepository(Ancien::class)->findBy([],['surface'=>'ASC']);
            } elseif ($selectedFilter === 4) {
                $anciens = $em->getRepository(Ancien::class)->findBy([],['surface'=>'DESC']);
            }

        }

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

            return $this->redirectToRoute('nosanciens');
        }

        return $this->render('MyOrleansBundle::nosAnciens.html.twig', [
            'anciens' => $anciens,
            'telephone_number' =>$telephoneNumber,
            'form' => $formulaire->createView(),
            'ancienFiltre' => $ancienFiltre->createView(),

        ]);
    }
}