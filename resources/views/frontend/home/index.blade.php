@extends('layout/web_layout')
@section('content')
    <div class="slider-area">
        <div class="swiper-container main-slider-2 swiper-arrow with-bg_white">
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                    <div class="swiper-slide animation-style-01">
                        <a href="{{ $slide->alias ? $slide->alias : "#" }}">
                            <div class="slide-inner bg-height" data-bg-image="{{ $slide->image }}">
                                <div class="container">
                                    <div class="slide-content">
                                        <h2 class="title font-weight-bold mb-4">{{ $slide->title }}</h2>
                                        <p class="short-desc different-width mb-9">{{ $slide->sub_title }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination with-bg d-md-none"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
    <div class="shipping-area section-space-top-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="shipping-item">
                        <div class="shipping-img">
                            <img src="web/assets/images/shipping/icon/plane.png" alt="Shipping Icon">
                        </div>
                        <div class="shipping-content">
                            <h5 class="title">Miễn phí giao hàng</h5>
                            <p class="short-desc mb-0">Từ T2 - T6 trong tuần</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 pt-6 pt-md-0">
                    <div class="shipping-item">
                        <div class="shipping-img">
                            <img src="web/assets/images/shipping/icon/earphones.png" alt="Shipping Icon">
                        </div>
                        <div class="shipping-content">
                            <h5 class="title">Hỗ trợ trực tuyến</h5>
                            <p class="short-desc mb-0">24/7 sẵn sàng trợ giúp</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 pt-6 pt-lg-0">
                    <div class="shipping-item">
                        <div class="shipping-img">
                            <img src="web/assets/images/shipping/icon/shield.png" alt="Shipping Icon">
                        </div>
                        <div class="shipping-content">
                            <h5 class="title">Thanh toán dễ dàng</h5>
                            <p class="short-desc mb-0">Đa dạng hình thức thanh toán</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="product-area section-space-top-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav product-tab-nav pb-10" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="active" id="all-items-tab" data-bs-toggle="tab" href="#all-items" role="tab" aria-controls="all-items" aria-selected="true">
                                <div class="product-tab-icon">
                                    <img src="web/assets/images/product/icon/1.png" alt="Product Icon">
                                </div>
                                Toàn bộ
                            </a>
                        </li>
                        @foreach ($menus as $menu)
                            <li class="nav-item" role="presentation">
                                <a id="menu-{{ $menu->id }}-tab" data-bs-toggle="tab" href="#menu-{{ $menu->id }}" role="tab" aria-controls="menu-{{ $menu->id }}" aria-selected="false">
                                    <div class="product-tab-icon">
                                        <img src="{{ $menu->avatar }}" alt="Product Icon" style="width:108px">
                                    </div>
                                    {{ $menu->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="all-items" role="tabpanel" aria-labelledby="all-items-tab">
                            <div class="product-item-wrap row">
                                @foreach ($menu_items as $item)
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="product-item">
                                            <div class="product-img img-zoom-effect">
                                                <a href="£">
                                                    <img class="img-full" src="{{ $item->item->avatar }}" alt="Product Images">
                                                </a>
                                                <div class="product-add-action">
                                                    <ul>
                                                        <li>
                                                            <a href="cart.html">
                                                                <i class="pe-7s-cart"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <a class="product-name" href="£">{{ $item->item->name }}</a>
                                                <div class="price-box pb-1">
                                                    <span class="new-price">{{ number_format($item->item->activePrice->sale_price, 0, ',', '.') }} đ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @foreach ($menus as $menu)
                            <div class="tab-pane fade" id="menu-{{ $menu->id }}" role="tabpanel" aria-labelledby="menu-{{ $menu->id }}-tab">
                                <div class="product-item-wrap row">
                                    @foreach ($menu_items->where('menu_id', $menu->id) as $item)
                                        <div class="col-xl-3 col-lg-4 col-sm-6">
                                            <div class="product-item">
                                                <div class="product-img img-zoom-effect">
                                                    <a href="single-product.html">
                                                        <img class="img-full" src="{{ $item->item->avatar }}" alt="Product Images">
                                                    </a>
                                                    <div class="product-add-action">
                                                        <ul>
                                                            <li>
                                                                <a href="cart.html">
                                                                    <i class="pe-7s-cart"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <a class="product-name" href="single-product.html">{{ $item->item->name }}</a>
                                                    <div class="price-box pb-1">
                                                        <span class="new-price">{{ number_format($item->item->activePrice->sale_price, 0, ',', '.') }} đ</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="product-area section-space-y-axis-100">
        <div class="container">
            <div class="section-title text-center pb-55">
                <span class="sub-title text-primary">Combo</span>
                <h2 class="title mb-0">Danh sách Combo món ăn ưu đãi</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="swiper-container product-slider swiper-arrow with-radius border-issue">
                        <div class="swiper-wrapper">
                            @foreach ($combos as $combo)
                                <div class="swiper-slide">
                                    <div class="product-item">
                                        <div class="product-img img-zoom-effect">
                                            <a href="single-product.html">
                                                <img class="img-full" src="{{ $combo->avatar }}" alt="Product Images">
                                            </a>
                                            <div class="product-add-action">
                                                <ul>
                                                    <li>
                                                        <a href="cart.html" class="mt-5">
                                                            <i class="pe-7s-cart"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <a class="product-name" href="single-product.html">{{ $combo->name }}</a>
                                            <div class="price-box pb-1">
                                                <span class="new-price">{{ number_format($combo->price, 0, ',', '.') }} đ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-nav-wrap">
                            <div class="swiper-button-next"></div>
                        </div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="blog-area section-space-y-axis-100">
        <div class="container">
            <div class="section-title text-center pb-55">
                <span class="sub-title text-primary">Bài viết</span>
                <h2 class="title mb-0">Các bài viết mới nhất</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="swiper-container blog-slider">
                        <div class="swiper-wrapper">
                            @foreach ($blogs as $blog)
                                <div class="swiper-slide">
                                    <div class="blog-item">
                                        <div class="blog-img img-zoom-effect">
                                            <a href="/blog/detail/{{ $blog->id }}">
                                                <img class="img-full" src="{{ $blog->image }}" alt="Blog Image">
                                            </a>
                                        </div>
                                        <div class="blog-content">
                                            <div class="blog-meta text-dim-gray pb-3">
                                                <ul>
                                                    <li class="date"><i class="fa fa-calendar-o me-2"></i>{{ $blog->created_at->format("d/m/Y") }}</li>
                                                    <li>
                                                        <span class="comments me-3">
                                                            <a href="javascript:void(0)">{{ $blog->user->name }}</a>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <h5 class="title mb-4">
                                                <a href="/blog/detail/{{ $blog->id }}">{{ $blog->title }}</a>
                                            </h5>
                                            <p class="short-desc mb-5">{{ $blog->abstract }}</p>
                                            <div class="button-wrap">
                                                <a class="btn btn-custom-size btn-dark btn-lg rounded-0" href="/blog/detail/{{ $blog->id }}">Chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection