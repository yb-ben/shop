<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class UploadFile extends Base
{

    use SoftDeletes;

    protected static $fileTypeMap = [
        'image/jpg' => 1,
        'image/jpeg' => 1,
        'image/jfif' => 1,
        'image/png' => 2,
        'image/gif' => 3,
        'image/bmp' => 4
    ];

    protected $table = 'upload_file';

    protected $fillable = ['filetype', 'fn', 'size', 'url', 'ext', 'created_at', 'updated_at', 'deleted_at'];


    //自动设置文件类型
    public function setFileTypeAttribute($value){
        if(isset(static::$fileTypeMap[$value])){
            $this->attributes['filetype'] = static::$fileTypeMap[$value];
        }else{
            throw new \Exception('不允许的文件类型');
        }
    }


    public function getUrlFullAttribute()
    {
        return env('APP_URL') . $this->url;
    }


    public static function getFileType(){
        return static::$fileTypeMap;
    }
}
