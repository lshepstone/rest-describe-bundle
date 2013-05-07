<?php

namespace Sheppers\RestDescribeBundle\Annotation\Describe;

/**
 * @Annotation
 */
class Parameter
{
    const LOCATION_PATH = 'path';
    const LOCATION_QUERY = 'query';
    const LOCATION_ENTITY = 'entity';

    /**
     * @var string
     */
    protected $type;
    /**
     * @var string
     */
    protected $note;
    /**
     * @var string
     */
    protected $location;
    /**
     * @var bool
     */
    protected $isRequired = false;
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
     * @param $options
     */
    public function __construct($options)
    {
        isset($options['type']) && $this->type = (string) $options['type'];
        isset($options['note']) && $this->note = (string) $options['note'];
        isset($options['location']) && $this->location = (string) $options['location'];
        isset($options['required']) && $this->isRequired = (boolean) $options['required'];
        isset($options['sample']) && $this->sample = (string) $options['sample'];
        isset($options['location']) && $this->location = (string) $options['location'];
        isset($options['format']) && $this->format = (string) $options['format'];
        isset($options['default']) && $this->default = (string) $options['default'];
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
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
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->isRequired;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }
}
