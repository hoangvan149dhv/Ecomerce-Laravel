<?php

    namespace App\Http\Controllers\user;

    use App\Http\Controllers\user\HomeController;
    use Illuminate\Http\Request;
    use DB;
    use Session;
    use App\Http\Requests;
    use Illuminate\Support\Facades\Redirect;
    use App\Http\Model\contactinfoModel;

    class CategoryProduct extends HomeController
    {
        //SHOW SẢN PHẨM CỦA DAN MỤC
        public function show_category_home($category_id, request $request)
        {
            //category_name
            $category_name = DB::table('tbl_category_product')->where('category_id', $category_id)->limit('1')->get();

            //category_by_id
            $category_by_id = \App\Http\library\product_detail::getAllProduct()
                            ->where('tbl_category_product.category_id', $category_id)
                            ->paginate(12);
            // meta
            foreach ($category_name as $value) {
                //SEO
                $meta_desc = $value->category_desc;

                $meta_title = $value->category_name;

                $url_canonical = $request->url();
                ///SEO
            }
            return view('user.category.show_category')
                ->with('category_by_id', $category_by_id)
                ->with('category_name', $category_name)
                //SEO
                ->with('meta_desc', $meta_desc)
                ->with('meta_title', $meta_title)
                ->with('url_canonical', $url_canonical);
        }
    }
