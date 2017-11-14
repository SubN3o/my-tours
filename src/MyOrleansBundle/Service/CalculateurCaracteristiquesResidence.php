<?php
/**
 * Created by PhpStorm.
 * User: wilder3
 * Date: 16/06/17
 * Time: 20:28
 */

namespace MyOrleansBundle\Service;


use MyOrleansBundle\Entity\Residence;

class CalculateurCaracteristiquesResidence
{
    public function calculPrix(Residence $residence)
    {
        $flats = $residence->getFlats();

        $prix = [];

        foreach ($flats as $flat) {
            if ($flat->getStatut() == true) {
                $prix[] = $flat->getPrix();
            }
        }

        if (!$prix) {
            $prix[] = 0;
        }
        $prixMin = min($prix);

        return $prixMin;
    }

    public function calculFlatDispo(Residence $residence)
    {
        $freeFlats = 0;

        $flats = $residence->getFlats();
        foreach ($flats as $flat) {
            if ($flat->getStatut() == 1) {
                $freeFlats++;
            }
        }

        return $freeFlats;
    }

    public function calculSizes(Residence $residence)
    {
        $flats = $residence->getFlats();

        $sizes = [];
        foreach ($flats as $flat) {
            if ($flat->getStatut() == true) {
                $sizes[] = $flat->getTypeLogement()->getNom();
            }
        }

        if (!$sizes) {
            $sizes[0] = $sizes[1] = 0;
        }

        sort($sizes);

        $minMaxType[0] = $sizes[0];

        if ((count($sizes) - 1) <= 1) {
            $minMaxType[1] = $sizes[0];
        } else {
            $minMaxType[1] = $sizes[count($sizes) - 1];
        }

        return $minMaxType;
    }
}