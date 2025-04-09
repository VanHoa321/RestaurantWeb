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
                                                <a href="#">
                                                    <img class="img-full" src="{{ $item->item->avatar }}" alt="Product Images">
                                                </a>
                                                <div class="product-add-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" class="add-cart-item" data-id="{{ $item->id }}">
                                                                <i class="pe-7s-cart"></i>
                                                            </a>
                                                        </li>
                                                        <li><a href="#" class="openItemModal" data-bs-toggle="modal" data-id="{{ $item->item->id }}" data-bs-target="#itemModal"><i class="pe-7s-search"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <a class="product-name" href="#">{{ $item->item->name }}</a>
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
                                                    <a href="#">
                                                        <img class="img-full" src="{{ $item->item->avatar }}" alt="Product Images">
                                                    </a>
                                                    <div class="product-add-action">
                                                        <ul>
                                                            <li>
                                                                <a href="#" class="add-cart-item" data-id="{{ $item->id }}">
                                                                    <i class="pe-7s-cart"></i>
                                                                </a>
                                                            </li>
                                                            <li><a href="#" class="openItemModal" data-bs-toggle="modal" data-id="{{ $item->item->id }}" data-bs-target="#itemModal"><i class="pe-7s-search"></i></a></li>
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
                                            <a href="#">
                                                <img class="img-full" src="{{ $combo->avatar }}" alt="Product Images">
                                            </a>
                                            <div class="product-add-action">
                                                <ul>
                                                    <li>
                                                        <a href="#" class="add-cart-combo" data-id="{{ $combo->id }}">
                                                            <i class="pe-7s-cart"></i>
                                                        </a>
                                                    </li>
                                                    <li><a href="#" class="openComboModal" data-bs-toggle="modal" data-id="{{ $combo->id }}" data-bs-target="#itemModal"><i class="pe-7s-search"></i></a></li>
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
    <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <input type="hidden" id="res-id">
                    <div class="row">
                        <div class="col-md-4">
                            <img id="modal-avatar" src="" class="img-fluid rounded" alt="Sản phẩm">
                        </div>
                        <div class="col-md-8 mt-2">
                            <h4 class="fw-bold" id="modal-title"></h4>
                            <h5 class="text-danger" id="modal-price"></h5>
                            <p id="modal-dec"></p>
                            <ul class="list-unstyled">
                                <li><strong>Phân loại: </strong><span id="modal-category"></span></li>
                            </ul>
                            <a id="add-cart" class="btn btn-primary mt-3 w-100">Thêm giỏ hàng</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).on("click", ".add-cart-item", function (e) {
            e.preventDefault();
            $.ajax({
                url: "/cart/add",
                method: "POST",
                data: {
                    id: $(this).data("id"), 
                    quantity: 1,
                    type: "item",
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-bottom-right",
                        "timeOut": "3000"
                    };
                    toastr.success(response.success);
                    $("#cartItemCount").text(response.count);
                },
                error: function (xhr) {
                    console.log(xhr);
                    toastr.error('Đã xảy ra lỗi!');
                }
            });
        });

        $(document).on("click", ".add-cart-item-2", function (e) {
            e.preventDefault();
            var id = $("#res-id").val();
            $.ajax({
                url: "/cart/add",
                method: "POST",
                data: {
                    id: id, 
                    quantity: 1,
                    type: "item",
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-bottom-right",
                        "timeOut": "2000"
                    };
                    toastr.success(response.success);
                    $("#cartItemCount").text(response.count);
                },
                error: function (xhr) {
                    console.log(xhr);
                    toastr.error('Đã xảy ra lỗi!');
                }
            });
        });

        $(document).on("click", ".add-cart-combo", function (e) {
            e.preventDefault();
            $.ajax({
                url: "/cart/add",
                method: "POST",
                data: {
                    id: $(this).data("id"), 
                    quantity: 1,
                    type: "combo",
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-bottom-right",
                        "timeOut": "3000"
                    };
                    toastr.success(response.success);
                    $("#cartItemCount").text(response.count);
                },
                error: function (xhr) {
                    console.log(xhr);
                    toastr.error('Đã xảy ra lỗi!');
                }
            });
        });

        $(document).on("click", ".add-cart-combo-2", function (e) {
            e.preventDefault();
            var id = $("#res-id").val();
            $.ajax({
                url: "/cart/add",
                method: "POST",
                data: {
                    id: id, 
                    quantity: 1,
                    type: "combo",
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-bottom-right",
                        "timeOut": "3000"
                    };
                    toastr.success(response.success);
                    $("#cartItemCount").text(response.count);
                },
                error: function (xhr) {
                    console.log(xhr);
                    toastr.error('Đã xảy ra lỗi!');
                }
            });
        });

        $(document).on("click", ".openItemModal", function (e) {
            e.preventDefault();
            $("#add-cart").removeClass("add-cart-item-2");
            $("#add-cart").removeClass("add-cart-combo-2");
            var id = $(this).data("id");
            $.ajax({
                url: "/getItem/" + id,
                type: "GET",
                success: function(response) {
                    if(response.success) {
                        var item = response.item;
                        $("#res-id").val(item.id);
                        $("#modal-avatar").attr("src", item.avatar);
                        $("#modal-title").text(item.name);
                        $("#modal-price").text(item.active_price.sale_price.toLocaleString("vi-VN") + " đ");
                        $("#modal-dec").text(item.description);
                        $("#modal-category").html(item.category.name);
                        $("#add-cart").addClass("add-cart-item-2");
                        $("#itemModal").modal("show");
                    } else {
                        toastr.error("Không tìm thấy sản phẩm!");
                    }
                },
                error: function() {
                    toastr.error("Lỗi khi tải dữ liệu!");
                }
            });
        });

        $(document).on("click", ".openComboModal", function (e) {
            e.preventDefault();
            $("#add-cart").removeClass("add-cart-item-2");
            $("#add-cart").removeClass("add-cart-combo-2");
            var id = $(this).data("id");
            $.ajax({
                url: "/getCombo/" + id,
                type: "GET",
                success: function(response) {
                    if(response.success) {
                        var combo = response.combo;
                        $("#res-id").val(combo.id);
                        $("#modal-avatar").attr("src", combo.avatar);
                        $("#modal-title").text(combo.name);
                        $("#modal-price").text(combo.price.toLocaleString("vi-VN") + " đ");
                        $("#modal-dec").text(combo.description);
                        $("#modal-category").html("Combo");
                        $("#add-cart").addClass("add-cart-combo-2");
                        $("#itemModal").modal("show");
                    } else {
                        toastr.error("Không tìm thấy combo!");
                    }
                },
                error: function() {
                    toastr.error("Lỗi khi tải dữ liệu!");
                }
            });
        });
    </script>
@endsection