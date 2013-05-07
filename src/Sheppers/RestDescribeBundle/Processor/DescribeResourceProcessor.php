<?php

namespace Sheppers\RestDescribeBundle\Processor;

use Sheppers\RestDescribeBundle\Processor\AbstractProcessor;
use Sheppers\RestDescribeBundle\Annotation\Describe;
use Symfony\Component\Routing\Route;

class DescribeResourceProcessor extends AbstractProcessor
{
    protected $entityManager;

    /**
     * {@inheritDoc}
     */
    public function supports($annotation, $entity)
    {
        return $annotation instanceOf Describe\Resource;
    }

    /**
     * {@inheritDoc}
     */
    public function process($annotation, $resource, array $meta = null)
    {
        $resource
            ->setName($annotation->getName())
            ->setClass($meta['class'])
        ;
    }
}
