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


    public static function  saveOneToMany(Array $data,Model $model,String $ref,\Closure $beforeInsert ,\Closure $updater = null,$pk = 'id'){

        if($updater){
            $model->load($ref);
            foreach($model->$ref as $r){
                $flag = 0;
                foreach($data as $k => $v){
                    if(isset($v[$pk]) && $r->$pk === $v[$pk]){
                        $updater && call_user_func_array($updater,[$r,$v,$model]);
                        unset($data[$k]);
                        $flag = 1;
                        break;
                    }
                }
                if($r->isDirty()){
                    $r->save();
                }else if(!$flag){
                    $r->delete();
                }
            }
        }
        if(!empty($data)){
            $insert = call_user_func_array($beforeInsert,[$data,$model]);
            $model->$ref()->createMany($insert);
        }
    }
}
