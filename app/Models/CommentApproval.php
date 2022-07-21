<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentApproval extends Model
{
    use HasFactory;

    protected $filled = 
    [
        'comment_id',
        'user_id',
        'has_approved'
    ];
}
