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
    public function location(Location $location, Request $request, CalculateurHonoraires $calculateurHonoraires)
    {
        //calcul des honoraires selon la surface
        $honoraires = $calculateurHonoraires->calculHonoraires($location->getSurface());

        // Formulaire de contact
        $client = new  Client();

        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
        $telephoneNumber = $this->getParameter('telephone_number');
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $mailer = $this->get('mailer');

            $message = new \Swift_Message('Nouveau message de my-orleans.com');
            $message
                ->setTo($this->getParameter('mailer_user'))
                ->setFrom($this->getParameter('mailer_user'))
                ->setBody(
                    $this->renderView(

                        'MyOrleansBundle::receptionForm.html.twig', [
                            'client' => $client
                        ]
                    ),
                    'text/html'
                );

            //Mail de confirmation
            $confirmation = new \Swift_Message('Confirmation de my-orleans.com');
            $confirmation
                ->setTo($client->getEmail())
                ->setFrom($this->getParameter('mailer_user'))
                ->setBody(
                    $this->renderView(
                        'MyOrleansBundle::confirmationForm.html.twig',
                        ['demande'=>$client->getMessage()]
                    ),
                    'text/html'
                );

            $mailer->send($confirmation);

            $mailer->send($message);

            $client->setDate(new \Datetime());

            $em->persist($client);
            $em->flush();
            return $this->redirectToRoute('location',['reference'=>$location->getReference()]);
        }

        return $this->render('MyOrleansBundle::location.html.twig', [
           'location'=>$location,
            'honoraires'=>$honoraires,
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView()
        ]);
    }
}