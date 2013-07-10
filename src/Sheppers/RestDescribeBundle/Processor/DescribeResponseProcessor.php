<?php

namespace Sheppers\RestDescribeBundle\Processor;

use Sheppers\RestDescribeBundle\Processor\AbstractProcessor;
use Sheppers\RestDescribeBundle\Annotation\Describe;
use Sheppers\RestDescribeBundle\Entity\Operation;
use Sheppers\RestDescribeBundle\Entity\Response;

class DescribeResponseProcessor extends AbstractProcessor
{
    /**
     * {@inheritDoc}
     */
    public function supports($annotation, $entity)
    {
        return $annotation instanceOf Describe\Response && $entity instanceOf Operation;
    }

    /**
     * {@inheritDoc}
     */
    public function process($annotation, $entity, array $meta = null)
    {
        if (null !== ($codes = $annotation->getCodes())) {
            $this->persistCodes($codes, $entity);
        }

        if (null !== ($isEmpty = $annotation->getIsEmpty())) {
            $entity->setIsResponseEmpty($isEmpty);
        }
    }

    /**
     * Persists an array of response codes to the data store.
     *
     * @param array $codes
     * @param Operation $operation
     */
    protected function persistCodes(array $codes, Operation $operation)
    {
        foreach ($codes as $code => $message) {
            $entity = new Response($code, $operation);
            $entity->setMessage($message);

            $this->entityManager->persist($entity);
        }
    }
}
