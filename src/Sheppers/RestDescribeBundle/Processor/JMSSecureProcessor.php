<?php

namespace Sheppers\RestDescribeBundle\Processor;

use Sheppers\RestDescribeBundle\Processor\AbstractProcessor;
use Sheppers\RestDescribeBundle\Annotation\Describe;
use Symfony\Component\Routing\Route;
use Sheppers\RestDescribeBundle\Entity\SecureInterface;
use JMS\SecurityExtraBundle\Annotation\Secure;

class JMSSecureProcessor extends AbstractProcessor
{
    /**
     * {@inheritDoc}
     */
    public function supports($annotation, $entity)
    {
        return $annotation instanceOf Secure && $entity instanceOf SecureInterface;
    }

    /**
     * {@inheritDoc}
     */
    public function process($annotation, $operation, array $meta = null)
    {
        $operation->setRoles($annotation->roles);
    }
}
