<?php
namespace App\Services\Sysdef\Facades;

use Illuminate\Support\Facades\Facade;


class SystemFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sysdef';
    }
}