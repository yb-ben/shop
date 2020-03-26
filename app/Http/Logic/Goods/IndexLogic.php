<?php

namespace App\Http\Logic\Goods;

use App\Http\Logic\Logic;
use App\Model\Goods;
use App\Model\GoodsContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IndexLogic extends Logic
{

    public function add($data)
    {

        return DB::transaction(function () use ($data) {

            $goods = new Goods;
            $goods->title = $data['title'];
            $goods->main_image = $data['main_image'];
            $goods->file_id = $data['file_id'];
            $goods->price = $data['price'];
            $goods->line_price = $data['line_price'];
            $goods->count = $data['count'];
            $goods->cate_id = $data['cate_id'];
            $goods->code = $data['code'];
            $goods->how = $data['how'];
            $goodsContent = GoodsContent::create(['content' => $data['content'] ?: '']);
            $goods->content_id = $goodsContent->id;
            $goods->status = $data['status'];
            $goods->limit = $data['limit'];
            $goods->up_at = $data['up_at'];
            $goods->is_timing = $data['is_timing'];
            $goods->spu = $data['spu'];
            $goods->save();

            //保存属性 和 属性值

            $this->saveSKU($goods, $data['sku']);

            $this->saveGallery($data['mImage'], $goods);
            return true;
        });
    }

    public function edit($data)
    {

        return DB::transaction(function () use ($data) {

            $goods = Goods::with(['content'])->find($data['id']);
            $goods->title = $data['title'];
            $goods->main_image = $data['main_image'];
            $goods->file_id = $data['file_id'];
            $goods->price = $data['price'];
            $goods->line_price = $data['line_price'];
            $goods->count = $data['count'];
            $goods->cate_id = $data['cate_id'];
            $goods->code = $data['code'];
            $goods->how = $data['how'];

           $goods->content->content = $data['content'];
           $goods->content->isDirty('content') && $goods->content->save();
           $goods->status = $data['status'];
           $goods->limit = $data['limit'];
           $goods->up_at = $data['up_at']?:time();
           $goods->spu = $data['spu'] ;
           $goods->is_timing = $data['is_timing'];

           
           $goods->isDirty() && $goods->save();

            //保存属性 和 属性值
            $this->saveSKU($goods, $data['sku'],true);

            $this->saveGallery($data['mImage'], $goods,true);

            return true;
        });
    }

    //商品详情
    public function detail($id, $field = null
    , $with = null)
    {
        $f = empty($field)?['id', 'title', 'main_image', 'status', 'price', 'line_price', 'cate_id', 'count', 'spu', 'content_id','file_id','limit','up_at','is_timing']:$field;
        $w = empty($with)?[ 'gallery', 'content', 'specs']:$with;
        $goods = Goods::with($w)
            ->select($f)
            ->findOrFail($id);
        return $goods;
    }

    //保存图集
    protected function saveGallery($data, $goods, $isUpdate = false)
    {
        if(!$isUpdate){

            $this->saveOneToMany($data,$goods,'gallery',function($data,$model){
                foreach ($data as &$i) {
                    $i['goods_id'] = $model->id;
                }
                return $data;
            });
        }else{

            $this->saveOneToMany($data,$goods,'gallery',function($data,$model){
                foreach ($data as &$i) {
                    $i['goods_id'] = $model->id;
                }
                return $data;
            },function($r,$v,$m){
                $r->fill(['goods_id'=>$m->id,'url' => $v['url'] ,'file_id' => $v['file_id']]);
            });
        }

    }

    //保存SKU
    protected function saveSKU($goods, $data, $isUpdate = false)
    {

        if (!$isUpdate) {
            //新增
            $this->saveOneToMany($data,$goods,'specs',function($data,$model){
                foreach ($data as &$i) {
                    $t = [
                        'count' => $i['count'],
                        'price' => $i['price'],
                        'weight' => $i['weight'],
                        'cast' => $i['cast'],
                        'code' => $i['code'],
                        'key' => $i['_id'],
                    ];
                    unset($i['count'], $i['price'], $i['weight'], $i['cast'], $i['code'], $i['_id']);
                    $t['sku'] = $i;
                    $d[] = $t;
                }
                return $d;
            });

        } else {
            //更新
            $this->saveOneToMany($data,$goods,'specs',function($data,$model){
                foreach ($data as &$i) {
                    $t = [
                        'count' => $i['count'],
                        'price' => $i['price'],
                        'weight' => $i['weight'],
                        'cast' => $i['cast'],
                        'code' => $i['code'],
                        'key' => $i['_id'],
                    ];
                    unset($i['count'], $i['price'], $i['weight'], $i['cast'], $i['code'], $i['_id']);
                    $t['sku'] = $i;
                    $d[] = $t;
                }
                return $d;
            },function($r,$v,$m){
                $r->fill($v);
            });
        }

    }


    //批量上架
    public function batchTakeUp($data){
      
            $goods = Goods::whereIn('id',$data)->select(['id','status'])->get();
            $now = time();
            foreach($goods as $good){
                $good->status = 1;
                $good->up_at = $now;
                $good->is_timing = 0; 
                $good->save();
            }
          
     
    }

    //批量下架
    public function batchTakeDown($data){
     
            $goods = Goods::whereIn('id',$data)->select(['id','status'])->get();
            foreach($goods as $good){
                $good->status = 0;
                $good->save();
            }
        
     
    }


    //批量删除
    public function batchDelete($data){
        $goods = Goods::whereIn('id',$data)->select(['id','status'])->get();
        foreach($goods as $item){
            if($item->status !== 1){
                $item->delete();
            }
        }
    }


    private function saveOneToMany(Array $data,Model $model,String $ref,\Closure $beforeInsert ,\Closure $updater = null,$pk = 'id'){

        if($updater){
            $model->load($ref);
            foreach($model->$ref as $r){
                $flag = 0;
                foreach($data as $k => $v){
                    if(isset($v[$pk]) && $r->$pk === $v[$pk]){
                        call_user_func_array($updater,[$r,$v,$model]);
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
