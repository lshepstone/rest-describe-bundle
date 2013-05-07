<?php

namespace Sheppers\RestDescribeBundle\Processor;

use Sheppers\RestDescribeBundle\Processor\AbstractProcessor;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Doctrine\ORM\EntityManager;
use Sheppers\RestDescribeBundle\Annotation\Describe;
use Symfony\Component\Routing\Route;
use Sheppers\RestDescribeBundle\Entity\Operation;
use Sheppers\RestDescribeBundle\Entity\Parameter;

class DescribeRequestProcessor extends AbstractProcessor
{
    /**
     * Annotation reader.
     *
     * @var \Doctrine\Common\Annotations\Reader
     */
    protected $annotationReader;

    /**
     * Array of route parameters to exclude.
     *
     * @var array
     */
    protected $exludedRouteParameters = array(
        'compiler_class'
    );

    /**
     * @param EntityManager $entityManager
     * @param AnnotationReader $annotationReader
     */
    public function __construct(EntityManager $entityManager, AnnotationReader $annotationReader)
    {
        $this->entityManager = $entityManager;
        $this->annotationReader = $annotationReader;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($annotation, $entity)
    {
        return $annotation instanceof Describe\Request && $entity instanceOf Operation;
    }

    /**
     * {@inheritDoc}
     */
    public function process($annotation, $operation, array $meta = null)
    {
        $parameters = array();

        $parameters = $this->mergeParameterArrays($parameters, $this->getOptionalRouteParameters($meta['route']));
        $parameters = $this->mergeParameterArrays($parameters, $this->getRequiredRouteParameters($meta['route']));

        if ($model = $annotation->getModel()) {
            $parameters = $this->mergeParameterArrays($this->getParametersFromModel($model), $parameters);
        }

        if (is_array($annotation->getParameters())) {
            $parameters = $this->mergeParameterArrays($parameters, $annotation->getParameters());
        }

        $this->persistParameters($parameters, $operation);
    }

    /**
     * Gets parameters array from a given model class that has it's properties described with Describe\Parameter.
     *
     * @param string $model Model class that models the parameters.
     *
     * @return array
     */
    protected function getParametersFromModel($model)
    {
        $parameters = array();

        $reflectionClass = new \ReflectionClass($model);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $annotation = $this->annotationReader->getPropertyAnnotation(
                $reflectionProperty, '\Sheppers\RestDescribeBundle\Annotation\Describe\Parameter');
            if (null !== $annotation) {
                $parameters[$reflectionProperty->getName()] = array(
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

        return $parameters;
    }

    /**
     * Merge one parameters array into another, recursively.
     *
     * @param array $target Target array to merge into from the source
     * @param array $source Source array to be merged into the target
     *
     * @return array
     */
    protected function mergeParameterArrays(array $target, array $source)
    {
        foreach ($source as $key => $value) {
            if (array_key_exists($key, $target)) {
                if (is_array($value)) {
                    $value = $this->mergeParameterArrays($target[$key], $value);
                }
            }

            $target[$key] = $value;
        }

         return $target;
    }

    /**
     * Gets optional parameters from a route.
     *
     * @param Route $route
     *
     * @return array
     */
    protected function getOptionalRouteParameters(Route $route)
    {
        $parameters = array();

        $filtered = $this->filterExcludedRouteParameters($route->getDefaults());
        foreach ($filtered as $name => $default) {
            $parameters[$name] = array(
                'required' => false,
                'default' => $default
            );
        }

        return $parameters;
    }

    /**
     * Gets required parameters from a route.
     *
     * @param Route $route
     *
     * @return array
     */
    protected function getRequiredRouteParameters(Route $route)
    {
        $parameters = array();

        $filtered = $this->filterExcludedRouteParameters($route->getRequirements());
        foreach ($filtered as $name => $format) {
            $parameters[$name] = array(
                'required' => true,
                'format' => $format,

            );
        }

        return $parameters;
    }

    /**
     * Filters excluded route parameters from a given parameters array.
     *
     * @param $parameters
     *
     * @return array
     */
    protected function filterExcludedRouteParameters($parameters)
    {
        $filtered = array();

        foreach ($parameters as $name => $value) {
            if (0 === preg_match('/^_/', $name)) {
                if (false === in_array($name, $this->exludedRouteParameters)) {
                    $filtered[$name] = $value;
                }
            }
        }

        return $filtered;
    }

    /**
     * Persist an array of parameters to the data store.
     *
     * @param array $parameters
     * @param Operation $operation
     */
    protected function persistParameters(array $parameters, Operation $operation)
    {
        foreach ($parameters as $name => $parameter) {
            $entity = new Parameter($name, $operation);

            isset($parameter['name']) && $entity->setName($parameter['name']);
            isset($parameter['note']) && $entity->setNote($parameter['note']);
            isset($parameter['location']) && $entity->setLocation($parameter['location']);
            isset($parameter['type']) && $entity->setType($parameter['type']);
            isset($parameter['sample']) && $entity->setSample($parameter['sample']);
            isset($parameter['required']) && $entity->setRequired($parameter['required']);
            isset($parameter['default']) && $entity->setDefault($parameter['default']);
            isset($parameter['sample']) && $entity->setSample($parameter['sample']);
            isset($parameter['format']) && $entity->setFormat($parameter['format']);

            $this->entityManager->persist($entity);
        }
    }
}
