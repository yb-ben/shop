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
            $goods = new  Goods;
            $goods->title = $data['title'];
            $goods->main_image = $data['main_image'];
            $goods->file_id = $data['file_id'];
            $goods->price = $data['price'];
            $goods->line_price = $data['line_price'];
            $goods->count = $data['count'];
            $goods->cate_id = $goodsCategory->id;
            $goods->code = $data['code'];
            $goods->how = $data['how'];
            $goodsContent = GoodsContent::create(['content' => $data['content']?:'']);
            $goods->content_id = $goodsContent->id;
            $goods->save();
        
        
            //保存属性 和 属性值
            $insertSpecs = [];
            foreach($data['spec'] as $spec){
                $insertSpecs[] = [
                    'spu' => $spec['spu'],
                    'count' => $spec['count'],
                    'price' => $spec['price'],
                    'weight' => $spec['weight'],
                    'cost' => $spec['cost'],
                    'code' => $spec['code'],
                    'goods_id' => $goods->id
                ];
            }
            $goods->specs()->createMany($insertSpecs);

            $this->saveGallery($data['mImage'],$goods);
            return true;
        });
    }


    public function edit($data){
    
        return DB::transaction(function()use($data){
          
            $goodsCategory = GoodsCategory::where('status',1)->select(['id'])->findOrFail($data['cate_id']);//分类
            $goods = Goods::find($data['id']);
            $goods->title = $data['title'];
            $goods->main_image = $data['main_image'];
            $goods->file_id = $data['file_id'];
            $goods->price = $data['price'];
            $goods->line_price = $data['line_price'];
            $goods->count = $data['count'];
            $goods->cate_id = $goodsCategory->id;
            $goods->code = $data['code'];
            $goods->how = $data['how'];
            $goods->save();
        
            //保存属性 和 属性值
            GoodsSpec::where('goods_id',$goods->id)->delete();
            $insertSpecs = [];
            foreach($data['spec'] as $spec){
                $insertSpecs[] = [
                    'spu' => $spec['spu'],
                    'count' => $spec['count'],
                    'price' => $spec['price'],
                    'weight' => $spec['weight'],
                    'cost' => $spec['cost'],
                    'code' => $spec['code'],
                    'goods_id' => $goods->id
                ];
            }
            $goods->specs()->createMany($insertSpecs);
            
            //图片添加
            $this->saveGallery($data['mImage'],$goods,true);
        
            $goods->content->content = $data['content'];
            $goods->content->save();
            return true;
        });
    }


    //商品详情
    public function detail($id,$field = ['id','title','main_image','status','price','line_price','cate_id','count'],$with = []){
        
        $goods =  Goods::with(array_merge([ 
            'gallery',
            'content',
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
        if(!empty($insert)){
            foreach($insert as $i){
                $m[] = ['goods_id' => $goods->id,'url' => $i['url'],'file_id' => $insert['file_id'] ];
            }
            count($m) && $goods->gallery()->createMany($m);    
        }
       
    }

}
