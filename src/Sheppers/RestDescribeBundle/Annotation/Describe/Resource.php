<?php

namespace Sheppers\RestDescribeBundle\Annotation\Describe;

/**
 * @Annotation
 */
class Resource
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var array
     */
    protected $relationships;

    /**
     * @param $options
     */
    public function __construct($options)
    {
        isset($options['name']) && $this->name = (string) $options['name'];
        isset($options['model']) && $this->model = (string) $options['model'];
        isset($options['relationships']) && $this->relationships = $options['relationships'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return array
     */
    public function getRelationships()
    {
        return $this->relationships;
    }
}
