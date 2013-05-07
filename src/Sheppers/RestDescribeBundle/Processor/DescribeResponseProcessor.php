<?php

namespace Sheppers\RestDescribeBundle\Processor;

use Sheppers\RestDescribeBundle\Processor\AbstractProcessor;
use Sheppers\RestDescribeBundle\Annotation\Describe;
use Symfony\Component\Routing\Route;

class DescribeResponseProcessor extends AbstractProcessor
{
    public function supports($annotation, $entity)
    {
        return $annotation instanceOf Describe\Response;
    }

    public function process($annotation, $entity, array $meta = null)
    {

    }
}
