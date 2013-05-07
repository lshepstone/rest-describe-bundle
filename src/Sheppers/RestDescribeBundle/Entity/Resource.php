<?php

namespace Sheppers\RestDescribeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="resource")
 */
class Resource
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $class;

    /**
     * @ORM\OneToMany(targetEntity="Operation", mappedBy="resource")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $operations;

    /**
     * Constructs a Resource instance.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
        $this->operations = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     *
     * @return Resource
     */
    public function setId($id)
    {
        $this->id = (integer) $id;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return Resource
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @param string $class
     *
     * @return Resource
     */
    public function setClass($class)
    {
        $this->class = (string) $class;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
}
