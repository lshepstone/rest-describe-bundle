<?php

namespace Sheppers\RestDescribeBundle\Processor;

use Sheppers\RestDescribeBundle\Processor\AbstractProcessor;
use Sheppers\RestDescribeBundle\Annotation\Describe;
use Symfony\Component\Routing\Route;
use Sheppers\RestDescribeBundle\Entity\Operation;

class DescribeOperationProcessor extends AbstractProcessor
{
    /**
     * {@inheritDoc}
     */
    public function supports($annotation, $entity)
    {
        return $annotation instanceOf Describe\Operation && $entity instanceOf Operation;
    }

    /**
     * {@inheritDoc}
     */
    public function process($annotation, $operation, array $meta = null)
    {
        $operation
            ->setDescription($annotation->getDescription())
            ->setName($this->extractName($annotation, $meta))
            ->setScope($this->extractScope($annotation, $meta))
            ->setMethod($this->extractMethod($annotation, $meta))
            ->setUri($this->extractUri($annotation, $meta))
        ;

        return $this->getNestedAnnotations($annotation);
    }

    /**
     * {@inheritDoc}
     */
    public function getNestedAnnotations($annotation)
    {
        $annotations = array();

        if ($annotation->getRequest() instanceOf Describe\Request) {
            $annotations[] = $annotation->getRequest();
        }

        if ($annotation->getResponse() instanceOf Describe\Response) {
            $annotations[] = $annotation->getResponse();
        }

        return $annotations;
    }

    protected function extractName($annotation, $meta)
    {
        return $annotation->getName() ?: $meta['method'];
    }

    protected function extractMethod($annotation, $meta)
    {
        $methods = $meta['route']->getMethods();

        if (0 === count($methods)) {
            return 'GET';
        }

        return $methods[0];
    }

    protected function extractScope($annotation, $meta)
    {
        return $annotation->getScope();
    }

    protected function extractUri($annotation, $meta)
    {
        /** @var Route $route */
        $route = $meta['route'];

        return $route->getPath();
    }
}
