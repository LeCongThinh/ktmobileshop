@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>KTMobile.vn - Chi tiết giỏ hàng</title>
    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" type="image/x-icon" href="../../../assets_admin/img/logo/icon_dashboard.png" />
    <link rel="stylesheet" href="../../assets_client/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../assets_client/css/LineIcons.3.0.css" />
    <link rel="stylesheet" href="../../assets_client/css/main.css" />
    <link href="../../../assets_admin/vendor/bootstrap-touchspin/css/jquery.bootstrap-touchspin.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


</head>
<style>
    .quantity-input {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 120px;
        /* Độ rộng của input */
    }

    .quantity-input input[type="number"] {
        width: 60px;
        /* Độ rộng của input number */
        text-align: center;
        /* Canh giữa nội dung của input */
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .quantity-input span {
        cursor: pointer;
        padding: 5px 10px;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .quantity-input span:hover {
        background-color: #e0e0e0;
    }
</style>

<body>
    <header class="header navbar-area">
        <div class="topbar">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-4 col-12" style="margin-left: 70%">
                        <div class="top-end">
                            <div class="user">
                                @auth
                                    @if (Auth::user()->avatar)
                                        <img style="width: 30px; border-radius: 20px;"
                                            src="../../storage/{{ Auth::user()->avatar }}" alt="">

                                        <img style="width: 30px; border-radius: 20px; margin-right: 5px"
                                            src="{{ Auth::user()->avatar }}" alt="">
                                    @else
                                        <i class="lni lni-user"></i>
                                    @endif
                                @else
                                    <i class="lni lni-user"></i>

                                @endauth

                                @auth
                                    @if (Auth::user()->name)
                                        {{ Auth::user()->name }}
                                    @else
                                        Xin chào!
                                    @endif
                                @else
                                    Xin chào!
                                @endauth
                            </div>

                            @auth
                                <a href="{{ route('showProfile') }}" class="btn btn-outline-light mb-1"
                                    style="margin-right: 5px">
                                    <span class="text">Thông tin cá nhân</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger mb-1 logout-button">Đăng
                                        xuất</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary mb-1" style="margin-right: 5px">Đăng
                                    nhập</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary mb-1">Đăng ký</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-middle">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-3 col-7">
                        <a class="navbar-brand" href="{{ route('homepage') }}">
                            <h3>KTMobile Shop</h3>
                        </a>
                    </div>
                    <div class="col-lg-5 col-md-7 d-xs-none">
                        <form action="" method="GET" id="search-form">
                            <div class="col-lg-5 col-md-7 d-xs-none" style="width: 100%;">
                                {{-- Tìm kiếm sản phẩm --}}
                                <div class="main-menu-search">
                                    <div class="navbar-search search-style-5">
                                        <div class="search-input">
                                            <input type="text" id="search-input" name="query"
                                                placeholder="Bạn muốn tìm gì ?" oninput="updateFormAction()">
                                        </div>
                                        <div class="search-btn">
                                            <button type="submit"><i class="lni lni-search-alt"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4 col-md-8 col-2">
                        <div class="middle-right-area">
                            <div class="nav-hotline">
                                <i class="lni lni-phone"></i>
                                <h3>Hotline:
                                    <span>(+84) 779 621 333</span>
                                </h3>
                            </div>
                            <div class="navbar-cart">
                                <a href="{{ route('formCheckOrder') }}" class="btn btn-outline-secondary mb-1"
                                    style="margin-right: 10px;">Tra cứu đơn hàng</a>
                                <div class="cart-items">
                                    <a href="{{ route('indexCart') }}" class="main-btn">
                                        <i class="lni lni-cart"></i>
                                        <span class="total-items-cart">{{ session('cartItemsCount', 0) }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <br>

    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 col-12">
                <div class="nav-inner" style="display: flex;justify-content: flex-start;align-items: center;">
                    <li class="breadcrumb-item active" style="list-style-type: none;"><a
                            href="{{ route('homepage') }}"><i class="lni lni-home"></i> Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item " style="list-style-type: none;">Chi tiết giỏ hàng</li>
                </div>
            </div>
            {{-- error: #FF6969 || success: #74E291 --}}
            {{-- Success --}}
            @if (session('success'))
                <div class="col-lg-6 col-md-6 col-12">
                    <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert"
                        style="background-color: #74E291; font-size: 17px; color: #059212">
                        <b>Thành công</b>
                        <br><span>{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endif
            {{-- Error --}}
            @if (session('error'))
                <div class="col-lg-6 col-md-6 col-12">
                    <div id="error-alert" class="alert alert-success alert-dismissible fade show" role="alert"
                        style="background-color: #FF6969; font-size: 17px; color: #910909">
                        <b>Không thành công</b>
                        <br><span>{{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="container">
        <div class="text-left" style="margin-top: 10px; margin-left: 120px;">
            <a href="{{ route('homepage') }}" style="font-size: 16px;">&larr; Mua thêm sản phẩm khác</a>
        </div>
        <div class="top-area">

            <form action="{{ route('order.store') }}" method="POST">
                @csrf
                <div class="row align-items-center">
                    <!-- DataTable with Hover -->
                    <div class="col-lg-20 col-12 custom-padding-right">
                        <div class="card mb-4" style="margin-left: 9%; margin-right: 9%; margin-top: 20px">
                            <div class="table-responsive p-3" style="border: 1px solid #cacaca;">
                                <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 12%;">Ảnh sản phẩm</th>
                                            <th style="width: 30%;">Tên sản phẩm</th>
                                            <th style="width: 15%;">Số lượng</th>
                                            <th style="width: 13%;">Giá</th>
                                            <th style="width: 13%;">Tổng cộng</th>
                                            <th style="width: 25%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-items">
                                        @foreach (Cart::content() as $item)
                                            <tr data-rowid="{{ $item->rowId }}">
                                                <td>
                                                    @if ($item->options->image && File::exists(public_path('storage/' . $item->options->image)))
                                                        <img src="{{ asset('storage/' . $item->options->image) }}"
                                                            style="width: 50px; height: 50px; object-fit: contain;">
                                                    @else
                                                        <img src="{{ $item->options->image }}"
                                                            style="width: 50px; height: 50px; object-fit: contain;">
                                                    @endif
                                                </td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    <div class="quantity-input">
                                                        <span class="minus">-</span>
                                                        <input type="number" name="quantity"
                                                            value="{{ $item->qty }}" min="1"
                                                            class="quantity" max="5"
                                                            style="width: 60px; text-align:center;" readonly>
                                                        <span class="plus">+</span>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                                <td class="subtotal">{{ number_format($item->subtotal, 0, ',', '.') }}
                                                    đ
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-danger btn-sm remove-from-cart"
                                                        data-rowid="{{ $item->rowId }}">Xóa</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="product-details-info" style="margin-bottom: 100px;">
                        <div class="row">
                            <div class="col-lg-6 col-12" style="margin-left: 9%;">
                                <div class="single-block" style="border: 1px solid #cacaca;">
                                    <div class="info-body custom-responsive-margin">
                                        <h4>Thông tin khách hàng</h4>

                                        <div style="margin-top: 5px; color: #333;">
                                            <b for="name" class="col-sm-1 col-form-label">Họ tên</b>
                                            <div class="col-sm-15">
                                                <input type="text" class="form-control" id="name"
                                                    name="username"
                                                    value="{{ old('username', Auth::check() ? Auth::user()->name : '') }}"
                                                    placeholder="Họ tên...">
                                                <div style="color: red">
                                                    @if ($errors->has('username'))
                                                        {{ $errors->first('username') }}<br>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div style="margin-top: 5px; color: #333;">
                                            <b for="phone" class="col-sm-1 col-form-label">Số điện thoại</b>
                                            <div class="col-sm-15">
                                                <input type="text" class="form-control" id="phone"
                                                    name="phone"
                                                    value="{{ old('phone', Auth::check() ? Auth::user()->phone : '') }}"
                                                    placeholder="Số điện thoại...">
                                                <div style="color: red">
                                                    @if ($errors->has('phone'))
                                                        {{ $errors->first('phone') }}<br>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div style="margin-top: 5px; color: #333;">
                                            <b for="email" class="col-sm-1 col-form-label">Email</b>
                                            <div class="col-sm-15">
                                                <input type="text" class="form-control" id="email"
                                                    name="email"
                                                    value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}"
                                                    placeholder="Email...">
                                                <div style="color: red">
                                                    @if ($errors->has('email'))
                                                        {{ $errors->first('email') }}<br>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <fieldset class="form-group" style="margin-top: 5px">
                                            <div class="d-flex align-items-center" style="color: #333">
                                                <b style="margin-right: 10px; font-size: 15px;">Cách thức nhận hàng</b>
                                                <div class="custom-control custom-radio" style="margin-right: 10px;">
                                                    <input type="radio" id="customRadio1" name="delivery"
                                                        value="home" class="custom-control-input"
                                                        {{ old('delivery') == 'home' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="customRadio1">Giao hàng
                                                        tận
                                                        nơi</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio2" name="delivery"
                                                        value="shop" class="custom-control-input"
                                                        {{ old('delivery') == 'shop' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="customRadio2">Nhận tại
                                                        cửa
                                                        hàng</label>
                                                </div>
                                            </div>
                                            <div style="color: red; margin-left: 20px;">
                                                @if ($errors->has('gender'))
                                                    {{ $errors->first('gender') }}<br>
                                                @endif
                                            </div>
                                        </fieldset>

                                        <div id="delivery-details" style="display: none;">
                                            <div style="margin-top: 5px; color: #333">
                                                <b for="exampleFormControlSelect1"
                                                    class="col-sm-2 col-form-label">Tỉnh/thành
                                                    phố</b>
                                                <div class="col-sm-15">
                                                    <select class="form-control" name="province_id"
                                                        id="selectProvinces">
                                                        <option value="">----Chọn tỉnh/thành phố----</option>
                                                        @if (!@empty($provinces))
                                                            @foreach ($provinces as $province)
                                                                <option value="{{ $province->id }}">
                                                                    {{ $province->name }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <div style="color: red;">
                                                        @if ($errors->has('province_id'))
                                                            {{ $errors->first('province_id') }}<br>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="margin-top: 5px; color: #333">
                                                <b for="exampleFormControlSelect1"
                                                    class="col-sm-2 col-form-label">Quận/huyện</b>
                                                <div class="col-sm-15">
                                                    <select class="form-control" name="district_id"
                                                        id="selectDistricts">
                                                        <option value="">----Chọn quận/huyện----</option>
                                                        @if (!@empty($districts))
                                                            @foreach ($districts as $district)
                                                                <option value="{{ $district->id }}"
                                                                    {{ old('district_id', $user->district_id) == $district->id ? 'selected' : '' }}>
                                                                    {{ $district->name }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <div style="color: red;">
                                                        @if ($errors->has('district_id'))
                                                            {{ $errors->first('district_id') }}<br>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="margin-top: 5px; color: #333">
                                                <b for="exampleFormControlSelect1"
                                                    class="col-sm-2 col-form-label">Phường/xã</b>
                                                <div class="col-sm-15">
                                                    <select class="form-control" name="ward_id" id="selectWards">
                                                        <option value="">----Chọn phường/xã----</option>
                                                        @if (!@empty($wards))
                                                            @foreach ($wards as $ward)
                                                                <option value="{{ $ward->id }}"
                                                                    {{ old('ward_id', $user->ward_id) == $ward->id ? 'selected' : '' }}>
                                                                    {{ $ward->name }}
                                                                </option>
                                                            @endforeach

                                                        @endif
                                                    </select>
                                                    <div style="color: red;">
                                                        @if ($errors->has('ward_id'))
                                                            {{ $errors->first('ward_id') }}<br>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="margin-top: 5px; color: #333">
                                                <b for="exampleFormControlTextarea1"
                                                    class="col-sm-2 col-form-label">Địa
                                                    chỉ</b>
                                                <div class="col-sm-15">
                                                    <textarea class="form-control" name="address" id="exampleFormControlTextarea1" rows="3"
                                                        placeholder="Địa chỉ...">{{ old('address') }}</textarea>
                                                </div>
                                                <div style="color: red; margin-left: 18%;">
                                                    @if ($errors->has('address'))
                                                        {{ $errors->first('address') }}<br>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <div class="single-block"
                                    style="border: 1px solid #cacaca;>
                                    <div class="info-body">
                                    <h6 class="total-items-order">Tổng cộng {{ session('cartItemsCount', 0) }} sản
                                        phẩm</h6>
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush table-hover"
                                            id="dataTableHover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>THÀNH TIỀN: </th>
                                                    <th id="total"><strong
                                                            style="font-size: 20px">{{ Cart::subtotal(0, ',', '.') }}
                                                            đ</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td style="font-style: italic;">
                                                        (Đã bao gồm VAT)
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                        <button type="submit"
                                            id="view-more-specs-btn"class="btn btn-primary btn-icon-split"
                                            style="width: 100%; margin-top: 20px; border-radius: 10px;">
                                            <span class="icon text-white-100">
                                                <i class="lni lni-cart-full"></i>
                                            </span>
                                            <span class="text">Đặt hàng</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================= scroll-top ========================= -->
    <a href="#" class="scroll-top">
        <i class="lni lni-chevron-up"></i>
    </a>

    <script src="../../assets_client/js/bootstrap.min.js"></script>
    <script src="../../assets_client/js/tiny-slider.js"></script>
    <script src="../../assets_client/js/glightbox.min.js"></script>
    <script src="../../assets_client/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            function toggleDeliveryDetails() {
                if ($("input[name='delivery']:checked").val() == 'home') {
                    $("#delivery-details").show();
                } else {
                    $("#delivery-details").hide();
                }
            }

            // Gọi hàm khi trang được tải lần đầu
            toggleDeliveryDetails();

            // Gọi hàm khi người dùng thay đổi lựa chọn
            $("input[name='delivery']").change(function() {
                toggleDeliveryDetails();
            });

            //select districts from provinces
            $("#selectProvinces").change(function() {
                var province_id = $(this).val();
                if (province_id == "") {
                    var province_id = 0;
                }
                $.ajax({
                    url: '{{ url('/fetch-districts/') }}/' + province_id,
                    type: 'post',
                    dataType: "json",
                    success: function(response) {
                        //console.log(response['districts'].length);

                        //console.log(response['districts'].length);
                        $('#selectDistricts').find('option:not(:first)').remove();
                        $('#selectWards').find('option:not(:first)').remove();

                        if (response['districts'].length > 0) {
                            $.each(response['districts'], function(key, value) {
                                $("#selectDistricts").append("<option value='" + value[
                                    'id'] + "'>" + value['name'] + "</option>");
                            });
                        }
                    }
                });
            });

            $("#selectDistricts").change(function() {
                var district_id = $(this).val();
                if (district_id == "") {
                    var district_id = 0;
                }
                $.ajax({
                    url: '{{ url('/fetch-wards/') }}/' + district_id,
                    type: 'post',
                    dataType: "json",
                    success: function(response) {
                        $('#selectWards').find('option:not(:first)').remove();
                        if (response['wards'].length > 0) {
                            $.each(response['wards'], function(key, value) {
                                $("#selectWards").append("<option value='" + value[
                                    'id'] + "'>" + value['name'] + "</option>");
                            });
                        }
                    }
                });
            });
            //set thời gian thông báo
            setTimeout(function() {
                $("#success-alert").alert('close'); // Đóng alert sau 2 giây
            }, 2000);
            setTimeout(function() {
                $("#error-alert").alert('close'); // Đóng alert sau 2 giây
            }, 2000);


            // Hàm cập nhật tổng tiền
            function updateTotal() {
                var subtotalText = $("#total strong").text().replace(' đ', '').replace(/\./g, '');
                var subtotal = parseFloat(subtotalText);
                var vatRate = 0.08;
                var shippingFee = 50000;

                var vatAmount = subtotal * vatRate;
                var total = subtotal + vatAmount + shippingFee;

                var formattedTotal = new Intl.NumberFormat('vi-VN').format(total) + ' đ';
                $("#sum strong").text(formattedTotal);
            }

            // Gọi hàm cập nhật tổng tiền khi trang tải lần đầu
            updateTotal();

            // Cập nhật số lượng sản phẩm trong giỏ hàng bằng AJAX
            $(document).on('change', '.quantity', function() {
                var rowId = $(this).closest('tr').data('rowid');
                var quantity = $(this).val();

                $.ajax({
                    url: '{{ route('cart.update', ':rowId') }}'.replace(':rowId', rowId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quantity: quantity
                    },
                    success: function(response) {
                        $('#cart-message').html('<div class="alert alert-success">' + response
                            .success + '</div>');
                        updateCartItem(rowId, response.updatedItem);
                        updateCartTotal(response.total);
                    },
                    error: function(xhr) {
                        $('#cart-message').html(
                            '<div class="alert alert-danger">Error updating product quantity.</div>'
                        );
                    }
                });
            });

            $('.quantity-input .minus').click(function() {
                var $input = $(this).parent().find('input[type="number"]');
                var value = parseInt($input.val());
                var min = parseInt($input.attr('min')) || 1;

                if (value > min) {
                    $input.val(value - 1);
                    updateQuantity($input);
                    $('.total-items-cart').text(response.cartItemsCount);
                    $('.total-items-order').text('Tổng cộng ' + response.cartItemsCount + ' sản phẩm');
                }
            });

            $('.quantity-input .plus').click(function() {
                var $input = $(this).parent().find('input[type="number"]');
                var value = parseInt($input.val());
                var max = parseInt($input.attr('max')) || Infinity;

                if (value < max) {
                    $input.val(value + 1);
                    updateQuantity($input);
                    $('.total-items-cart').text(response.cartItemsCount);
                    $('.total-items-order').text('Tổng cộng ' + response.cartItemsCount + ' sản phẩm');
                }
            });

            function updateQuantity($input) {
                var rowId = $input.closest('tr').data('rowid');
                var quantity = $input.val();

                $.ajax({
                    url: '{{ route('cart.update', ':rowId') }}'.replace(':rowId', rowId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quantity: quantity
                    },
                    success: function(response) {
                        $('#cart-message').html('<div class="alert alert-success">' + response.success +
                            '</div>');
                        updateCartItem(rowId, response.updatedItem);
                        updateCartTotal(response.total);
                        $('.total-items-cart').text(response.cartItemsCount);
                        $('.total-items-order').text('Tổng cộng ' + response.cartItemsCount +
                            ' sản phẩm');
                    },
                    error: function(xhr) {
                        $('#cart-message').html(
                            '<div class="alert alert-danger">Error updating product quantity.</div>'
                        );
                    }
                });
            }


            // Xóa sản phẩm khỏi giỏ hàng bằng AJAX
            $(document).on('click', '.remove-from-cart', function() {
                var rowId = $(this).data('rowid');

                $.ajax({
                    url: '{{ route('cart.remove', ':rowId') }}'.replace(':rowId', rowId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#cart-message').html('<div class="alert alert-success">' + response
                            .success + '</div>');
                        removeCartItem(rowId);
                        updateCartTotal(response.total);
                        $('.total-items-cart').text(response.cartItemsCount);
                        $('.total-items-order').text('Tổng cộng ' + response.cartItemsCount +
                            ' sản phẩm');
                    },
                    error: function(xhr) {
                        $('#cart-message').html(
                            '<div class="alert alert-danger">Error removing product from cart.</div>'
                        );
                    }
                });
            });

            // Cập nhật thông tin sản phẩm trong giỏ hàng
            function updateCartItem(rowId, updatedItem) {
                var $tr = $('tr[data-rowid="' + rowId + '"]');

                // Kiểm tra và chuyển đổi thành số
                var qty = parseFloat(updatedItem.qty);
                var price = parseFloat(updatedItem.price);

                if (!isNaN(qty) && !isNaN(price)) {
                    // Cập nhật số lượng sản phẩm
                    $tr.find('.quantity').val(qty);

                    // Tính toán lại subtotal
                    var subtotal = qty * price;

                    // Định dạng số tiền VND với Intl.NumberFormat
                    var formattedSubtotal = new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(subtotal);

                    $tr.find('.subtotal').text(formattedSubtotal);

                    // Gọi lại hàm cập nhật tổng giá trị giỏ hàng
                    updateCartTotal();
                } else {
                    console.error('Dữ liệu số lượng hoặc giá không hợp lệ.');
                    // Xử lý lỗi nếu cần thiết
                }
            }

            // Hàm cập nhật tổng giá trị giỏ hàng
            function updateCartTotal() {
                var total = 0;
                // Lặp qua từng sản phẩm trong giỏ hàng để tính tổng
                $('tr').each(function() {
                    var subtotalStr = $(this).find('.subtotal').text();
                    // Lấy giá trị số từ chuỗi định dạng tiền tệ VND
                    var subtotal = parseFloat(subtotalStr.replace(/\D/g,
                        '')); // Xóa hết ký tự không phải số

                    if (!isNaN(subtotal)) {
                        total += subtotal;
                    }
                });

                // Định dạng lại số tiền VND cho total
                var formattedTotal = new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(total);

                // Cập nhật nội dung của phần tổng giá trị giỏ hàng
                $('#total').html('<strong style="font-size: 20px">' + formattedTotal + '</strong>');
            }

            // Xóa sản phẩm khỏi giỏ hàng
            function removeCartItem(rowId) {
                $('tr[data-rowid="' + rowId + '"]').remove();
            }
        });
        //Tìm kiếm sản phẩm
        function updateFormAction() {
            const input = document.getElementById('search-input');
            const form = document.getElementById('search-form');
            form.action = "{{ url('/san-pham/search/keyword') }}/" + encodeURIComponent(input.value);
        }
    </script>
</body>

</html>
