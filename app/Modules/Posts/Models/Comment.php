<?php
namespace App\Modules\Posts\Models;

use HZ\Illuminate\Mongez\Managers\Database\MongoDB\Model;

class Comment extends Model {
    const SHARED_INFO = ['id', 'comment', 'post', 'approved', 'parentComment'];
}
