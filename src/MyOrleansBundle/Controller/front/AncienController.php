<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 12/01/2018
 * Time: 09:23
 */

namespace MyOrleansBundle\Controller\front;


use MyOrleansBundle\Entity\Ancien;
use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Service\FormulaireContact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AncienController extends Controller
{
    /**
     * @Route("ancien/{reference}", name="ancien")
     * @ParamConverter("ancien", class="MyOrleansBundle:Ancien", options={"reference" = "reference"})
     */
    public function location(Ancien $ancien, Request $request, FormulaireContact $formulaireContact)
    {
        $em = $this->getDoctrine()->getManager();

        // Formulaire de contact
        $client = new  Client();

        $telephoneNumber = $this->getParameter('telephone_number');

        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $formulaireContact->formulaireContact($client);

            $client->setDate(new \Datetime());

            $em->persist($client);
            $em->flush();

            $this->addFlash('success', 'Votre message a bien été envoyé');

            return $this->redirectToRoute('ancien',['reference'=>$ancien->getReference()]);
        }

        return $this->render('MyOrleansBundle::ancien.html.twig', [
            'ancien'=>$ancien,
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView()
        ]);
    }
}