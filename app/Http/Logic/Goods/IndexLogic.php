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

            !empty($data['spu']) && ($goods->spu = $data['spu']);
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

            //保存属性 和 属性值

            $this->saveSKU($goods, $data['sku'],true);

            $this->saveGallery($data['mImage'], $goods,true);

            $goods->save();
            return true;
        });
    }

    //商品详情
    public function detail($id, $field = ['id', 'title', 'main_image', 'status', 'price', 'line_price', 'cate_id', 'count', 'spu', 'content_id'], $with = [])
    {

        $goods = Goods::with(array_merge([
            'gallery',
            'content',
            'specs',
        ], $with))
            ->select($field)
            ->findOrFail($id);
        return $goods;
    }

    //保存图集
    protected function saveGallery($data, $goods, $isUpdate = false)
    {

        if ($isUpdate) {
            $goods->load('gallery');
            foreach($goods->gallery as $g){
                foreach($data as $k => $v){
                    if(isset($v['id']) && $g->id === $v['id']){
                        $g->fill(['goods_id'=>$goods->id,'url' => $v['url'] ,'file_id' => $v['file_id']]);
                        unset($data[$k]);
                        break;
                    }
                }
                $g->isDirty() ? $g->save() : $g->delete() ;
            }
        }
        if (!empty($data)) {
            foreach ($data as $i) {
                $m[] = ['goods_id' => $goods->id, 'url' => $i['url'], 'file_id' => $data['file_id']];
            }
            count($m) && $goods->gallery()->createMany($m);
        }

    }

    //保存SKU
    protected function saveSKU($goods, $data, $isUpdate = false)
    {

        if (!$isUpdate) {
            //新增
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
            $goods->specs()->createMany($d);

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



    private function saveOneToMany(Array $data,Model $model,String $ref,\Closure $beforeInsert ,\Closure $updater = null){

        if($updater){
            $model->load($ref);
            foreach($model->$ref as $r){
                foreach($data as $k => $v){
                    if(isset($v['id']) && $r->id === $v['id']){
                        call_user_func_array($updater,[$r,$v,$model]);
                        unset($data[$k]);
                        break;
                    }
                }
                $r->isDirty() ? $r->save() : $r->delete();
            }
        }
        if(!empty($data)){
            $insert = call_user_func_array($beforeInsert,[$data,$model]);
            $model->$ref()->createMany($insert);
        }
    }
}
