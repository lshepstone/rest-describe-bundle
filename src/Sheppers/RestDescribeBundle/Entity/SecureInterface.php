<?php

namespace Sheppers\RestDescribeBundle\Entity;

interface SecureInterface
{
    public function setRoles($roles);

    public function getRoles();
}