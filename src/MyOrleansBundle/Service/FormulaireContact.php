<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 19/01/2018
 * Time: 10:02
 */

namespace MyOrleansBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FormulaireContact
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function formulaireContact($client)
    {
        $mailer = $this->container->get('mailer');

        //Mail de reception
        $message = new \Swift_Message('Nouveau message de my-orleans.com');
        $message
            ->setTo($this->container->getParameter('mailer_user'))
            ->setFrom($this->container->getParameter('mailer_user'))
            ->setBody(
                $this->container->get('templating')->render(

                    'MyOrleansBundle::receptionForm.html.twig',
                    array('client' => $client)
                ),
                'text/html'
            );


        //Mail de confirmation
        $confirmation = new \Swift_Message('Confirmation de my-orleans.com');
        $confirmation
            ->setTo($client->getEmail())
            ->setFrom($this->container->getParameter('mailer_user'))
            ->setBody(
                $this->container->get('templating')->render(
                    'MyOrleansBundle::confirmationForm.html.twig',
                    ['demande'=>$client->getMessage()]
                ),
                'text/html'
            );

        $mailer->send($confirmation);

        $mailer->send($message);

    }

}