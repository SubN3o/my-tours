<?php

namespace MyOrleansBundle\Repository;

/**
 * ResidenceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResidenceRepository extends \Doctrine\ORM\EntityRepository
{
    public function simpleSearch($ville, $type)
    {
        $qb = $this->createQueryBuilder('r');

        if (!empty($ville)) {
            $qb->andWhere('v.nom LIKE :ville')
                ->setParameter('ville', '%'.$ville.'%')
                ->join('r.ville', 'v')
                ->orderBy('r.tri', 'ASC');
        }

        if (!empty($type)) {
            $qb->andWhere('t.nom = :type')
                ->setParameter('type', $type)
                ->join('r.flats', 'f')
                ->join('f.typeLogement', 't')
                ->orderBy('r.tri', 'ASC');
        }

        $qb->andWhere('flts.statut = true')
            ->join('r.flats', 'flts')
            ->orderBy('r.tri', 'ASC');

        return $qb->getQuery()->getResult();
    }

//    public function simpleSuggestedSearch($ids, $ville, $type)
//    {
//        $qb = $this->createQueryBuilder('r');
//
//        if (!empty($ville)) {
//            $qb->where('v.nom != :ville')
//                ->setParameter('ville', $ville)
//                ->join('r.ville', 'v')
//                ->orderBy('r.tri', 'ASC');
//        }
//
//        if (!empty($type)) {
//            $qb->andWhere('t.nom != :type')
//                ->setParameter('type', $type)
//                ->join('r.flats', 'f')
//                ->join('f.typeLogement', 't')
//                ->orderBy('r.tri', 'ASC');
//        }
//
//        if (!empty($ids)) {
//            foreach ($ids as $key => $id) {
//                $idParam = 'id'.$key;
//                $qb->andwhere('r.id != :'.$idParam)
//                    ->setParameter($idParam, $id)
//                    ->orderBy('r.tri', 'ASC');
//            }
//        }
//
//        $qb->andWhere('flts.statut = true')
//            ->join('r.flats', 'flts')
//            ->setMaxResults(4)
//            ->orderBy('r.tri', 'ASC');
//
//        return $qb->getQuery()->getResult();
//    }


    public function completeSearch($data)
    {
        $qb = $this->createQueryBuilder('r');

        if (!empty($data['ville'])) {
            $qb->andWhere('v.nom LIKE :ville')
                ->setParameter('ville', '%'.$data['ville'].'%')
                ->join('r.ville', 'v')
                ->orderBy('r.tri', 'ASC');
        }


        if (!empty($data['type'])) {
            $qb->andWhere('t.nom = :type')
                ->setParameter('type', $data['type'])
                ->join('r.flats', 'f')
                ->join('f.typeLogement', 't')
                ->orderBy('r.tri', 'ASC');
        }


        if (!empty($data['budgetMin'])) {
            $qb->andWhere('ft.prix >= :budgetMin')
                ->setParameter('budgetMin', $data['budgetMin'])
                ->join('r.flats', 'ft')
                ->orderBy('r.tri', 'ASC');
        }

        if (!empty($data['budgetMax'])) {
            $qb->andWhere('fts.prix <= :budgetMax ')
                ->setParameter('budgetMax', $data['budgetMax'])
                ->join('r.flats', 'fts')
                ->orderBy('r.tri', 'ASC');
        }

        $qb->andWhere('flts.statut = true')
            ->join('r.flats', 'flts')
            ->orderBy('r.tri', 'ASC');

        return $qb->getQuery()->getResult();
    }

//    public function completeSuggestedSearch($ids, $data)
//    {
//        $qb = $this->createQueryBuilder('r');
//
//        if (!empty($data['quartier'])) {
//            $qb->where('v.nom = :ville')
//                ->setParameter('ville', 'Orléans')
//                ->join('r.ville', 'v')
//                ->andWhere('q.nom != :quartier')
//                ->setParameter('quartier', $data['quartier'])
//                ->join('r.quartier', 'q')
//                ->orderBy('r.tri', 'ASC');
//        }
//
//        if (!empty($data['ville']) && empty($data['quartier'])) {
//            $qb->where('v.nom != :ville')
//                ->setParameter('ville', $data['ville'])
//                ->join('r.ville', 'v')
//                ->orderBy('r.tri', 'ASC');
//        }
//
//        if (!empty($data['type'])) {
//            $qb->andWhere('t.nom != :type')
//                ->setParameter('type', $data['type'])
//                ->join('r.flats', 'f')
//                ->join('f.typeLogement', 't')
//                ->orderBy('r.tri', 'ASC');
//        }
//
//        if (!empty($data['surfaceMin'])) {
//            $qb->andWhere('fl.surface != :surfaceMin')
//                ->setParameter('surfaceMin', $data['surfaceMin'])
//                ->join('r.flats', 'fl')
//                ->orderBy('r.tri', 'ASC');
//        }
//
//        if (!empty($data['surfaceMax'])) {
//            $qb->andWhere('fla.surface != :surfaceMax')
//                ->setParameter('surfaceMax', $data['surfaceMax'])
//                ->join('r.flats', 'fla')
//                ->orderBy('r.tri', 'ASC');
//        }
//
//        if (!empty($data['budgetMin'])) {
//            $qb->andWhere('ft.prix != :budgetMin')
//                ->setParameter('budgetMin', $data['budgetMin'])
//                ->join('r.flats', 'ft')
//                ->orderBy('r.tri', 'ASC');
//        }
//
//        if (!empty($data['budgetMax'])) {
//            $qb->andWhere('fts.prix != :budgetMax ')
//                ->setParameter('budgetMax', $data['budgetMax'])
//                ->join('r.flats', 'fts')
//                ->orderBy('r.tri', 'ASC');
//        }
//
//        if (!empty($ids)) {
//            foreach ($ids as $key => $id) {
//                $idParam = 'id' . $key;
//                $qb->andwhere('r.id != :' . $idParam)
//                    ->setParameter($idParam, $id)
//                    ->orderBy('r.tri', 'ASC');
//            }
//        }
//
//        $qb->andWhere('flts.statut = true')
//            ->join('r.flats', 'flts')
//            ->setMaxResults(4);
//
//        return $qb->getQuery()->getResult();
//    }

    public function suggestResidence($idResidence)
    {
        foreach ($idResidence as $key => $id) {
            $qb = $this->createQueryBuilder('r')
                ->where('r.id != :idResidence')
                ->setParameter('idResidence', $id)
                ->andWhere('r.favoris = true')
                ->orderBy('r.tri', 'ASC')
                ->setMaxResults(4);

            return $qb->getQuery()->getResult();
        }


    }

    public function findAllLimit()
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.id','DESC')
            ->setMaxResults(6);
        return $qb->getQuery()->getResult();

    }

    public function findOneFav()
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.favoris = 1')
            ->orderBy('r.tri', 'ASC')
            ->setMaxResults(1);
         return $qb->getQuery()->getResult();
    }

    public function findTwoFav()
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.favoris = 1')
            ->orderBy('r.tri', 'ASC')
            ->setMaxResults(2);
        return $qb->getQuery()->getResult();
    }
}

