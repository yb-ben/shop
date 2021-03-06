<?php

namespace App\Http\Controllers\Admin\Upload;

use App\Http\Controllers\Controller;
use App\Model\Image;
use App\Model\UploadFile;
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


        $uploadFile =UploadFile::create([
            'filetype' =>  $file->getMimeType(),
            'fn' => substr($path,strrpos($path,'/')+1),
            'size' => $file->getSize(),
            'url' => $path,
        ]);

        return Response::api(['file_id' => $uploadFile->id ,'path' => $path,'path_full' => env('APP_IMAGE_SERVER').$path]);
    }

    //多图片上传
    public function uploadMultiImage(Request $request){

    }


    //图片选择
    public function imageList(Request $request){
        $img = Image::select(['id','fn','size'])
        ->orderby('id','desc')
        ->paginate($request->input('limit',10));
//
//        foreach($img as $i){
//            $i->setAppends(['url_full']);
//        }
        return Response::api($img);
    }

}
