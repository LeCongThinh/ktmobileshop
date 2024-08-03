@extends('clients.app-client')
@section('title', 'KTMobile.vn - ' . $category->name)
@section('header-route')
    @parent
    <li class="breadcrumb-item active" style="list-style-type: none;"><a href="{{ route('homepage') }}"><i
                class="lni lni-home"></i> Trang chủ</a></li>
    <li class="breadcrumb-item " style="list-style-type: none;"><a
            href="{{ route('showCategory', $category->slug) }}">{{ $category->name }}</a></li>
    <li class="breadcrumb-item " style="list-style-type: none;">{{ $subcategory->name }}</li>

@endsection
@section('client-content-pages')
    <!-- Content pages -->
    {{-- Slider khuyến mãi và quảng cáo --}}
    <section class="hero-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-20 col-12 custom-padding-right">
                    <div class="slider-head">
                        <div class="hero-slider">
                            <div class="single-slider"
                                style="background-image: url(../../assets_client/images/hero/banner_7.png);">
                            </div>
                            <div class="single-slider"
                                style="background-image: url(../../assets_client/images/hero/banner_2.png);">
                            </div>
                            <div class="single-slider"
                                style="background-image: url(../../assets_client/images/hero/banner_3.png);">
                            </div>
                            <div class="single-slider"
                                style="background-image: url(../../assets_client/images/hero/banner_4.png);">
                            </div>
                            <div class="single-slider"
                                style="background-image: url(../../assets_client/images/hero/banner_5.png);">
                            </div>
                            <div class="single-slider"
                                style="background-image: url(../../assets_client/images/hero/banner_10.png);">
                            </div>
                        </div>
                    </div>
                    <br>
                    @foreach ($category->subcategories->where('status', 1) as $subcate)
                        <a href="{{ route('showSubCategory', [$category->slug, $subcate->slug]) }}"
                            class="btn btn-outline-secondary mb-1">{{ $subcate->name }}</a>
                    @endforeach
                    <br>
                    <h5 style="font-weight: bold; margin-top:10px">{{ $subcategory->name }}</h5>
                </div>
            </div>
            <h5 style="margin-top: 10px">Lọc sản phẩm theo: </h5>
            <div class="text-left mt-4">
                <button class="btn btn-warning" onclick="sortProducts('price_asc')">Giá tăng dần
                    <i class="lni lni-arrow-up"></i>
                </button>
                <button class="btn btn-warning" onclick="sortProducts('price_desc')">Giá giảm dần
                    <i class="lni lni-arrow-down"></i>
                </button>
            </div><br>
        </div>
    </section>
    {{-- Load sản phẩm lên giao diện --}}
    <!-- Start Trending Product Area -->
    <section class="trending-product section" style="top: 0">
        <div class="container">
            <div class="row">
                {{-- Thẻ sản phẩm --}}
                <!-- Start Single Product -->
                @foreach ($listProduct as $p)
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="single-product" style="width: 290px; height: auto;">
                            <div class="product-image">
                                <span class="new-tag">Mới</span>
                                <a href="{{ route('showProduct', $p->slug) }}">
                                    <img style="margin: 35px 30px; width: 220px; max-height: 220px; object-fit: contain;"
                                        src="{{ $p->image }}" alt="#">
                                </a>
                                {{-- <div class="button">
                                    <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $p->id }}">
                                        <input type="hidden" name="name" value="{{ $p->name }}">
                                        <input type="hidden" name="price" value="{{ $p->sale_price }}">
                                        <input type="hidden" name="qty" value="1">
                                        <!-- Mặc định là 1, bạn có thể tùy chỉnh -->
                                        <input type="hidden" name="image" value="{{ $p->image }}">
                                        <button type="submit" class="btn"><i class="lni lni-cart"></i> Thêm</button>
                                    </form>
                                </div> --}}
                            </div>
                            <div class="product-info">
                                <h4 class="title">
                                    <a href="{{ route('showProduct', $p->slug) }}">{{ $p->name }}</a>
                                </h4>
                                <div class="price">
                                    @if ($p->details->isNotEmpty())
                                        <span>{{ number_format($p->details->first()->sale_price, 0, ',', '.') }}</span>
                                    @endif
                                    @if ($p->price && $p->price != 0)
                                        <span class="discount-price">{{ number_format($p->price, 0, ',', '.') }} đ</span>
                                    @endif
                                </div>
                                <ul class="review">
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- <div class="text-center mt-4">
                    <button class="btn btn-primary">Xem thêm 100 sản phẩm
                        <i class="lni lni-chevron-down"></i>
                    </button>
                </div><br> --}}

            </div>
        </div>
    </section>

    <!-- Start Shipping Info -->
    <section class="shipping-info">
        <div class="container">
            <ul>
                <!-- Free Shipping -->
                <li>
                    <div class="media-icon">
                        <i class="lni lni-delivery"></i>
                    </div>
                    <div class="media-body">
                        <h5>Giao hàng toàn quốc</h5>
                        <span>Miễn phí nội thành TP.HCM</span>
                    </div>
                </li>
                <!-- Money Return -->
                <li>
                    <div class="media-icon">
                        <i class="lni lni-support"></i>
                    </div>
                    <div class="media-body">
                        <h5>Dùng thử 7 ngày miễn phí.</h5>
                    </div>
                </li>
                <!-- Support 24/7 -->
                <li>
                    <div class="media-icon">
                        <i class="lni lni-protection"></i>
                    </div>
                    <div class="media-body">
                        <h5>Máy mới bảo hành 12 - 18 tháng</h5>
                        <span>Máy cũ bảo hành 6 tháng</span>
                    </div>
                </li>
                <!-- Safe Payment -->
                <li>
                    <div class="media-icon">
                        <i class="lni lni-dropbox-original"></i>
                    </div>
                    <div class="media-body">
                        <h5>Hỗ trợ CSKH trực tuyến </h5>
                        <span>Giờ làm việc 8h00 đến 21h30</span>
                    </div>
                </li>
            </ul>
        </div>
    </section>
    <!-- End Shipping Info -->
@endsection
