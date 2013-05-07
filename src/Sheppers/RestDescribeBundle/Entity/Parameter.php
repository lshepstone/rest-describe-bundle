<?php

namespace Sheppers\RestDescribeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sheppers\RestDescribeBundle\Entity\Operation;

/**
 * @ORM\Entity
 * @ORM\Table(name="parameter")
 */
class Parameter
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Operation", inversedBy="parameters")
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id")
     */
    protected $operation;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $type;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $note;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $location;

    /**
     * @ORM\Column(name="is_required", type="string")
     *
     * @var bool
     */
    protected $isRequired = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $sample;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $format;

    /**
     * @ORM\Column(name="`default`", type="string", nullable=true)
     *
     * @var string
     */
    protected $default;

    /**
     * Constructs a Parameter instance.
     *
     * @param string $name
     */
    public function __construct($name, Operation $operation)
    {
        $this->setName($name);
        $this->setOperation($operation);
    }

    /**
     * Sets the related Operation instance.
     *
     * @param Operation $operation
     */
    public function setOperation(Operation $operation)
    {
        $this->operation = $operation;
    }

    /**
     * Gets the related Operation instance.
     *
     * @return Operation
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param string $name
     *
     * @return Parameter
     */
    public function setName($name)
    {
        $this->name = (string) $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $default
     *
     * @return Parameter
     */
    public function setDefault($default)
    {
        $this->default = (string) $default;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param string $note
     *
     * @return Parameter
     */
    public function setNote($note)
    {
        $this->note = (string) $note;

        return $this;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $format
     *
     * @return Parameter
     */
    public function setFormat($format)
    {
        $this->format = (string) $format;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param boolean $isRequired
     *
     * @return Parameter
     */
    public function setRequired($isRequired)
    {
        $this->isRequired = (boolean) $isRequired;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->isRequired;
    }

    /**
     * @param string $location
     *
     * @return Parameter
     */
    public function setLocation($location)
    {
        $this->location = (string) $location;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $sample
     *
     * @return Parameter
     */
    public function setSample($sample)
    {
        $this->sample = (string) $sample;

        return $this;
    }

    /**
     * @return string
     */
    public function getSample()
    {
        return $this->sample;
    }

    /**
     * @param string $type
     *
     * @return Parameter
     */
    public function setType($type)
    {
        $this->type = (string) $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
