<?php

namespace Sheppers\RestDescribeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sheppers\RestDescribeBundle\Entity\Operation;

/**
 * @ORM\Entity
 * @ORM\Table(name="response")
 */
class Response
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Operation", inversedBy="responses")
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id")
     */
    protected $operation;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $code;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $message;

    /**
     * @param integer $code
     * @param Operation $operation
     */
    public function __construct($code, Operation $operation)
    {
        $this->setCode($code);
        $this->setOperation($operation);
    }

    /**
     * @param $id
     *
     * @return Response
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Operation $operation
     *
     * @return Response
     */
    public function setOperation(Operation $operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * @return Operation
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param integer $code
     *
     * @return Response
     */
    public function setCode($code)
    {
        $this->code = (integer) $code;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $message
     *
     * @return Response
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
