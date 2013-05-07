<?php

namespace Sheppers\RestDescribeBundle\Repository;

use Doctrine\ORM\EntityRepository;

class OperationRepository extends EntityRepository
{
    /**
     * Gets all Operations for a given resource.
     *
     * @param string $resource Resource name
     *
     * @return array
     */
    public function findForResource($resource)
    {
        return $this->getEntityManager('describe')->createQueryBuilder()
            ->select('o')->from('SheppersRestDescribeBundle:Operation', 'o')
            ->join('o.resource', 'r')
            ->where('r.name = :resource')
            ->setParameter('resource', $resource)
            ->getQuery()
            ->getResult();
    }

    /**
     * Gets an Operation by name for a given resource.
     *
     * @param string $resource Resource name
     * @param string $name Operation name
     *
     * @return array
     */
    public function findOneByNameForResource($resource, $name)
    {
        return $this->getEntityManager('describe')->createQueryBuilder()
            ->select('o')->from('SheppersRestDescribeBundle:Operation', 'o')
            ->join('o.resource', 'r')
            ->where('r.name = :resource')
            ->andWhere('o.name = :operation')
            ->setParameter('resource', $resource)
            ->setParameter('operation', $name)
            ->getQuery()
            ->getSingleResult();
    }
}
