@extends('clients.app-client')
@section('title', 'KTMobile.vn - ' . $product->name)
@section('header-route')
    @parent
    <li class="breadcrumb-item active" style="list-style-type: none;"><a href="{{ route('homepage') }}"><i
                class="lni lni-home"></i> Trang chủ</a></li>
    <li class="breadcrumb-item " style="list-style-type: none;"><a
            href="{{ route('showCategory', $category->slug) }}">{{ $category->name }}</a></li>
    <li class="breadcrumb-item " style="list-style-type: none;"><a
            href="{{ route('showSubCategory', [$category->slug, $subcategory->slug]) }}">{{ $subcategory->name }}</a></li>
    <li class="breadcrumb-item " style="list-style-type: none;">{{ $product->name }}</li>
@endsection
@section('client-content-pages')
    <section class="item-details section">
        <div class="container">
            <div class="top-area">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="product-images">
                            <main id="gallery">
                                <div class="main-img">
                                    <img style="margin: 0px 20%; width: 65%; height: auto; object-fit: contain;"
                                        src="{{ $product->image }}" id="current" alt="#">
                                </div>
                                <div class="images">
                                    <div class="swiper-wrapper">
                                        @foreach ($product->images as $image)
                                            <div class="swiper-slide">
                                                <img style="width: 150px; height: auto;"
                                                    src="{{ asset('storage/' . $image->image_path) }}" class="img"
                                                    alt="#">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </main>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="single-product" style="border: none;">
                            <div class="product-info">
                                <h2 class="title">{{ $product->name }}</h2>
                                <p class="category"><i class="lni lni-tag"></i>{{ $product->subcategory->name }}</p>
                                <ul class="review">
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><span>(4.6/5) đánh giá</span></li>
                                </ul>
                                <h3 class="price" id="selected-variant-price" style="color: darkblue">
                                    {{ number_format($product->details->first()->sale_price, 0, ',', '.') }}
                                    đ
                                    @if ($product->price && $product->price != 0)
                                        <span style="font-size: 15px">{{ number_format($product->price, 0, ',', '.') }}
                                            đ</span>
                                    @endif
                                </h3>
                                <p style="font-style: italic; font-size: 17px;"><b>Chọn màu bạn muốn xem: </b></p>
                                <div class="row">
                                    @foreach ($product->details as $index => $variant)
                                        <div class="col-lg-4 col-md-4 col-12">
                                            <div id="color-variant-{{ $index }}" class="color-variant"
                                                style="font-size: 17px; color: black; display: flex; justify-content: center; align-items: center; margin-top: 10px; border: {{ $index === 0 ? '2px solid #019fb3' : '2px solid rgb(228, 228, 228)' }}; border-radius: 10px; cursor: pointer;"
                                                onclick="selectVariant('{{ $variant->id }}', '{{ $variant->color }}', '{{ $variant->sale_price }}', '{{ asset('storage/' . $variant->attribute_image) }}', '{{ $product->name }}')">

                                                <img src="{{ asset('storage/' . $variant->attribute_image) }}"
                                                    style="height: 43px; margin-top: 15px;" class="mb-3">
                                                <div
                                                    style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                                    <b>{{ $variant->color }}</b>
                                                    <b>{{ number_format($variant->sale_price, 0, ',', '.') }}</b>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                {{-- Mua hàng --}}
                                <div class="bottom-content">
                                    <div class="row align-items-end">
                                        <div class="col-lg-12 col-md-4 col-12">
                                            <div class="button cart-button" style="margin-bottom: 10px">
                                                <button class="btn" id="buy-now-btn" style="width: 100%;">Mua
                                                    ngay</button>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-4 col-12">
                                            <div class="wish-button">
                                                <form action="{{ route('cart.add') }}" method="POST"
                                                    class="add-to-cart-form">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                                    <input type="hidden" name="name"
                                                        value="{{ $product->name }} - {{ $product->details->first()->color }}">
                                                    @if ($product->details->isNotEmpty())
                                                        <input type="hidden" name="price"
                                                            id="selected-variant-price-input"
                                                            value="{{ $product->details->first()->sale_price }}">
                                                    @endif
                                                    <input type="hidden" name="qty" value="1">
                                                    <input type="hidden" name="image" id="selected-variant-image"
                                                        value="{{ asset('storage/' . $product->details->first()->attribute_image) }}">
                                                    <button type="submit" class="btn"><i class="lni lni-cart"></i>
                                                        Thêm vào giỏ hàng</button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="bottom-content">
                                    <div class="row align-items-end">
                                        <hr>
                                        <div class="col-lg-10 col-md-4 col-12">
                                            <i class="lni lni-mobile" style="font-size: 20px;"></i>
                                            <span style="margin-left: 10px; font-size: 17px">Sản phẩm là máy mới 100% , đầy
                                                đủ phụ kiện.</span>
                                        </div>
                                        <div class="col-lg-10 col-md-4 col-12" style="margin-top: 10px">
                                            <i class="lni lni-delivery" style="font-size: 20px;"></i>
                                            <span style="margin-left: 10px; font-size: 17px">Giao hàng miễn phí</span>
                                        </div>
                                        <div class="col-lg-12 col-md-4 col-12" style="margin-top: 10px">
                                            <i class="lni lni-dropbox" style="font-size: 20px;"></i>
                                            <span style="margin-left: 10px; font-size: 17px">Bộ sản phẩm: Hộp, máy, cáp,
                                                sách hướng dẫn</span>
                                        </div>
                                        <div class="col-lg-12 col-md-4 col-12" style="margin-top: 10px">
                                            <i class="lni lni-protection" style="font-size: 20px;"></i>
                                            <span style="margin-left: 10px; font-size: 17px">Bảo hành 12 tháng tại trung
                                                tâm
                                                bảo hành chính hãng</span>
                                        </div>
                                        <div class="col-lg-12 col-md-4 col-12" style="margin-top: 10px">
                                            <i class="lni lni-money-protection" style="font-size: 20px;"></i>
                                            <span style="margin-left: 10px; font-size: 17px">Thanh toán trực tiếp hoặc qua
                                                thẻ tín dụng Visa, Master, JCB.</span>
                                        </div>
                                        <div class="col-lg-12 col-md-4 col-12" style="margin-top: 10px">
                                            <i class="lni lni-tag" style="font-size: 20px;"></i>
                                            <span style="margin-left: 10px; font-size: 17px">Giá bán đã bao gồm thuế (Xuất
                                                V.A.T) </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-details-info">
                <div class="row">
                    <div class="col-lg-8 col-12">
                        <div class="single-block">
                            <div class="info-body custom-responsive-margin">
                                <h4>Thông tin sản phẩm</h4>
                                <div class="product-description">
                                    {!! $product->description !!}
                                </div>
                                <button id="read-more-btn" class="btn btn-outline-primary"
                                    style="width: 100%; margin-top: 20px">Xem thêm <i
                                        class="lni lni-angle-double-down"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12">
                        <div class="single-block">
                            <div class="info-body">
                                <h6>Cấu hình {{ $product->name }}</h6>
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 100px">Cấu hình</th>
                                                <th>Thông số</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($product->specifications as $index => $spec)
                                                <tr class="spec-row {{ $index >= 8 ? 'd-none' : '' }}">
                                                    <td>{{ $spec->title }}</td>
                                                    <td>{{ $spec->content }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button id="view-more-specs-btn"class="btn btn-secondary btn-icon-split"
                                        style="width: 100%; margin-top: 20px; border-radius: 10px;">
                                        <span class="icon text-white-100">
                                            <i class="lni lni-plus"></i>
                                        </span>
                                        <span class="text">Xem cấu hình chi tiết</span>
                                    </button>
                                    <button id="hide-more-specs-btn" class="btn btn-secondary btn-icon-split d-none"
                                        style="width: 100%; margin-top: 20px; border-radius: 10px;">
                                        <span class="icon text-white-100">
                                            <i class="lni lni-minus"></i>
                                        </span>
                                        <span class="text">Thu gọn</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('storeComment', $product->id) }}">
                        @csrf
                        <div class="col-lg-20 col-12 custom-padding-right">
                            <div class="single-product">
                                <h5 style="font-weight: bold">Hỏi đáp {{ $product->name }}</h5><br>
                                <div class="col-sm-20">
                                    <textarea class="form-control" name="content" id="exampleFormControlTextarea1" rows="5"
                                        placeholder="Mời bạn thảo luận, vui lòng nhập tiếng việt có dấu"></textarea>
                                    <div style="color: red">
                                        @if ($errors->has('content'))
                                            {{ $errors->first('content') }}<br>
                                        @endif
                                    </div>
                                </div>
                                <h6 style="margin-top: 20px;margin-bottom: 5px;">Nhập thông tin của bạn</h6>
                                <div class="col-sm-20">
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}"
                                        placeholder="Họ tên...">
                                    <div style="color: red">
                                        @if ($errors->has('name'))
                                            {{ $errors->first('name') }}<br>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-20" style="margin-top: 10px">
                                    <input type="text" class="form-control" id="email" name="email"
                                        value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}"
                                        placeholder="Email...">
                                    <div style="color: red">
                                        @if ($errors->has('email'))
                                            {{ $errors->first('email') }}<br>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right mt-4 b" style="text-align: right ">
                                    <button class="btn btn-warning"> <i class="lni lni-telegram-plane"></i> Gửi bình luận
                                    </button>
                                </div><br>
                            </div>
                        </div>
                    </form>

                    {{-- Hiển thị bình luận đã duyệt --}}
                    <div class="col-lg-20 col-12 custom-padding-right">
                        <div class="single-product">
                            @foreach ($comment as $cmt)
                                <div class="user" style="font-size: 15px; font-weight: bold">
                                    <div style="display: flex; align-items: center;">
                                        <img class="img-profile rounded-circle" src="../../../image/user_default.png"
                                            style="max-width: 40px;border: 1px solid black;">
                                        <h6 style="margin:10px">{{ $cmt->name }}</h6>
                                    </div>
                                    <p style="margin-top: 10px">
                                        {{ $cmt->content }}
                                    </p>
                                </div>
                                - Ngày gửi: 04/06/2024
                                <div class="user"
                                    style="font-size: 15px; font-weight: bold; margin-top: 10px; margin-bottom: 10px;margin-left: 60px; background-color:rgb(225, 223, 223); border-radius: 10px;">
                                    <div style="display: flex; align-items: center;">
                                        <img class="img-profile rounded-circle" src="../../../assets_admin/img/boy.png"
                                            style="max-width: 40px;border: 1px solid black; margin-left: 10px;margin-top: 10px;">
                                        <h6 style="margin-top:10px;margin-left:10px;">Quản trị viên</h6>

                                    </div>
                                    <p style="margin-top: 10px; margin-left: 10px">{{ $cmt->reply }}</p>
                                    <p style="margin-top: 10px;margin-bottom: 10px; margin-left: 10px">- Ngày gửi:
                                        04/06/2024
                                    </p>
                                </div>
                                <hr style="color: black">
                            @endforeach
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .color-variant {
            border: 2px solid rgb(228, 228, 228);
        }

        .color-variant.selected {
            border: 2px solid #019fb3;
        }
    </style>
    <script type="text/javascript">
        const current = document.getElementById("current");
        const opacity = 0.6;
        const imgs = document.querySelectorAll(".img");
        imgs.forEach(img => {
            img.addEventListener("click", (e) => {
                //reset opacity
                imgs.forEach(img => {
                    img.style.opacity = 1;
                });
                current.src = e.target.src;
                //adding class 
                //current.classList.add("fade-in");
                //opacity
                e.target.style.opacity = opacity;
            });
        });
        //Nút xem thêm bài viết
        document.addEventListener("DOMContentLoaded", function() {
            var description = document.querySelector('.product-description');
            var btn = document.getElementById('read-more-btn');
            var originalHeight = description.clientHeight; // Lưu chiều cao ban đầu

            btn.addEventListener('click', function() {
                if (description.classList.contains('expanded')) {
                    // Nếu đã mở rộng, thu lại
                    description.style.maxHeight = originalHeight + 'px'; // Sử dụng chiều cao ban đầu
                    description.classList.remove('expanded');
                    btn.innerHTML = 'Xem thêm <i class="lni lni-angle-double-down"></i>';
                } else {
                    // Nếu chưa mở rộng, mở rộng
                    description.style.maxHeight = description.scrollHeight +
                        'px'; // Sử dụng chiều cao đầy đủ
                    description.classList.add('expanded');
                    btn.innerHTML = 'Thu gọn <i class="lni lni-angle-double-up"></i>';
                }
            });

            //Nút xem cấu hình chi tiết
            var viewMoreSpecsBtn = document.getElementById('view-more-specs-btn');
            var hideMoreSpecsBtn = document.getElementById('hide-more-specs-btn');
            viewMoreSpecsBtn.addEventListener('click', function() {
                var rows = document.querySelectorAll('.spec-row.d-none');
                rows.forEach(function(row) {
                    row.classList.remove('d-none');
                });
                viewMoreSpecsBtn.style.display = 'none'; // Ẩn nút xem chi tiết
                hideMoreSpecsBtn.classList.remove('d-none'); // Hiển thị nút thu gọn
            });
            hideMoreSpecsBtn.addEventListener('click', function() {
                var rows = document.querySelectorAll('.spec-row');
                rows.forEach(function(row, index) {
                    if (index >= 8) {
                        row.classList.add('d-none');
                    }
                });
                hideMoreSpecsBtn.classList.add('d-none'); // Ẩn nút thu gọn
                viewMoreSpecsBtn.style.display = 'block'; // Hiển thị nút xem chi tiết
            });
        });

        document.getElementById('buy-now-btn').addEventListener('click', function() {
            var form = document.getElementById('add-to-cart-form');
            var formData = new FormData(form);

            // Gửi AJAX request
            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Chuyển hướng đến trang indexCart sau khi thêm thành công
                        window.location.href = '{{ route('indexCart') }}';
                    } else {
                        // Xử lý khi thêm vào giỏ hàng thất bại (nếu cần)
                        alert('Có lỗi xảy ra. Vui lòng thử lại.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                });
        });
        document.addEventListener('DOMContentLoaded', (event) => {
            const variants = document.querySelectorAll('.color-variant');

            variants.forEach(variant => {
                variant.addEventListener('click', () => {
                    // Xóa viền khỏi tất cả các phần tử
                    variants.forEach(v => v.style.border = '2px solid rgb(228, 228, 228)');
                    // Thêm viền vào phần tử được chọn
                    variant.style.border = '2px solid #019fb3';
                });
            });
        });

        function selectVariant(id, color, price, image, productName) {
            // Cập nhật hình ảnh chính với ảnh thuộc tính
            $('#current').attr('src', image);
            // Gọi Ajax để cập nhật thông tin sản phẩm
            $.ajax({
                url: '{{ route('product.variant') }}',
                type: 'GET',
                data: {
                    id: id,
                    productName: productName
                },
                success: function(response) {
                    // Cập nhật thông tin sản phẩm dựa trên phản hồi từ Ajax
                    $('#selected-variant-price').html(response.price);
                    $('#selected-variant-image-file').html(response.image);

                    $('#selected-variant-price-input').val(response.raw_price);
                    $('#selected-variant-image').val(response.image);
                    $('input[name="name"]').val(response.name);
                },
                error: function(xhr, status, error) {
                    console.error('Ajax request failed');
                }
            });
        }
    </script>


@endsection
