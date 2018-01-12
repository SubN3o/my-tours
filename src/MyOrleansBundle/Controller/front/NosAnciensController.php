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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NosAnciensController extends Controller
{
    /**
     * @Route("/anciens", name="nosanciens")
     */
    public function locationAction(Request $request)
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

            $this->addFlash('success', 'votre message a bien été envoyé');

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