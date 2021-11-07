<?php

namespace Src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthModel extends Model
{
    protected $table = 'oauth_data';
    protected $fillable = [
        'id',
        'oauth_name',
        'client_id',
        'client_secret'
    ];
    public function __construct()
    {
    }
}
