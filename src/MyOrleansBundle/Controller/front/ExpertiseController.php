<?php
/**
 * Created by PhpStorm.
 * User: jean-baptiste
 * Date: 15/06/17
 * Time: 10:48
 */

namespace MyOrleansBundle\Controller\front;

use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Form\FormulaireType;
use MyOrleansBundle\MyOrleansBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyOrleansBundle\Entity\Pack;
use MyOrleansBundle\Entity\Service;
use MyOrleansBundle\Entity\Temoignage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class ExpertiseController extends Controller
{

    /**
     * @Route("/nos-competences", name="expertise")
     */

    public function expertiseAction( Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        //récupération des services
        $services = $em->getRepository(Service::class)->findBy([], ['tri'=>'ASC']);

        //récupération des packs
        $packs = $em->getRepository(Pack::class)->findBy([], ['tri'=>'ASC']);

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

            $this->addFlash('success', 'votre message a bien été envoyé');



            return $this->redirectToRoute('expertise');
        }



        return $this->render('MyOrleansBundle::expertise.html.twig',  [
            'services' => $services,
            'packs' => $packs,
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView()
        ]);
    }

}



