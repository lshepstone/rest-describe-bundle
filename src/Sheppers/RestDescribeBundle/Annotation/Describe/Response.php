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
     * @param array $options
     */
    public function __construct($options)
    {
        isset($options['codes']) && $this->codes = $options['codes'];
    }

    /**
     * @return array
     */
    public function getCodes()
    {
        return $this->codes;
    }
}
