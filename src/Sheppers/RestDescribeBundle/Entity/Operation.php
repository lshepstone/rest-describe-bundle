<?php

namespace Sheppers\RestDescribeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Sheppers\RestDescribeBundle\Entity\Resource;

/**
 * @ORM\Table(name="operation")
 * @ORM\Entity(repositoryClass="Sheppers\RestDescribeBundle\Repository\OperationRepository")
 */
class Operation implements SecureInterface
{
    const SCOPE_RESOURCE = 'resource';
    const SCOPE_COLLECTION = 'collection';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Resource instance the operation relates to.
     *
     * @ORM\ManyToOne(targetEntity="Resource", inversedBy="operations")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     *
     * @var Resource
     */
    protected $resource;

    /**
     * Operation name.
     *
     * @ORM\Column(type="string", unique=true)
     *
     * @var string
     */
    protected $name;

    /**
     * Note of the operation.
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $description;

    /**
     * Scope of the operation (collection or resource).
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $scope;

    /**
     * HTTP method use to execute the operation.
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $method;

    /**
     * URI to use for the operation.
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $uri;

    /**
     * Indicates whether the operation has Response.
     *
     * @ORM\Column(name="is_response_empty", type="boolean")
     *
     * @var bool
     */
    protected $isResponseEmpty = false;

    /**
     * Operation request parameters.
     *
     * @ORM\OneToMany(targetEntity="Parameter", mappedBy="operation")
     *
     * @var array
     */
    protected $parameters = array();

    /**
     * Operation response codes.
     *
     * @ORM\OneToMany(targetEntity="Response", mappedBy="operation")
     *
     * @var array
     */
    protected $responses = array();

    /**
     * Operation roles.
     *
     * @ORM\Column(type="array", nullable=true)
     *
     * @var array
     */
    protected $roles = array();

    /**
     * @param string $name
     * @param Resource $resource
     */
    public function __construct($name, Resource $resource)
    {
        $this->setName($name);
        $this->setResource($resource);
        $this->parameters = new ArrayCollection();
        $this->responses = new ArrayCollection();
    }

    /**
     * @param string $method
     *
     * @return Operation
     */
    public function setMethod($method)
    {
        $this->method = (string) $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $uri
     *
     * @return Operation
     */
    public function setUri($uri)
    {
        $this->uri = (string) $uri;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param boolean $isResponseEmpty
     */
    public function setIsResponseEmpty($isResponseEmpty)
    {
        $this->isResponseEmpty = (bool) $isResponseEmpty;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isResponseEmpty()
    {
        return $this->isResponseEmpty;
    }

    /**
     * @param string $name
     *
     * @return Operation
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
     * @param string $description
     *
     * @return Operation
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
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param Resource $resource
     *
     * @return Operation
     */
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param string $scope
     *
     * @return Operation
     */
    public function setScope($scope)
    {
        $this->scope = (string) $scope;

        return $this;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return array
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * @param array $roles
     *
     * @return Operation
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
