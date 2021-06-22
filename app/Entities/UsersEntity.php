<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Fluent\JWTAuth\Contracts\JWTSubjectInterface;

class UsersEntity extends Entity implements JWTSubjectInterface
{
    /**
     * {@inheritdoc}
     */
    public function getJWTIdentifier()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

