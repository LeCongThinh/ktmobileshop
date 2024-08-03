@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>KTMobile.vn - Thanh toán đơn hàng</title>
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
    .not-payment {
        background-color: #ffe4b5;
        font-size: 17px;
        color: #ff5100;
        border: 2px dashed #ff7b00;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: row;
        margin: 20px 0;
    }

    .payment-option {
        display: flex;
        align-items: center;
        margin-left: 3%;
        margin-top: 15px;
        font-size: 15px;
        color: black;
    }

    .payment-option img {
        margin-left: 5px;
        margin-right: 5px;
        /* Khoảng cách giữa ảnh và input */
        width: 25px;
        /* Điều chỉnh kích thước của ảnh */
        height: auto;
    }

    .payment-option label {
        display: flex;
        align-items: center;
    }

    .payment-option input[type="radio"] {
        margin-right: 5px;
        /* Khoảng cách giữa input và văn bản */
        vertical-align: middle;
        /* Căn giữa theo chiều dọc */
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

    <div class="container" style="margin-bottom: 200px">
        <div class="row align-items-center">
            {{-- error: #FF6969 || success: #74E291 --}}
            {{-- Success --}}
            <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cancelOrderModalLabel">Xác nhận hủy đơn hàng</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="font-size: 17px">
                            Bạn có chắc chắn muốn hủy đơn hàng này không?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-danger" id="confirmCancelOrder">Hủy đơn
                                hàng</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="payment" style="display:flex; justify-content: center">
                <div class="col-lg-7 col-md-6 col-12">
                    <div id="success-alert"
                        style="background-color: #87ff99; font-size: 17px; color: #00790c;display: flex; justify-content: center; align-items: center; flex-direction: row;">
                        <img src="../image/checked.png"
                            style="height: 43px; margin-top: 15px; margin-right: 2%; color:black" class="mb-3">
                        <b>ĐẶT HÀNG THÀNH CÔNG</b>
                    </div>
                    <div class="card">
                        <div class="user-info" style="margin: 20px 20px;">
                            <h6 style="margin-bottom: 5px">Cảm ơn vì đã mua hàng tại KTMobile</h6>
                            <div class="user" style="background-color:#EEEEEE; border-radius: 8px;">
                                <div style="display: flex; align-items: center;">
                                    <h6 style="margin-top:15px;margin-left:10px;">ĐƠN HÀNG:
                                        #<span>{{ $order->code }}</span>
                                    </h6>
                                </div>
                                <ul>
                                    <li style="margin-left: 20px;margin-top: 7px; color:#424242"><b>- Người nhận hàng:
                                        </b>
                                        <span style="font-size: 16px;">{{ $order->username }}</span>
                                    </li>
                                    <li style="margin-left: 20px;margin-top: 7px; color:#424242"><b>- Số điện thoại:
                                        </b>
                                        <span style="font-size: 16px;">{{ $order->phone }}</span>
                                    </li>
                                    <li style="margin-left: 20px;margin-top: 7px;color:#424242"><b>- Giao đến:
                                        </b>
                                        <span style="font-size: 16px;">
                                            @if ($order->address || $order->ward_name || $order->district_name || $order->province_name)
                                                {{ $order->address ?: '65 đường Huỳnh Thúc Kháng' }},
                                                {{ $order->ward_name ?: 'Phường Bến Nghé' }},
                                                {{ $order->district_name ?: 'Quận 1' }},
                                                {{ $order->province_name ?: 'TP. Hồ Chí Minh' }}, Việt Nam
                                            @else
                                                65 đường Huỳnh Thúc Kháng, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh,
                                                Việt
                                                Nam
                                            @endif
                                        </span>
                                    </li>
                                    <li style="margin-left: 20px;margin-top: 7px;color:#424242"><b>- Tổng tiền: </b>
                                        <span
                                            style="font-weight: bold; color: red; font-size: 16px;">{{ number_format($order->total, 0, ',', '.') }}
                                            đ</span>
                                    </li><br>
                                </ul>
                            </div>
                            <div class="not-payment">
                                <b style="margin: 10px 0;">Đơn hàng chưa được thanh toán</b>
                            </div>
                            <hr>
                            <h6>Danh sách sản phẩm: </h6>
                            <div id="info-order" style="padding: 15px;">
                                @foreach ($orderDetails as $detail)
                                    <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                        <!-- Ảnh sản phẩm -->
                                        <img src="{{ asset($detail->product_image) }}"
                                            style="height: 50px; width: 50px; object-fit: cover; margin-right: 10px;margin-top: 15px;"
                                            class="mb-3">
                                        <!-- Phần thông tin -->
                                        <div>
                                            {{ $detail->product_name }} <br>
                                            <b>Số lượng:</b> {{ $detail->quantity }}

                                        </div>
                                    </div>
                                    <hr style="width: 100%; margin: 1px 0;">
                                @endforeach
                            </div>

                            <p style="font-style: italic; font-size: 15px; margin-bottom: 10px">( <b>Thời gian nhận
                                    hàng:</b> Khoảng 5 ngày kể từ lúc thanh toán đơn hàng thành công. )</p>

                            <form id="paymentForm" method="POST" action="#">
                                @csrf
                                {{-- Lấy orderId và total truyền vào controller để thanh toán --}}
                                <input type="hidden" name="code" id="code" value="{{ $order->code }}">
                                <input type="hidden" name="amount" id="amount" value="{{ $order->total }}">
                                <input type="hidden" name="addInfo" id="addInfo"
                                    value="Thanh toan ma don hang {{ $order->code }}">
                                <input type="hidden" name="accountName" id="accountName"
                                    value="Thanh Toan Don Hang Ktmobile">
                                <div class="choose-payment-method">
                                    <h6>Chọn hình thức thanh toán: </h6>
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="cash_money">
                                        <img src="../image/pay.png" alt="Thanh toán khi nhận hàng">
                                        <span>Thanh toán trực tiếp khi nhận hàng</span>
                                    </label>
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="vietqr"
                                            id="vietqr_payment">
                                        <img src="../image/atm-card.png" alt="Thanh toán chuyển khoảng ngân hàng">
                                        <span>Qua thẻ ATM (có Internet Banking)</span>
                                    </label>
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="momo"
                                            id="momo_payment">
                                        <img src="../image/momo.png" alt="Thanh toán ví MoMo">
                                        <span>Ví MoMo</span>
                                    </label>
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="vnpay">
                                        <img src="../image/vnpay.png" style="width: 50px" alt="Thanh toán ví VNPay">
                                        <span>Thanh toán VNPay</span>
                                    </label>
                                </div>

                                <div class="text-center">
                                    <button type="button" class="btn btn-primary" id="btn_payment"
                                        style="margin-bottom: 5px;width: 100%;"><b>THANH TOÁN</b></button>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-outline-danger" id="btn_cancel_payment"
                                        style="margin-bottom: 50px;width: 100%;"><b>HỦY ĐƠN HÀNG</b></button>
                                </div>
                                <div class="text-center" id="vietqr_code_container" style="display: none;">
                                    <h3>QR Code</h3>
                                    <img id="vietqr_code" src="" alt="VietQR Code">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
        document.getElementById('btn_payment').addEventListener('click', function() {
            var paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            var form = document.getElementById('paymentForm');

            if (paymentMethod === 'momo') {
                form.action = '{{ route('momoPayment', ['orderId' => $order->code]) }}';
            } else if (paymentMethod === 'vnpay') {
                form.action = '{{ route('vnpayPayment', ['orderId' => $order->code]) }}';
            } else if (paymentMethod === 'vietqr') {
                const amount = document.getElementById('amount').value;
                const addInfo = document.getElementById('addInfo').value;
                const accountName = document.getElementById('accountName').value;
                const orderId = '{{ $order->code }}'; // Lấy orderId từ server-side rendering

                const qrUrl =
                    `https://img.vietqr.io/image/STB-070126855069-compact2.png?amount=${amount}&addInfo=${encodeURIComponent(addInfo)}&accountName=${encodeURIComponent(accountName)}`;

                // Chuyển hướng đến trang thanh toán VietQR code
                window.location.href =
                    `{{ route('showQRCode') }}?qrUrl=${encodeURIComponent(qrUrl)}&orderId=${orderId}`;
                return;
            } else if (paymentMethod === 'cash_money') {
                form.action = '{{ route('cashMoneyPayment') }}';
            }
            form.submit();
        });
        $(document).ready(function() {
            $('#btn_cancel_payment').click(function() {
                $('#cancelOrderModal').modal('show');
            });

            $('#confirmCancelOrder').click(function() {
                window.location.href = `{{ route('homepage') }}`;
                $('#cancelOrderModal').modal('hide');
            });
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
