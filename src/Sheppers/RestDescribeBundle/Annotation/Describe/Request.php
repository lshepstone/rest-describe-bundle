<?php

namespace Sheppers\RestDescribeBundle\Annotation\Describe;

/**
 * @Annotation
 */
class Request
{
    /**
     * @var string
     */
    protected $model;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @param $options
     */
    public function __construct($options)
    {
        isset($options['model']) && $this->model = (string) $options['model'];
        isset($options['parameters']) && $this->parameters = $options['parameters'];
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
    public function getParameters()
    {
        return $this->parameters;
    }
}
