<?php

namespace Sheppers\RestDescribeBundle\Annotation\Describe;

use Sheppers\RestDescribeBundle\Annotation\Describe;

/**
 * @Annotation
 */
class Operation
{
    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Describe\Request
     */
    protected $request;

    /**
     * @var Describe\Response
     */
    protected $response;

    /**
     * @var string
     */
    protected $scope;

    /**
     * @param $options
     */
    public function __construct($options)
    {
        isset($options['description']) && $this->description = (string) $options['description'];
        isset($options['name']) && $this->name = (string) $options['name'];
        isset($options['scope']) && $this->scope = (string) $options['scope'];
        isset($options['request']) && $this->request = $options['request'];
        isset($options['response']) && $this->response = $options['response'];
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return \Sheppers\RestDescribeBundle\Annotation\Describe\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return \Sheppers\RestDescribeBundle\Annotation\Describe\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
