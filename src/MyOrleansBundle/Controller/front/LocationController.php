<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 08/01/2018
 * Time: 14:46
 */

namespace MyOrleansBundle\Controller\front;


use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Location;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LocationController extends Controller
{
    /**
     * @Route("/locations", name="locations")
     */
    public function locationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $locations = $em->getRepository(Location::class)->findBy([],['statut'=>'DESC']);

        $locationFiltre = $this->createForm('MyOrleansBundle\Form\FiltreLocationType');
        $locationFiltre->handleRequest($request);

        if ($locationFiltre->isSubmitted() && $locationFiltre->isValid()){

            $data = $locationFiltre->getData();

            $selectedFilter = $data['filter'];

            $locations = $em->getRepository(Location::class)->findByStatut(1,[$selectedFilter=>'ASC']);
        }

        // Formulaire de contact
        $telephoneNumber = $this->getParameter('telephone_number');

        $client = new Client();
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $mailer = $this->get('mailer');

            $message = new \Swift_Message('Nouveau message de my-orleans.com');
            $message
                ->setFrom($client->getEmail())
                ->setTo($this->getParameter('mailer_user'))


                ->setBody(
                    $this->renderView(

                        'MyOrleansBundle::receptionForm.html.twig',
                        array('client' => $client)
                    ),
                    'text/html'
                );

            $mailer->send($message);

            $client->setDate(new \Datetime());

            $em->persist($client);
            $em->flush();

            $this->addFlash('success', 'votre message a bien été envoyé');

            return $this->redirectToRoute('immo_pratique');
        }

        return $this->render('MyOrleansBundle::locations.html.twig', [
            'locations' => $locations,
            'telephone_number' =>$telephoneNumber,
            'form' => $formulaire->createView(),
            'locationFiltre' => $locationFiltre->createView(),

        ]);
    }
}