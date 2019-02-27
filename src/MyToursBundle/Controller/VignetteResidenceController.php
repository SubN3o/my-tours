<?php
/**
 * Created by PhpStorm.
 * User: wilder3
 * Date: 20/06/17
 * Time: 11:39
 */

namespace MyToursBundle\Controller;


use MyToursBundle\Entity\Residence;
use MyToursBundle\Service\CalculateurCaracteristiquesResidence;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class VignetteResidenceController extends Controller
{

    /**
     * @param $id
     * @param CalculateurCaracteristiquesResidence $calculateur
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/vignette-residence/{id}", name="vignette-residence")
     */
    public function affichageResidenceAction(Residence $residence, CalculateurCaracteristiquesResidence $calculateur)
    {
        //recuperation des caracteristiques de chaque residence
        $prixMin = $calculateur->calculPrix($residence);
        $flatsDispo = $calculateur->calculFlatDispo($residence);
        $typeMinMax = $calculateur->calculSizes($residence);

        // Fin recup caracteristiques

        return $this->render('MyToursBundle::affichage_residence.html.twig', [
                'residence' => $residence,
                'prixMin' => $prixMin,
                'flatsDispo' => $flatsDispo,
                'typeMin' => $typeMinMax[0],
                'typeMax' => $typeMinMax[1]
        ]);

    }

    /**
     * @param $id
     * @param CalculateurCaracteristiquesResidence $calculateur
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/vignette-residence-home/{id}", name="vignette-residence-home")
     */
    public function affichageResidenceHomeAction(Residence $residence, CalculateurCaracteristiquesResidence $calculateur)
    {
        //recuperation des caracteristiques de chaque residence
        $prixMin = $calculateur->calculPrix($residence);
        $flatsDispo = $calculateur->calculFlatDispo($residence);
        $typeMinMax = $calculateur->calculSizes($residence);

        // Fin recup caracteristiques

        return $this->render('MyToursBundle::home_affichage_residence.html.twig', [
            'residence' => $residence,
            'prixMin' => $prixMin,
            'flatsDispo' => $flatsDispo,
            'typeMin' => $typeMinMax[0],
            'typeMax' => $typeMinMax[1]
        ]);

    }


    /**
     * @param $id
     * @param CalculateurCaracteristiquesResidence $calculateur
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/vignette-residence-simplifie/{id}", name="vignette-residence-simplifie")
     */
    public function affichageResidenceSimplifieAction(Residence $residence, CalculateurCaracteristiquesResidence $calculateur)
    {
        //recuperation des caracteristiques de chaque residence
        $prixMin = $calculateur->calculPrix($residence);

        // Fin recup caracteristiques

        return $this->render('MyToursBundle:blog:blog_affichage_residence.html.twig', [
            'residence' => $residence,
            'prixMin' => $prixMin,
        ]);

    }

}