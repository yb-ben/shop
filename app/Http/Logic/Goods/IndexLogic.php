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
        
        //var_dump($data);
        return DB::transaction(function () use ($data) {
            $goods = Goods::create($data);
            if (count($data['attrValues'])>0) {
                $goodsCategory = GoodsCategory::select(['id', 'status'])->findOrFail($data['cate_id']);
                $categoryAttrs = CategoryAttr::where('cate_id', $goodsCategory->id)->select(['id', 'name'])->get();

                $avs = [];
                $time = time();

                //插入spu属性   
                foreach ($categoryAttrs as $item) {
                    foreach ($data['attrValues'] as $a) {
                        if ($item->id == $a['id']) {
                            if (isset($a['values']) && !empty($a['values'])) {

                                foreach ($a['values'] as $value) {

                                    $avs[] = ['goods_id' => $goods->id, 'val' => $value, 'attr_id' => $a['id'],'created_at' => $time,'updated_at' => $time];

                                }

                            }
                        }
                    }
                }
               
                if (count($avs) > 0) {

                    GoodsValue::insert($avs);
                    $goodsValue = GoodsValue::where('goods_id',$goods->id)->select(['id','attr_id','val'])->get();

                    
                    //插入sku
                    $sku = [];

                    foreach ($data['sku'] as $item) {
                        $flag = 1; $spustr = '';
                        foreach ($categoryAttrs as $ca) {
                            //检查属性是否遗漏
                            if (!isset($item[$ca->id])) {
                                $flag = 0;
                                break;
                            } else {
                                foreach ($goodsValue as $gv) {
                                    if ($gv->attr_id === $ca->id && $gv->val === $item[$ca->id]) {
                                        $spustr .= "{$ca->id}:{$gv->id},";
                                        break;
                                    }
                                }
                            }
                        }
                        if ($flag) {
                            $sku[] = ['spu' => rtrim($spustr, ','), 'goods_id' => $goods->id, 'count' => $item['count'], 'price' => $item['price'], 'line_price' => $item['line_price'],'created_at' => $time,'updated_at' => $time];
                        }
                    }
                    if (count($sku) > 0) {
                        GoodsSpec::insert($sku);
                    }
                }

            
            }
            if (count($data['mImage']) > 0){
                $m = [];
                foreach($data['mImage'] as $i){
                    $m[] = ['goods_id' => $goods->id,'img' => $i];
                }
                GoodsGallery::insert($m);
            }
            GoodsContent::create(['goods_id' => $goods->id,'content' => $data['content']]);
            return true;
        });
    }


    public function edit($data){
        return DB::transaction(function()use($data){


            $goods = Goods::with(['content'])->findOrFail($data['id']);
            $goods->title = $data['title'];
            $goods->main_image = $data['main_image'];
            $goods->price = $data['price'];
            $goods->line_price = $data['line_price'];
            $goods->count = $data['count'];
            $goods->cate_id = $data['cate_id'];
            $goods->save();

            if (count($data['attrValues'])>0) {
                $goodsCategory = GoodsCategory::select(['id', 'status'])->findOrFail($data['cate_id']);
                $categoryAttrs = CategoryAttr::where('cate_id', $goodsCategory->id)->select(['id', 'name'])->get();

                $avs = [];
                $time = time();
                
                //插入spu属性   
                foreach ($categoryAttrs as $item) {
                    foreach ($data['attrValues'] as $a) {
                        if ($item->id == $a['id']) {
                            if (isset($a['values']) && !empty($a['values'])) {

                                foreach ($a['values'] as $value) {

                                    $avs[] = ['goods_id' => $goods->id, 'val' => $value, 'attr_id' => $a['id'],'created_at' => $time,'updated_at' => $time];

                                }

                            }
                        }
                    }
                }

               
                if (count($avs) > 0) {

                    GoodsValue::where('goods_id',$goods->id)->delete();
                    GoodsValue::insert($avs);
                    $goodsValue = GoodsValue::where('goods_id',$goods->id)->select(['id','attr_id','val'])->get();

                    
                    //插入sku
                    $sku = [];

                    foreach ($data['sku'] as $item) {
                        $flag = 1; $spustr = '';
                        foreach ($categoryAttrs as $ca) {
                            //检查属性是否遗漏
                            if (!isset($item[$ca->id])) {
                                $flag = 0;
                                break;
                            } else {
                                foreach ($goodsValue as $gv) {
                                    if ($gv->attr_id === $ca->id && $gv->val === $item[$ca->id]) {
                                        $spustr .= "{$ca->name}:{$gv->val},";
                                        break;
                                    }
                                }
                            }
                        }
                        if ($flag) {
                         
                            $sku[] = ['spu' =>rtrim($spustr, ',') , 'goods_id' => $goods->id, 'count' => $item['count'], 'price' => $item['price'], 'line_price' => $item['line_price'],'created_at' => $time,'updated_at' => $time];
                        }
                    }
                    if (count($sku) > 0) {
                       // GoodsSpec::insert($sku);
                       $specs = GoodsSpec::where('goods_id',$goods->id)->get();
                       if(empty($specs)){
                        GoodsSpec::insert($sku);
                       }else{
                           $insert = [];
                           foreach($sku as $s){
                               $flag = 0;
                                foreach($specs as $k => $spec){
                                    if($spec->spu === $s['spu']){
                                        $spec->fill($s);
                                        $spec->save();
                                        $flag = 1;
                                        unset($specs[$k]);
                                        break;
                                    }
                                }
                                if(!$flag){
                                    $insert[] = $s;
                                }
                            }
                            $keys =$specs->modelKeys();
                            if(!empty($keys)){
                                GoodsSpec::destroy($keys);
                            }
                            if(!empty($insert)){
                                GoodsSpec::insert($insert);
                            }
                       }
                      
                    }
                }

            
            }else{
                GoodsSpec::where('goods_id',$goods->id)->delete();
                GoodsValue::where('goods_id',$goods->id)->delete();
            }
            if (count($data['mImage']) > 0){
                $m = [];
                foreach($data['mImage'] as $i){
                    $m[] = ['goods_id' => $goods->id,'img' => $i];
                }
                GoodsGallery::insert($m);
            }else{
                GoodsGallery::where('goods_id',$goods->id)->delete();
            }
            $goods->content->content = $data['content'];
            $goods->content->save();
            return true;
        });
    }


    public function detail($id){

        $goods =  Goods::with([ 
            'specs',
            'gallery',
            'content',
            'values',
           
            ])
            ->select(['id','title','main_image','status','price','line_price','cate_id','count'])
            ->find($id);
            $goods->setAppends(['main_image_full']);
            foreach($goods->gallery as $gallery){
                $gallery->setAppends(['img_full']);
            }
           $attrs = CategoryAttr::where('cate_id',$goods->cate_id)->select(['id','name'])->get()->toArray();
           $attrs = array_column($attrs,null,'id');
            
        

      //  $goods->content = $goods->content->content;
        return $goods;
    }
}
