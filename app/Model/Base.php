<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Base extends Model{

    // const DELETED_AT = 'delete_at';
    // const UPDATED_AT = 'update_at';
    // const CREATED_AT = 'create_at';

    public function getDateFormat(){
        return time();
    }



}