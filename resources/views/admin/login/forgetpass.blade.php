@extends('libraries.head')
@section('content')
<div class="log-w3">
<div class="w3layouts-main">
    <h2>Lấy mật khẩu</h2>
<form action="{{URL::to('/lay-mat-khau')}}" method="post">
            {{--BẢO MẬT HƠN, token --}}{{ csrf_field() }}
            <input type="text" class="ggg" name="name" placeholder="Tên Đăng Nhập" required="">
            <input type="text" class="ggg" name="question" placeholder="câu hỏi bảo mật" required="">
                <div class="clearfix"></div>
                <input type="submit" value="Lấy mật khẩu" name="getpass">
        </form>
        <p><a href="{{URL::to('/admin-login')}}">Trở về trang đăng nhập</a></p>
</div>
</div>
@endsection
