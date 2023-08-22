<?php

namespace App\Models\Access;

use App\Models\Access\Attribute\UserLogAttribute;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use UserLogAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
