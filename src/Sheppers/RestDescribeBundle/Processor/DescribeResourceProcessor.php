<?php

namespace Sheppers\RestDescribeBundle\Processor;

use Sheppers\RestDescribeBundle\Processor\AbstractProcessor;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Doctrine\ORM\EntityManager;
use Sheppers\RestDescribeBundle\Annotation\Describe;
use Sheppers\RestDescribeBundle\Entity\Resource;
use Sheppers\RestDescribeBundle\Entity\Property;

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
            ->setDescription($annotation->getDescription())
            ->setClass($meta['class'])
        ;

        if ($model = $annotation->getModel()) {
            $properties = $this->getPropertiesFromModel($model);
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
    protected function getPropertiesFromModel($model)
    {
        $properties = array();

        $reflectionClass = new \ReflectionClass($model);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $annotation = $this->annotationReader->getPropertyAnnotation(
                $reflectionProperty, '\Sheppers\RestDescribeBundle\Annotation\Describe\Property');
            if (null !== $annotation) {
                $name = $annotation->getName() ?: $reflectionProperty->getName();
                $properties[$name] = array(
                    'type' => $annotation->getType(),
                    'description' => $annotation->getDescription(),
                    'sample' => $annotation->getSample(),
                    'format' => $annotation->getFormat(),
                    'default' => $annotation->getDefault(),
                );

                if ($model = $annotation->getModel()) {
                    $properties[$name]['model'] = $model;
                    $properties[$name]['properties'] = $this->getPropertiesFromModel($model);
                }
            }
        }

        return $properties;
    }

    /**
     * Persists an array of properties to the data store.
     *
     * @param array $properties
     * @param Resource $resource
     * @param Property|null $parent
     */
    protected function persistProperties(array $properties, Resource $resource, Property $parent = null)
    {
        foreach ($properties as $name => $property) {
            $entity = new Property($name, $resource);
            if (null !== $parent) {
                $entity->setParent($parent);
            }

            isset($property['name']) && $entity->setName($property['name']);
            isset($property['description']) && $entity->setDescription($property['description']);
            isset($property['type']) && $entity->setType($property['type']);
            isset($property['model']) && $entity->setModel($property['model']);
            isset($property['format']) && $entity->setFormat($property['format']);
            isset($property['default']) && $entity->setDefault($property['default']);
            isset($property['sample']) && $entity->setSample($property['sample']);

            $this->entityManager->persist($entity);

            if (isset($property['properties']) && count($property['properties'])) {
                $this->persistProperties($property['properties'], $resource, $entity);
            }
        }
    }
}
