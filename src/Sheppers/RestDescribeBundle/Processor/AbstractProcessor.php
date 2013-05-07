<?php

namespace Sheppers\RestDescribeBundle\Processor;

use Sheppers\RestDescribeBundle\Processor\ProcessorInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Route;

abstract class AbstractProcessor implements ProcessorInterface
{
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    abstract public function supports($annotation, $entity);

    /**
     * {@inheritDoc}
     */
    abstract public function process($annotation, $entity, array $meta = null);
}
