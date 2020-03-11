<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Base extends Model{

    // const DELETED_AT = 'delete_at';
    // const UPDATED_AT = 'update_at';
    // const CREATED_AT = 'create_at';

    public function getDateFormat(){
        return time();
    }


    protected $casts =[
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
}