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
use MyOrleansBundle\Service\FormulaireContact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FaqController extends Controller
{
    /**
     * @Route("/faq", name="faq")
     */
    public function faqAction(Request $request, FormulaireContact $formulaireContact)
    {
        $em = $this->getDoctrine()->getManager();

        $faqs = $em->getRepository(Faq::class)->findBy([],['tri'=>'ASC']);

        //Formulaire de contact
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

            return $this->redirectToRoute('faq');
        }



        return $this->render('MyOrleansBundle::faq.html.twig',  [
            'faqs' => $faqs,
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView()
        ]);
    }
}