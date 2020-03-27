<?php

namespace App\Model;

use App\Model\Relations\UserRelations;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Base{

    use SoftDeletes,UserRelations;

    protected $table = 'user';

    protected $fillable = ['nickname','phone','password','email'];
 
}