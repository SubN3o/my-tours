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

class NosLocationsController extends Controller
{
    /**
     * @Route("/locations", name="noslocations")
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

            return $this->redirectToRoute('noslocations');
        }

        return $this->render('MyOrleansBundle::nosLocations.html.twig', [
            'locations' => $locations,
            'telephone_number' =>$telephoneNumber,
            'form' => $formulaire->createView(),
            'locationFiltre' => $locationFiltre->createView(),

        ]);
    }
}