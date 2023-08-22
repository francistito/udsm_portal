<?php

namespace App\Models\Access\Relationship;

use App\Models\Access\User;



/**
 * Class RoleRelationship
 * @package App\Models\Access\Relationship
 */
trait RoleRelationship
{
    /**
     * @return mixed
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }




}
