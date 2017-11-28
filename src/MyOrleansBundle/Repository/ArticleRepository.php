<?php

namespace MyOrleansBundle\Repository;

use MyOrleansBundle\Entity\Article;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{


    public function findOneActu()
    {
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1);
        return $qb->getQuery()->getResult();
    }


    public function articleByTag($data)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->where('a.titre LIKE :keyword ')
            ->orWhere('ta.nom LIKE :keyword')
            ->orWhere('ty.nom LIKE :keyword')
            ->orWhere('a.texte LIKE :keyword')
            ->setParameter('keyword', '%'.$data['keyword'].'%')
            ->join('a.tags','ta')
            ->join('a.typeArticle','ty');

        return $qb->getQuery()->getResult();
    }

    public function articleByType($type, $nbArticles)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select('a')
            ->join('a.typeArticle', 't')
            ->addSelect('t')
            ->where('t.nom = :type')
            ->setParameter('type', $type)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults($nbArticles);

        return $qb->getQuery()->getResult();
    }

    public function findLatestArticles($limit = Article::NUM_ARTICLES)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

}
