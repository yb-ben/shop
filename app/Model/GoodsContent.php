<?php

namespace App\Model;

use App\Model\Base;

class GoodsContent extends Base{

    public $timestamps = false;
    protected $table = 'goods_content';
    
    protected $fillable = ['content'];

    public function setContentAttribute($value){
        $this->attributes['content'] = htmlspecialchars($value);
    }

    public function getContentAttribute($value){
        return html_entity_decode($value);
    }
}