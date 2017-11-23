<?php
/**
 * Created by PhpStorm.
 * User: wilder8
 * Date: 02/07/17
 * Time: 14:48
 */

namespace MyOrleansBundle\Controller\front;


use MyOrleansBundle\Entity\Article;

use MyOrleansBundle\Entity\CategoriePresta;
use MyOrleansBundle\Entity\Flat;
use MyOrleansBundle\Entity\Media;

use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Collaborateur;
use MyOrleansBundle\Entity\Evenement;

use MyOrleansBundle\Entity\Pack;
use MyOrleansBundle\Entity\Service;
use MyOrleansBundle\Entity\Temoignage;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Entity\TypeLogement;
use MyOrleansBundle\Entity\TypePresta;
use MyOrleansBundle\Entity\Ville;
use MyOrleansBundle\Form\SimpleSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class FlatController extends Controller
{
    /**
     * @Route("/residence-{slug}/{reference}", name="appartement")
     * @ParamConverter("appartement", class="MyOrleansBundle:Flat", options={"reference" = "reference"})
     * @ParamConverter("residence", class="MyOrleansBundle:Residence", options={"slug" = "slug"})
     */
    public function flat(Flat $flat, SessionInterface $session, Request $request)
    {
        $client = new  Client();
        $parcours = null;
        if ($session->has('parcours')) {
            $parcours = $session->get('parcours');
        }



        $em = $this->getDoctrine()->getManager();
        $residence = $flat->getResidence();

        $medias = $flat->getMedias();
        $mediaDefine = [];
        foreach ($medias as $media) {
            if ($media->getTypeMedia()->getNom() == 'image') {
                $mediaDefine['image'] = $media;
            }elseif ($media->getTypeMedia()->getNom() == 'plan') {
                $mediaDefine['plan'] = $media;
            }
        }
        // Formulaire de contact

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

            $mailer->send($message);

            $em->persist($client);
            $em->flush();
            return $this->redirectToRoute('appartement',['id'=>$flat->getId()]);
        }



            return $this->render('MyOrleansBundle::appartement.html.twig',[
                'flat'=>$flat,
                'parcours'=>$parcours,
                'residence'=>$residence,
                'media' => $mediaDefine,
                'telephone_number' => $telephoneNumber,
                'form' => $formulaire->createView()
            ]);
        }

}