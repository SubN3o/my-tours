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