<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 11/07/17
 * Time: 09:05
 */

namespace MyToursBundle\Service;


use MyToursBundle\Entity\Residence;

class Geoloc
{
    /**
     * Permet de mettre à jour les coord GPS (lat et long) de la résidence passée en params.
     *
     * @param Residence $residence
     * @param $googleApiKey
     */
    public function updateCoord(Residence $residence, $googleApiKey)
    {
        $query = sprintf('https://maps.googleapis.com/maps/api/geocode/json?address=%s+%s+%s&key=%s',
                            urlencode($residence->getAdresse()),
                            urlencode($residence->getCodePostal()),
                            urlencode($residence->getVille()->getNom()),
                            $googleApiKey);

        if (0 == ini_get('allow_url_fopen')) {
            throw new \RuntimeException("Erreur configuration projet.");
        }

        if (false == $jsonData = file_get_contents($query)) {
            throw new \RuntimeException("Impossible de joindre l'api de geolocalisation");
        }

        $arrayData = json_decode($jsonData, true);

        // Si trop de resultats on ne peut pas choisir...
        if (count($arrayData['results']) > 1 || 0 === count($arrayData['results'])) {
            throw new \RuntimeException("L'adresse n'est pas assez précise, il est impossible de déterminer les coordonnées GPS.");
        }

        $residence->setLatitude($arrayData['results'][0]['geometry']['location']['lat']);
        $residence->setLongitude($arrayData['results'][0]['geometry']['location']['lng']);
    }
}
