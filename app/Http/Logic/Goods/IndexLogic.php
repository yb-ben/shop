<?php

namespace App\Http\Logic\Goods;

use App\Http\Logic\Logic;
use App\Model\CategoryAttr;
use App\Model\Goods;
use App\Model\GoodsCategory;
use App\Model\GoodsContent;
use App\Model\GoodsGallery;
use App\Model\GoodsSpec;
use App\Model\GoodsValue;
use Illuminate\Support\Facades\DB;

class IndexLogic extends Logic
{

    public function add($data)
    {
        
       
        return DB::transaction(function () use ($data) {
            
            $goodsCategory = GoodsCategory::where('status',1)->select(['id'])->findOrFail($data['cate_id']);//分类
            $goods = Goods::create($data);
            
            


            $this->saveGallery($data['mImage'],$goods);
            GoodsContent::create(['goods_id' => $goods->id,'content' => $data['content']]);
            return true;
        });
    }


    public function edit($data){
    
        return DB::transaction(function()use($data){
          //  ob_start();
   
            $goods = Goods::with(['content'])->findOrFail($data['id']);
            $goodsCategory = GoodsCategory::where('status',1)->select(['id'])->findOrFail($data['cate_id']);//分类
               
            $goods->title = $data['title'];
            $goods->main_image = $data['main_image'];
            $goods->price = $data['price'];
            $goods->cost = $data['cost'];
            $goods->count = $data['count'];
            $goods->cate_id = $goodsCategory->id;
            $goods->content->content = $data['content'];
            $goods->save();
            $goods->content->save();

            //图片添加
            $this->saveGallery($data['mImage'],$goods,true);
            

            //保存属性 和 属性值
            // 

        
            return true;
        });
    }


    //商品详情
    public function detail($id,$field = ['id','title','main_image','status','price','line_price','cate_id','count'],$with = []){
        
        $goods =  Goods::with(array_merge([ 
            'specs',
            'gallery',
            'content',
            'values',
        ],$with))
            ->select($field)
            ->findOrFail($id);
        return $goods;
    }


    //保存图集
    protected function saveGallery($insert,$goods,$isUpdate = false){
        if($isUpdate){
            $goods->gallery()->delete();
        }
        foreach($insert as $i){
            $m[] = ['goods_id' => $goods->id,'url' => $i['url'],'file_id' => $insert['file_id'] ];
        }
        count($m) && $goods->gallery()->createMany($m);
       
    }

}
