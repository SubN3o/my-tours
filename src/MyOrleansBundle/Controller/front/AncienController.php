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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AncienController extends Controller
{
    /**
     * @Route("immobilier-anciens/{reference}", name="ancien")
     * @ParamConverter("ancien", class="MyOrleansBundle:Ancien", options={"reference" = "reference"})
     */
    public function location(Ancien $ancien, Request $request)
    {
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