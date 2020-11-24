<?php

    namespace App\Http\Controllers\user;

    use App\Http\Model\ReviewModel;
    use Illuminate\Http\Request;
    use DB;
    use Session;
    use App\Http\Requests;

    //
    use Illuminate\Support\Facades\Redirect;
    use App\Http\Controllers\user\HomeController;

    class DetailsProductController extends HomeController
    {

        public function show_details($meta_slug, request $request)
        {
            if ( empty($meta_slug))
            {
                return back();
            }
            $show_details_product = DB::table('tbl_product')
                ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
                ->join('tbl_brand_code_product', 'tbl_brand_code_product.code_id', '=', 'tbl_product.brandcode_id')
                ->where('meta_slug', $meta_slug)
                ->where('tbl_product.publish','=', 1)->get();

            if (empty($show_details_product)) {

                return redirect('trang-chu');
            }
            //SẢN PHÂM ĐC QUAN TÂM (SẢN PHẨM MỚI)
            $show_details_product_recommended = DB::table('tbl_product')
                ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
                ->where('tbl_product.promotion_end_date', '>=', self::getcurrentTime())
                ->where('tbl_product.publish','=', 1)
                ->orwhere('tbl_product.promotion_end_date', '=' , null)
                ->orwhere('product_price_promotion', '=', 1)
                ->join('tbl_brand_code_product', 'tbl_brand_code_product.code_id', '=', 'tbl_product.brandcode_id')
                ->whereNotIn('tbl_product.meta_slug', [$meta_slug])
                ->limit('3')->orderby('product_id', 'desc')->get();


            foreach ($show_details_product as $value) {
                $category_product_id = $value->category_id;
                $brand_product_id = $value->brandcode_id;
                $meta_desc = $value->meta_desc;
                $meta_keyword = $value->meta_keyword;
                $meta_title = $value->product_Name;
                $url_canonical = $request->url();

                ///SEO
            }
            if (isset($brand_product_id)) {
                $related_product = DB::table('tbl_product')
                    ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
                    ->join('tbl_brand_code_product', 'tbl_brand_code_product.code_id', '=', 'tbl_product.brandcode_id')
                    ->where('tbl_product.promotion_end_date', '>=', self::getcurrentTime())
                    ->orwhere('tbl_product.promotion_end_date', '=' , null)
                    ->where('tbl_product.publish','=', 1)
                    ->orwhere('product_price_promotion', '=', 1)
                    ->where('tbl_brand_code_product.brandcode_id', $brand_product_id)->whereNotIn('tbl_product.meta_slug', [$meta_slug])->limit('3')
                    ->orderby('product_id', 'asc')->get();
            } else {

                return redirect('trang-chu');
            }

            //LẤY SẢN PHẨM THEO DANH MỤC
            $show_product = DB::table('tbl_product')
                ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
                ->join('tbl_brand_code_product', 'tbl_brand_code_product.code_id', '=', 'tbl_product.brandcode_id')
                ->where('tbl_product.category_id', $category_product_id)
                ->where('tbl_product.publish','=', 1)->limit(5)->get();
            $reviewModel = ReviewModel::where('product_id', $show_details_product[0]->product_id)->limit(4)->orderby('Rid', 'desc')->get();

            return view('user.details_product.details_product')
                ->with('details_product', $show_details_product)
                ->with('show_details_product_recommended', $show_details_product_recommended)
                ->with('related_product', $related_product)
                ->with('show_product', $show_product)

                //SEO
                ->with('meta_desc', $meta_desc)
                ->with('meta_keyword', $meta_keyword)
                ->with('meta_title', $meta_title)
                ->with('url_canonical', $url_canonical)
                ->with('reviewModel', $reviewModel);
        }

        // Insert comment from customer
        public function insertComment(request $request)
        {
            if (empty($request['pid']) || !is_numeric($request['pid']))
            {
                Session::put('alert', "<div style='color:red'> Viết Bình Luận Thất Bại</div>");

                return back();
            }

            $reviewModel = new ReviewModel();

            $reviewModel->Rname = stripcslashes($request['name']);
            $reviewModel->Remail = stripcslashes($request['email']);
            $reviewModel->Rcomment = stripcslashes($request['comment']);
            $reviewModel->status = 0;
            $reviewModel->product_id = stripcslashes($request['pid']);

            if (empty($request['name'] && $request['email'] && $request['comment']))
            {

                Session::put('alert', "<div style='color:red'> bạn không được để trống ở bất kì mục nào</div>"); //admin_Id trong dbs`

                return back();

            }
            else
            {
                if (!$reviewModel->save())
                {
                    return back();
                }

                return back();
            }
        }
    }
