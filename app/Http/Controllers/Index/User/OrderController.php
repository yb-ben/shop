<?php


namespace App\Http\Controllers\Index\User;


use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Utils\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * 部分订单数量
     * @return \Illuminate\Http\JsonResponse
     */
    public function someCount(){

        $data = Order::where('user_id',Auth::id())
            ->where('status','in',[0,8,4])
            ->groupby('status','has_comment')
            ->select(DB::raw('status,has_comment,COUNT(id) as count'))
            ->get()
            ->toArray()
        ;

        $waitPay = $waitGet = $waitComment = 0;
        foreach ($data as $item){
            if($item['status'] === 0){
                $waitPay += $item['count'];
            }elseif ($item['status'] === 4 && $item['has_comment'] === 0){
                $waitComment += $item['count'];
            }else{
                $waitGet += $item['count'];
            }
        }

        return Response::api(['waitPay'=>$waitPay,'waitGet'=>$waitGet,'waitComment'=>$waitComment]);
    }

}
