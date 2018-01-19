<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 09/01/2018
 * Time: 10:30
 */

namespace MyOrleansBundle\Controller\front;


use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Location;
use MyOrleansBundle\Service\CalculateurHonoraires;
use MyOrleansBundle\Service\FormulaireContact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LocationController extends Controller
{
    /**
     * @Route("location/{reference}", name="location")
     * @ParamConverter("location", class="MyOrleansBundle:Location", options={"reference" = "reference"})
     */
    public function location(Location $location, Request $request, CalculateurHonoraires $calculateurHonoraires, FormulaireContact $formulaireContact)
    {
        $em = $this->getDoctrine()->getManager();

        //calcul des honoraires selon la surface
        $honoraires = $calculateurHonoraires->calculHonoraires($location->getSurface());
        $etatLieux = $calculateurHonoraires->calculHonoraireEtat($location->getSurface());

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

            return $this->redirectToRoute('location',['reference'=>$location->getReference()]);
        }

        return $this->render('MyOrleansBundle::location.html.twig', [
           'location'=>$location,
            'honoraires'=>$honoraires,
            'etatLieux'=>$etatLieux,
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView()
        ]);
    }
}