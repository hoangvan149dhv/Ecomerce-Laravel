<?php
    use Carbon\Carbon;
?>
@extends('admin.admin_layout')
@section('content')
<div class="table-agile-info">
    <div class="panel panel-default">
      <div class="panel-heading" style="margin-bottom:15px">
        Thông Tin Khách hàng
      </div>
          <div class="table-responsive" style="margin-bottom:55px">
            <table class="table table-striped b-t b-light table-hover" style="border:1px solid #eae6e6">
              <thead style="border:1px solid #eae6e6">
                <tr >
                  <th style="text-align:center;width:15%">Họ tên </th>
                  <th style="text-align:center;width:15%">SĐT</th>
                  <th style="text-align:center;width:20%">Địa chỉ</th>
                  <th style="text-align:center;width:30%">Ghi chú</th>
                  <th style="text-align:center;width:20%" >Ngày đặt</th>
                </tr>
              </thead>
              <tbody>
               @foreach ($infocustomerorder as $info)
                    <tr>
                  <td style="text-align:center">  {{$info->customer->cusname}}</td>
                  <td style="text-align:center">  {{$info->cusphone}}</td>
                  <td style="text-align:center">  {{$info->customer->cusadd}}</td>
                  <td style="text-align:center">  {{$info->note}}</td>
                  <td style="text-align:center">  {{Carbon::createFromFormat('Y-m-d H:i:s', $info->order_date)->format('d/m/yy | H:i:s')}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <hr>
        {{-- ORDER INFO --}}
          <div class="panel panel-default">
            <div class="panel-heading" style="margin-bottom:15px">
              Thông Tin Đơn hàng
            </div>
      <div class="table-responsive">
        <table class="table table-striped b-t b-light table-hover" style="border:1px solid #eae6e6">
          <thead style="border:1px solid #eae6e6">
            <tr >
              <th style="text-align:center">Sản Phẩm</th>
              <th style="text-align:center">Hình Ảnh</th>
              <th style="text-align:center">Chất liệu</th>
              <th style="text-align:center">Số Lượng</th>
              <th style="text-align:center">Đơn giá</th>
              <th style="text-align:center">Phí giao hàng</th>
              <th style="text-align:center">Tổng</th>
              <th style="text-align:center">Tình Trạng</th>
            </tr>
          </thead>
          <tbody>
           @foreach ($infocustomerorder as $product)
            <tr>

              <td style="text-align:center">{{$product->productname}}</td>
              <td style="text-align:center"><img src="../public/upload/{{$product->image}}"width=80 height=110 alt=""></td>
              <td style="text-align:center">{{$product->productorder->product_material}}</td>
              <td style="text-align:center">  {{$product->soluong}}</td>
              <td style="text-align:center">  {{number_format($product->price)}}.VNĐ</td>
              <td style="text-align:center">  {{number_format($product->fee_ship)}}.VNĐ</td>
              <td style="text-align:center">  {{number_format($product->total)}}.VNĐ</td>
              @if ($product->status==1)
              <td style="text-align:center;background:#bbecc457">
                  <a href="{{ URL::to('/update-status-1/'.$product->orderid) }}" style="color:green;">Đã Giao Xong</a>
              @else
              <td style="text-align:center;background:#f0bcb470;">
                  <a href="{{ URL::to('/update-status-0/'.$product->orderid) }}" style="color:red">Đang Xử Lý</a>
              @endif</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <footer class="panel-footer">
        <div class="row">
        </div>
      </footer>
    </div>
  </div>
@endsection
