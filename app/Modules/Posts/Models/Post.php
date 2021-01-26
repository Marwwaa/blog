<?php
namespace App\Modules\Posts\Models;

use HZ\Illuminate\Mongez\Managers\Database\MongoDB\Model;

class Post extends Model {
    const SHARED_INFO = ['id', 'title', 'description', 'published', 'image'];
}
