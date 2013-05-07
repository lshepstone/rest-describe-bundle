<?php

namespace Sheppers\RestDescribeBundle\Processor;

use Symfony\Component\Routing\Route;

interface ProcessorInterface
{
    /**
     * Determines if the given annotation is supported for processing.
     *
     * @param $annotation
     * @param $entity
     *
     * @return boolean
     */
    public function supports($annotation, $entity);

    /**
     * Processes the given annotation in context of a given entity and route (if available) and returns any nested
     * annotations to be processed next. This method must always return an array.
     *
     * @param $annotation
     * @param $entity
     * @param array $meta
     *
     * @return array
     */
    public function process($annotation, $entity, array $meta = null);
}
