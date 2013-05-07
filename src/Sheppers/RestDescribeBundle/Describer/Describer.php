<?php

namespace Sheppers\RestDescribeBundle\Describer;

use Symfony\Component\Routing\RouterInterface;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Doctrine\ORM\EntityManager;
use Sheppers\RestDescribeBundle\Processor\ProcessorInterface;
use Symfony\Component\Routing\Route;
use Sheppers\RestDescribeBundle\Entity\Resource;
use Sheppers\RestDescribeBundle\Entity\Operation;

class Describer
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    protected $annotationReader;

    /**
     * @var array<ProcessorInterface>
     */
    protected $processors = array();

    /**
     * @param RouterInterface $router
     * @param AnnotationReader $annotationReader
     * @param EntityManager $entityManager
     */
    public function __construct(RouterInterface $router, AnnotationReader $annotationReader, EntityManager $entityManager)
    {
        $this->router = $router;
        $this->annotationReader = $annotationReader;
        $this->entityManager = $entityManager;
    }

    /**
     * Processes all route controller and action annotations.
     */
    public function describe()
    {
        foreach ($this->getControllers() as $class => $actions) {
            $resource = $this->processControllerAnnotations($class);
            $resource = $this->persistResource($resource);
            if (count($actions)) {
                foreach ($actions as $method => $route) {
                    $operation = $this->processActionAnnotations($class, $method, $route, $resource);
                    if ($operation) {
                        $this->persistOperation($operation);
                    }
                }
            }
        }
    }

    /**
     * Adds an annotation processor to the describer.
     *
     * @param ProcessorInterface $processor
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * Gets an array of controllers with their methods and each method's route.
     *
     * @return array
     */
    protected function getControllers()
    {
        $controllers = array();

        /** @var $route Route */
        foreach ($this->router->getRouteCollection() as $route) {
            $controller = $route->getDefault('_controller');
            if (false !== strpos($controller, '::')) {
                list($class, $method) = explode('::', $controller);
                $controllers[$class][$method] = $route;
            }
        }

        return $controllers;
    }

    /**
     * Processes a given controller class for annotations.
     *
     * @param string $class
     *
     * @return Resource
     */
    protected function processControllerAnnotations($class)
    {
        $resource = new Resource('default');
        $annotations = $this->getClassAnnotations($class);
        while ($annotation = array_shift($annotations)) {
            if ($processor = $this->getSupportingProcessor($annotation, $resource)) {
                $nested = $processor->process($annotation, $resource, array('class' => $class));
                if (count($nested)) {
                    $annotations = array_merge($nested, $annotations);
                }
            }
        }

        return $resource;
    }

    /**
     * Gets all annotations from a given class name.
     *
     * @param string $class
     *
     * @return array
     */
    protected function getClassAnnotations($class)
    {
        $reflectionClass = new \ReflectionClass($class);

        return $this->annotationReader->getClassAnnotations($reflectionClass);
    }

    /**
     * Processes the annotations for a given controller action in context of a given route and Resource entity,
     * and returns an Operation entity if any annotations were processed and null otherwise.
     *
     * @param string $class
     * @param string $method
     * @param Route $route
     * @param Resource $resource
     *
     * @return null|Operation
     */
    protected function processActionAnnotations($class, $method, Route $route, Resource $resource)
    {
        $processed = false;
        $operation = new Operation('default', $resource);
        $annotations = $this->getMethodAnnotations($class, $method);
        while ($annotation = array_shift($annotations)) {
            if ($processor = $this->getSupportingProcessor($annotation, $operation)) {
                $nested = $processor->process($annotation, $operation,
                    array('route' => $route, 'class' => $class, 'method' => $method));
                if (count($nested)) {
                    $annotations = array_merge($nested, $annotations);
                }

                $processed = true;
            }
        }

        return $processed ? $operation : null;
    }

    /**
     * Gets all annotations from a given class and method name.
     *
     * @param string $class
     * @param string $method
     *
     * @return array
     */
    protected function getMethodAnnotations($class, $method)
    {
        $reflectionMethod = new \ReflectionMethod($class, $method);

        return $this->annotationReader->getMethodAnnotations($reflectionMethod);
    }

    /**
     * Gets the first processor capable of processing the given annotation in context of a given entity.
     *
     * @param $annotation
     * @param $entity
     *
     * @return bool|ProcessorInterface
     */
    protected function getSupportingProcessor($annotation, $entity)
    {
        /** @var $processor ProcessorInterface */
        foreach ($this->processors as $processor) {
            if ($processor->supports($annotation, $entity)) {
                return $processor;
            }
        }

        return false;
    }

    /**
     * Persists a given Resource entity to a data store.
     *
     * @param Resource $resource
     *
     * @return Resource
     */
    protected function persistResource(Resource $resource)
    {
        $existingResource = $this->entityManager
            ->getRepository('SheppersRestDescribeBundle:Resource')
            ->findOneBy(array('name' => $resource->getName()));

        if (null === $existingResource) {
            $this->entityManager->persist($resource);
        } else {
            $resource->setId($existingResource->getId());
            $resource = $this->entityManager->merge($resource);
        }

        $this->entityManager->flush();

        return $resource;
    }

    /**
     * Persists a given Operation entity to a data store.
     *
     * @param Operation $operation
     *
     * @return Operation
     */
    protected function persistOperation(Operation $operation)
    {
        $existingOperation = $this->entityManager
            ->getRepository('SheppersRestDescribeBundle:Operation')
            ->findOneBy(array(
                'name' => $operation->getName(),
                'resource' => $operation->getResource()->getId()
            ));

        if (null === $existingOperation) {
            $this->entityManager->persist($operation);
        } else {
            $operation->setId($existingOperation->getId());
            $operation = $this->entityManager->merge($operation);
        }

        $this->entityManager->flush();

        return $operation;
    }
}
