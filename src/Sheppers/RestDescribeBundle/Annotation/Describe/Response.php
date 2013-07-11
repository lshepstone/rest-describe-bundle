<?php

namespace Sheppers\RestDescribeBundle\Annotation\Describe;

/**
 * @Annotation
 */
class Response
{
    /**
     * @var array
     */
    protected $codes;

    /**
     * @var bool
     */
    protected $isEmpty;

    /**
     * @param array $options
     */
    public function __construct($options)
    {
        isset($options['codes']) && $this->codes = $options['codes'];
        isset($options['empty']) && $this->isEmpty = $options['empty'];
    }

    /**
     * @return array
     */
    public function getCodes()
    {
        return $this->codes;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->isEmpty;
    }
}
