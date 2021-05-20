<?php

namespace App\Facades;


use \Illuminate\Support\Facades\Facade;


class NewsApiFacade extends Facade {
    protected static function getFacadeAccessor() { return 'newsapi'; }
}
