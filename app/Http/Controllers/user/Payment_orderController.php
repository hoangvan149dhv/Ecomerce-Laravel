<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Cart;
use DB;
use Session;
use App\Http\Requests; //
use Illuminate\Support\Facades\Redirect;
use App\CustomerorderModel;
use App\Http\Controllers\sendMailController;
use App\templateMailModel;
use App\Http\Controllers\user\HomeController;
class Payment_orderController extends HomeController
{
    public function payment_order(Request $request){

        $content = Cart::content();

        // INSERT CUSTOMER
        $cus_data['cusname'] = $request->name;
        $cus_data['cusadd'] = $request->add;
        $cus_data['cusPhone'] =$request->phone;

        if(empty($request->phone)|| empty($request->add) || empty($request->name)){

            Session::put('error','Bạn Không Được Để Trống bất kì mục nào');
            return Redirect::to('/hien-thi-gio-hang');

        }else{

            $cus_id = DB::table('tbl_customer')->insertGetId($cus_data);

        // INSERT ORDER_PAYMENT
            foreach($content as $value_content){
                $order_data['cusid']       = $cus_id;
                $order_data['cusname']     = $request->name;
                $order_data['product_id']  = $value_content->id;
                $order_data['productname'] = $value_content->name;
                $order_data['price']       = $value_content->price;
                $order_data['soluong']     = $value_content->qty;
                $order_data['fee_ship']    = $request->val_feeship;
                $order_data['total']       = ($order_data['price'] * $order_data['soluong']) +  $order_data['fee_ship'];
                $order_data['image']       = $value_content->options->images;
                $order_data['cusphone']    = $request->phone;

                if(empty($request->note))
                {
                    $order_data['note'] = "Null";
                }else {
                    $order_data['note'] = $request->note; //GHI CHÚ
                }
                $order_data['status']="đang xử lý"; //TRẠNG THÁI XỬ LÝ
                $getIdorder = DB::table('tbl_order')->insertGetId($order_data);

                $item_detail_order = CustomerorderModel::where('orderid',$getIdorder)->get();

                if($item_detail_order){
                    $sendmail = new sendMailController();

                    $sendmail->sendMail(
                        $fromname,
                        $mailconfig_recipient,
                        $ccname,
                        $subject,
                        $file_template_mail,
                        $template,
                        $item_detail_order);
                }
            }
            Cart::destroy();

            return view('user.payment.payment_order');
        }
    }
}