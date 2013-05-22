<?php

namespace Sheppers\RestDescribeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sheppers\RestDescribeBundle\Entity\Resource;

/**
 * @ORM\Entity
 * @ORM\Table(name="property")
 */
class Property
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Resource", inversedBy="properties")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     */
    protected $resource;

    /**
     * @ORM\Column(type="string")
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
    protected $description;

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
     * Constructs a Property instance.
     *
     * @param string $name
     * @param Resource $resource
     */
    public function __construct($name, Resource $resource)
    {
        $this->setName($name);
        $this->setResource($resource);
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
     * Sets the related Resource instance.
     *
     * @param Resource $resource
     */
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Gets the related Resource instance.
     *
     * @return Resource
     */
    public function getResource()
    {
        return $this->resource;
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
     * @param string $description
     *
     * @return Parameter
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
