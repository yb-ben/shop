<?php


namespace App\Http\Controllers\Index\Category;


use App\Http\Controllers\Controller;
use App\Model\GoodsCategory;
use App\Utils\Format;
use App\Utils\Response;
use Huyibin\Struct\Tree;

class IndexController extends Controller
{

    /**
     * 分类菜单
     * @return \Illuminate\Http\JsonResponse
     */
    public function tree(){
        $data= GoodsCategory::select(['id','name','parent_id','image_id'])
            ->withImage()
            ->orderby('sort')
            ->get()
            ->makeHidden(['image_id'])
            ->toArray();
        foreach ($data as &$item){
            $item['image'] = $item['image']['url_full'];
        }
        $data = array_values( Tree::tree($data));

        return Response::api($data);
    }
}
