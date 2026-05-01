<?php

namespace AhmedTaha\FourjawalyPackage\Facades;

use AhmedTaha\FourjawalyPackage\FourJawalyService;
use Illuminate\Support\Facades\Facade;

class FourJawalyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FourJawalyService::class;
    }
}
