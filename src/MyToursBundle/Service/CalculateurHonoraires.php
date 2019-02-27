<?php
/**
 * Created by PhpStorm.
 * User: fabiendurand
 * Date: 08/01/2018
 * Time: 09:31
 */

namespace MyToursBundle\Service;



class CalculateurHonoraires
{
    public function calculHonoraires($surface)
    {
        if ($surface < 50) {
            $honoraire = $surface * 8;
        } elseif ($surface >= 50 && $surface < 70) {
            $honoraire = $surface * 7;
        } else {
            $honoraire = $surface * 6;
        }

        $etatLieux = $surface * 3;

        $honoraires = $honoraire + $etatLieux;

        return $honoraires;
    }

    public function calculHonoraireEtat($surface)
    {
        $etatLieux = $surface * 3;

        return $etatLieux;
    }
}