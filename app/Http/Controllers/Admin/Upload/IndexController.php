<?php

namespace App\Http\Controllers\Admin\Upload;

use App\Http\Controllers\Controller;
use App\Utils\Response;
use Illuminate\Http\Request;

class IndexController extends Controller{



    //图片上传
    public function uploadImage(Request $request){

        if(!$request->hasFile('file')){
            return Response::apiError('上传文件不存在');    
        }
        $file  = $request->file('file');
        if(!$file->isValid()){
            return Response::apiError('上传文件无效');    
        }
        $path =  $file->store('images');
        return Response::api(['path' => $path,'path_full' => env('APP_URL'.$path)]);
    }

    //多图片上传
    public function uploadMultiImage(Request $request){

    }

}