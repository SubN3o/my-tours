<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 15/01/2018
 * Time: 16:05
 */

namespace MyOrleansBundle\Controller\front;


use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Faq;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FaqController extends Controller
{
    /**
     * @Route("/faq", name="faq")
     */
    public function faqAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $faqs = $em->getRepository(Faq::class)->findBy([],['tri'=>'ASC']);

        //Formulaire de contact
        $telephoneNumber = $this->getParameter('telephone_number');
        $client = new Client();
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $mailer = $this->get('mailer');

            $message = new \Swift_Message('Nouveau message de my-orleans.com');
            $message
                ->setTo($this->getParameter('mailer_user'))
                ->setFrom($this->getParameter('mailer_user'))
                ->setBody(
                    $this->renderView(

                        'MyOrleansBundle::receptionForm.html.twig',
                        array('client' => $client)
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



            return $this->redirectToRoute('faq');
        }



        return $this->render('MyOrleansBundle::faq.html.twig',  [
            'faqs' => $faqs,
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView()
        ]);
    }
}