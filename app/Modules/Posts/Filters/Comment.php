<?php
namespace App\Modules\Comments\Filters;

use HZ\Illuminate\Mongez\Helpers\Filters\MongoDB\Filter;

class Comment extends Filter
{
    /**
     * List with all filter. 
     *
     * Comment => functionName
     * @const array 
     */
    const FILTER_MAP = [];    
}