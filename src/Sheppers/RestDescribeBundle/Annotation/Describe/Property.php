<?php

namespace Sheppers\RestDescribeBundle\Annotation\Describe;

/**
 * @Annotation
 */
class Property
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $sample;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var string
     */
    protected $default;

    /**
     * @var string
     */
    protected $model;

    /**
     * @param $options
     */
    public function __construct($options)
    {
        isset($options['name']) && $this->name = (string) $options['name'];
        isset($options['type']) && $this->type = (string) $options['type'];
        isset($options['description']) && $this->description = (string) $options['description'];
        isset($options['sample']) && $this->sample = (string) $options['sample'];
        isset($options['format']) && $this->format = (string) $options['format'];
        isset($options['default']) && $this->default = (string) $options['default'];
        isset($options['model']) && $this->model = (string) $options['model'];
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getSample()
    {
        return $this->sample;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }
}
