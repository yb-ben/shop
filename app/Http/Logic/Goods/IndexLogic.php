<?php

namespace App\Http\Logic\Goods;

use App\Http\Logic\Logic;
use App\Model\CategoryAttr;
use App\Model\Goods;
use App\Model\GoodsAttr;
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
            
            !empty($data['spu']) && ($goods->spu = $data['spu']);
            $goods->save();
        
        
            //保存属性 和 属性值
           
            $this->saveSKU($goods,$data['sku']);

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
    public function detail($id,$field = ['id','title','main_image','status','price','line_price','cate_id','count','spu'],$with = []){
        
        $goods =  Goods::with(array_merge([ 
            'gallery',
            'content',
            'specs',
        ],$with))
            ->select($field)
            ->findOrFail($id);
        return $goods;
    }


    //保存图集
    protected function saveGallery($data,$goods,$isUpdate = false){
        
        if($isUpdate){
            $goods->gallery()->delete();
        }
        if(!empty($data)){
            foreach($data as $i){
                $m[] = ['goods_id' => $goods->id,'url' => $i['url'],'file_id' => $data['file_id'] ];
            }
            count($m) && $goods->gallery()->createMany($m);    
        }
       
    }


    //保存SKU
    protected function saveSKU($goods,$data){
        ob_start();
        $goods->load('specs');
        $update = [];
        $insert = [];
        $delete = [];
        foreach($data as $i){
            $flag = -1;
            foreach($goods->specs as $k => $spec){
                if($i['_id'] === $spec->k){
                    $spec->fill($i)->save();
                    $flag = $k;
                    $update[$k] = 1;
                    break;
                }
            }
            if($flag === -1){
                $insert[] = $i;
            }
        }

        if(!empty($update)){
            foreach($goods->specs as $k => $s){
                if(!isset($update[$k])){
                    $delete[] = $s->id;
                    continue;
                }
            }
            if(!empty($delete)){
                $goods->specs()->delete($delete);                
            }
        }
        if(!empty($insert)){
           
            foreach($insert as &$i){
                $t = [
                    'count' => $i['count'],
                    'price' => $i['price'],
                    'weight' => $i['weight'],
                    'cast' => $i['cast'],
                    'code' => $i['code'],
                    'key' => $i['_id'],
                ];
                unset($i['count'],$i['price'],$i['weight'],$i['cast'],$i['code'],$i['_id']);
                $t['sku'] = $i;
                $d[] = $t;
            }
         //   print_r($d);
            $goods->specs()->createMany($d);
        }
    }
}
