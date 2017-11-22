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
     * @Route("/savoir-faire", name="expertise")
     */

    public function expertiseAction(SessionInterface $session, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $services = $em->getRepository(Service::class)->findBy([], ['tri'=>'ASC']);
        $telephoneNumber = $this->getParameter('telephone_number');
        $packs = $em->getRepository(Pack::class)->findBy([], ['tri'=>'ASC']);

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

            $mailer->send($message);



            $em->persist($client);
            $em->flush();

            $this->addFlash('success', 'votre message a bien été envoyé');



            return $this->redirectToRoute('epertise');
        }



        return $this->render('MyOrleansBundle::expertise.html.twig',  [
            'services' => $services,
            'packs' => $packs,
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView()
        ]);
    }

}


