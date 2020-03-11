<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class UploadFile extends Base{

    use SoftDeletes;

    protected $table = 'upload_file';

    protected $fillable = ['filetype','fn','size','url','ext','created_at','updated_at','deleted_at'];
   //protected $guarded = ['id'];

    

 
    
}