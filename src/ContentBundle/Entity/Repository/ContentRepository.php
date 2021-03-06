<?php

namespace ContentBundle\Entity\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ClickRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContentRepository extends \Doctrine\ORM\EntityRepository
{
    public function getNewestContents($page, $limit, $channels)
    {

        $query = $this->createQueryBuilder('e')
            ->select('e.id')
            ->addOrderBy('e.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        if ($channels) {
            $query
                ->join('e.channels', 'ch')
                ->where('ch.id in (:channels)')->setParameter('channels', $channels)
                ->orWhere('ch.name in (:channels)')->setParameter('channels', $channels);
        }

        $content = new Paginator($query, $fetchJoinCollection = true);

        $dql = "
            SELECT c, partial ch.{id, name}
            FROM ContentBundle:Content c
            JOIN c.channels ch
            where c.id in (:content)
            ORDER BY c.createdAt DESC
        ";

        $resultQuery = $this->getEntityManager()->createQuery($dql);
        $resultQuery->setParameter('content', $content->getQuery()->getResult());
        $resultQuery = $resultQuery->getArrayResult();


        return [
            'content' => $resultQuery,
            'total' => count($resultQuery),
        ];
    }

}
