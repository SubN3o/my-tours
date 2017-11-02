<?php
/**
 * Created by PhpStorm.
 * User: wilder3
 * Date: 12/06/17
 * Time: 14:57
 */

namespace MyOrleansBundle\Controller\front;

use MyOrleansBundle\Entity\Article;
use MyOrleansBundle\Entity\Chiffre;
use MyOrleansBundle\Entity\Client;
use MyOrleansBundle\Entity\Quartier;
use MyOrleansBundle\Entity\Ville;
use MyOrleansBundle\Service\AutocompleteGenerator;
use MyOrleansBundle\Service\CalculateurCaracteristiquesResidence;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use MyOrleansBundle\Entity\Residence;
use MyOrleansBundle\Form\SimpleSearchType;
use MyOrleansBundle\Form\CompleteSearchType;
use MyOrleansBundle\Repository\ArticleRepository;
use MyOrleansBundle\Service\MyOrleans_Twig_Extension;


class NosResidencesController extends Controller
{
    /**
     * @Route("/nos_residences", name="nosresidences")
     */
    public function nosResidencesAction(Request $request, SessionInterface $session)
    {

        // Definition des contenus associes par defaut

        $residencesSuggerees = '';
        $resultatRecherche = 0;

        // Generation du manager
        $em = $this->getDoctrine()->getManager();

        $chiffres = $em->getRepository(Chiffre::class)->findBy([], ['tri'=>'ASC'], 3);

        // Recuperation de la liste des villes et des quartiers dans lesqulles se trouvent les residences
        $villes = $em->getRepository(Ville::class)->findAll();
        $quartiers = $em->getRepository(Quartier::class)->findAll();

        // Generation du moteur de recherche simplifie
        $simpleSearch = $this->createForm('MyOrleansBundle\Form\SimpleSearchType', null, ['action' => $this->generateUrl('nosresidences')]);
        $simpleSearch->handleRequest($request);

        // initialisation des variables ville et type a 0 si le formulaire simpleSearch n'est pas soumis
        $selectedVille = $selectedType = '';

        // affectation des valeurs ville et type si le form simpleSearch est valide
        if ($simpleSearch->isSubmitted() && $simpleSearch->isValid()) {

            // Prise en compte des filtres du moteur de recherche
            $data = $simpleSearch->getData();
            $selectedVille = $data['ville'];
            $selectedType = $data['type'];
            $residences = $em -> getRepository(Residence::class)->simpleSearch($selectedVille, $selectedType);

            if (!empty($residences)){
                $resultatRecherche = 2;
            }

            // Recuperation de toutes les residences pour affichage si la ville selectionnee n'existe pas
            if(empty($residences)) {
                $residences = $em -> getRepository(Residence::class)->findBy([], ['tri'=>'ASC']);
                $resultatRecherche = 1;
            }

            // recherche des residences à exclure de la liste des residences à suggerer
            if (!empty($residences)) {
                foreach ($residences as $residence) {
                    $idResidences[] = $residence->getId();
                }
            }

            if ($selectedVille != null || $selectedType != null) {
                $residencesSuggerees = $em->getRepository(Residence::class)->suggestResidence($idResidences);
            }

        }

        // Generation du moteur de recherche complet avec les valeurs ville et type definies ou non dans simpleSearch
        $completeSearch = $this->createForm('MyOrleansBundle\Form\CompleteSearchType', ['ville'=>$selectedVille, 'type'=>$selectedType], ['action' => $this->generateUrl('nosresidences-search')]);
        $completeSearch->handleRequest($request);

        // Recuperation de toutes les residences pour affichage si la ville selectionnee n'existe pas
        if(empty($residences)) {
            $residences = $em -> getRepository(Residence::class)->findBy([], ['tri'=>'ASC']);
        }

        $telephoneNumber = $this->getParameter('telephone_number');

        // Formulaire de contact
        $client = new Client();
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
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

                        'MyOrleansBundle::receptionForm.html.twig',
                        array('client' => $client)
                    ),
                    'text/html'
                );

            $mailer->send($message);

            $em->persist($client);
            $em->flush();

            $this->addFlash('success', 'votre message a bien été envoyé');
            return $this->redirectToRoute('nosresidences');
        }

        return $this->render('MyOrleansBundle::nosResidences.html.twig', [
            'telephone_number' => $telephoneNumber,
            'form' => $formulaire->createView(),
            'chiffres' => $chiffres,
            'residencesSuggerees' => $residencesSuggerees,
            'residences' => $residences,
            'completeSearch' => $completeSearch->createView(),
            'villes' => $villes,
            'quartiers' => $quartiers,
            'resultatRecherche' => $resultatRecherche
        ]);

    }

    /**
     * @Route("/nos_residences/search", name="nosresidences-search")
     */
    public function completeSearchAction(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();

        $chiffres = $em->getRepository(Chiffre::class)->findBy([], ['tri'=>'ASC'], 3);

        $completeSearch = $this->createForm('MyOrleansBundle\Form\CompleteSearchType');
        $completeSearch->handleRequest($request);

        // Recuperation de la liste des villes et des quartiers dans lesqulles se trouvent les residences
        $villes = $em->getRepository(Ville::class)->findAll();
        $quartiers = $em->getRepository(Quartier::class)->findAll();

        // Traitement de la requete
        if ($completeSearch->isSubmitted()) {

            $resultatRecherche = 0;

            $data = $completeSearch->getData();


            if ($data['typeLogement']->isEmpty()){
                $data['typeLogement'] = null;
            }

            $residences = $em->getRepository(Residence::class)->completeSearch($data);

            if (!empty($residences)){
                $resultatRecherche = 2;
            }

            // Recuperation de toutes les residences pour affichage si la ville selectionnee n'existe pas
            if(empty($residences)) {
                $residences = $em->getRepository(Residence::class)->findBy([], ['tri'=>'ASC']);
                $resultatRecherche = 1;
            }


            // Fin contenu associe

            // recherche des residences à exclure de la liste des residences à suggerer
            if (!empty($residences)) {
                foreach ($residences as $residence) {
                    $idResidences[] = $residence->getId();
                }
            }

            // recherche des residences suggeres
            if ($data != null ) {
                $residencesSuggerees = $em -> getRepository(Residence::class)->suggestResidence($idResidences);
            }

        $telephoneNumber = $this->getParameter('telephone_number');

        // Formulaire de contact
        $client = new Client();
        $formulaire = $this->createForm('MyOrleansBundle\Form\FormulaireType', $client);
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

                        'MyOrleansBundle::receptionForm.html.twig',
                        array('client' => $client)
                    ),
                    'text/html'
                );

            $mailer->send($message);

            $em->persist($client);
            $em->flush();

            $this->addFlash('success', 'votre message a bien été envoyé');
            return $this->redirectToRoute('nosresidences');
        }


            return $this->render('MyOrleansBundle::nosResidences.html.twig',[
                'telephone_number' => $telephoneNumber,
                'form' => $formulaire->createView(),
                'chiffres' => $chiffres,
                'completeSearch' => $completeSearch->createView(),
                'residencesSuggerees' => $residencesSuggerees,
                'residences' => $residences,
                'villes' => $villes,
                'quartiers' => $quartiers,
                'resultatRecherche' => $resultatRecherche
            ]);

        }

    }

}