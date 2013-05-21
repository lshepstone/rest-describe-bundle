<?php

namespace Sheppers\RestDescribeBundle\Processor;

use Sheppers\RestDescribeBundle\Processor\AbstractProcessor;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Doctrine\ORM\EntityManager;
use Sheppers\RestDescribeBundle\Annotation\Describe;
use Sheppers\RestDescribeBundle\Entity\Resource;

class DescribeResourceProcessor extends AbstractProcessor
{
    /**
     * @param EntityManager $entityManager
     * @param AnnotationReader $annotationReader
     */
    public function __construct(EntityManager $entityManager, AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
        parent::__construct($entityManager);
    }

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

        if ($model = $annotation->getModel()) {
            $properties = $this->getParametersFromModel($model);
            $this->persistProperties($properties, $resource);
        }
    }

    /**
     * Gets properties array from a given model class that has it's properties described with Describe\Property.
     *
     * @param string $model Model class that models the properties.
     *
     * @return array
     */
    protected function getParametersFromModel($model)
    {
        $properties = array();

        $reflectionClass = new \ReflectionClass($model);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $annotation = $this->annotationReader->getPropertyAnnotation(
                $reflectionProperty, '\Sheppers\RestDescribeBundle\Annotation\Describe\Property');

            var_dump($annotation);

            if (null !== $annotation) {
                $properties[$reflectionProperty->getName()] = array(
                    'type' => $annotation->getType(),
                    'note' => $annotation->getNote(),
                    'location' => $annotation->getLocation(),
                    'required' => $annotation->isRequired(),
                    'sample' => $annotation->getSample(),
                    'format' => $annotation->getFormat(),
                    'default' => $annotation->getDefault()
                );
            }
        }

        return $properties;
    }

    /**
     * Persists an array of properties to the data store.
     *
     * @param array $properties
     * @param Resource $resource
     */
    protected function persistProperties(array $properties, Resource $resource)
    {
        var_dump($properties);
        exit(__METHOD__);

        foreach ($properties as $name => $property) {
            $entity = new Property($name, $property);

            isset($property['name']) && $entity->setName($property['name']);
            isset($property['note']) && $entity->setNote($property['note']);
            isset($property['type']) && $entity->setType($property['type']);
            isset($property['sample']) && $entity->setSample($property['sample']);
            isset($property['default']) && $entity->setDefault($property['default']);
            isset($property['sample']) && $entity->setSample($property['sample']);
            isset($property['format']) && $entity->setFormat($property['format']);

            $this->entityManager->persist($entity);
        }
    }
}
