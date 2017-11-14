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



class NosServicesController extends Controller
{

    /**
     * @Route("/nos_services", name="nos_services")
     */

    public function nosServicesAction(SessionInterface $session, Request $request)
    {

        $client = new Client();
//        $parcours = null;
//        if ($session->has('parcours')) {
//            $parcours = $session->get('parcours');
//        }


        $em = $this->getDoctrine()->getManager();
        $services = $em->getRepository(Service::class)->findBy([], ['tri'=>'ASC']);
        $telephoneNumber = $this->getParameter('telephone_number');
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
//        $formulaire->get('sujet')->setData(Client::SUJET_SERVICES);
        $packs = $em->getRepository(Pack::class)->findBy([], ['tri'=>'ASC']);

        $temoignages = $em->getRepository(Temoignage::class)->findBy([], ['tri'=>'ASC'], 4);
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

            $mailer->send($message);



            $em->persist($client);
            $em->flush();

            $this->addFlash('success', 'votre message a bien été envoyé');



            return $this->redirectToRoute('nos_services');
        }



        return $this->render('MyOrleansBundle::nosServices.html.twig',  [
            'services' => $services,
            'packs' => $packs,
            'temoignages' => $temoignages,
            'telephone_number' => $telephoneNumber,
//            'parcours' => $parcours,
            'form' => $formulaire->createView()
        ]);
    }

}



