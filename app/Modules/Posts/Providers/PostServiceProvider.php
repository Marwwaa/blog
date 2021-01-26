<?php
namespace App\Modules\Posts\Providers;

use HZ\Illuminate\Mongez\Managers\Providers\ModuleServiceProvider;

class PostServiceProvider extends ModuleServiceProvider
{
    /**
     * {@inheritDoc}
     */
    const ROUTES_TYPES = ["admin","site"];
    
    /**
     * {@inheritDoc}
     */    
    protected $namespace = 'App/Modules/Posts/';
}
