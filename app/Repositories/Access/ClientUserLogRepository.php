<?php

namespace App\Repositories\Access;

use App\Models\Auth\ClientUserLog;
use App\Models\Auth\UserLog;
use App\Repositories\BaseRepository;

class ClientUserLogRepository extends BaseRepository
{

    const MODEL = ClientUserLog::class;

}