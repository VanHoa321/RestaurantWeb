@extends('layout/web_layout')
@section('content')
    <main class="main-content">
        <div class="breadcrumb-area breadcrumb-height" data-bg-image="http://127.0.0.1:8000/storage/files/1/Slide/banner_1.png">
            <div class="container h-100">
                <div class="row h-100">
                    <div class="col-lg-12">
                        <div class="breadcrumb-item">
                            <h2 class="breadcrumb-heading">Món ăn nhà hàng</h2>
                            <ul>
                                <li>
                                    <a href="index.html">Trang chủ <i class="pe-7s-angle-right"></i></a>
                                </li>
                                <li>Mặt hàng</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="shop-area section-space-y-axis-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 order-lg-1 order-2 pt-10 pt-lg-0">
                        <div class="sidebar-area">
                            <div class="widgets-searchbox mb-9">
                                <input class="input-field" type="text" id="search-name" placeholder="Tìm món ăn">
                            </div>
                            <div class="widgets-area mb-9">
                                <h2 class="widgets-title mb-5">Danh mục</h2>
                                <div class="widgets-item">
                                    <ul class="widgets-checkbox">                               
                                        @foreach ($menus as $menu)
                                            <li>
                                                <input class="input-checkbox menu-filter" type="checkbox" id="refine-item-{{ $menu->id }}" value="{{ $menu->id }}" checked>
                                                <label class="label-checkbox mb-0" for="refine-item-{{ $menu->id }}"> {{ $menu->name }}
                                                    <span>{{ $menu->menu_items_count }}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                        <li>
                                            <input class="input-checkbox combo-filter" type="checkbox" id="refine-item-4" checked>
                                            <label class="label-checkbox mb-0" for="refine-item-4"> Combo
                                                <span>{{ $comboCount }}</span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="widgets-area mb-9">
                                <h2 class="widgets-title mb-5">Giá bán</h2>
                                <div class="widgets-searchbox mb-3">
                                    <input class="input-field" type="number" min="1" id="min-price" placeholder="Nhập giá tối thiểu">
                                </div>
                                <div class="widgets-searchbox mb-9">
                                    <input class="input-field" type="number" min="1" id="max-price" placeholder="Nhập giá tối đa">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 order-lg-2 order-1">
                        <div class="product-topbar">
                            <h3 style="margin-top: 5px; color: #bac34e;">Danh sách mặt hàng</h3>
                        </div>
                        <div class="tab-content text-charcoal pt-8">
                            <div class="tab-pane fade show active" id="grid-view" role="tabpanel" aria-labelledby="grid-view-tab">
                                <div class="product-grid-view row" id="product-container-grid">
                                </div>
                            </div>
                        </div>
                        <div class="pagination-area pt-10"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            function loadItems(page = 1) {

                var selectedMenus = [];
                var searchName = $("#search-name").val();
                var minPrice = $("#min-price").val() || 0;
                var maxPrice = $("#max-price").val() || 999999999;
                $(".menu-filter:checked").each(function () {
                    selectedMenus.push($(this).val());
                });

                var selectedCombos = $(".combo-filter").is(":checked") ? 1 : 0;
                $.ajax({
                    url: "/getData?page=" + page,
                    method: "GET",
                    data: { 
                        menu_ids: selectedMenus, 
                        combo: selectedCombos,
                        search_name: searchName,
                        min_price: minPrice,
                        max_price: maxPrice
                    },
                    success: function (response) {
                        var item_grid = "";
                        $.each(response.menu_items, function (index, item) {
                            item_grid += `
                            <div class="col-lg-3 col-sm-4">
                                <div class="product-item">
                                    <div class="product-img img-zoom-effect">
                                        <a href="single-product.html">
                                            <img class="img-full" src="${item.item.avatar}" alt="Item Image">
                                        </a>
                                        <div class="product-add-action">
                                            <ul>
                                                <li><a href="cart.html"><i class="pe-7s-cart"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <a class="product-name" href="single-product.html">${item.item.name}</a>
                                        <div class="price-box pb-1">
                                            <span class="new-price">${item.item.active_price.sale_price.toLocaleString("vi-VN")} đ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        });
                        $.each(response.combos, function (index, item) {
                            item_grid += `
                            <div class="col-lg-3 col-sm-4">
                                <div class="product-item">
                                    <div class="product-img img-zoom-effect">
                                        <a href="single-product.html">
                                            <img class="img-full" src="${item.avatar}" alt="Item Image">
                                        </a>
                                        <div class="product-add-action">
                                            <ul>
                                                <li><a href="cart.html"><i class="pe-7s-cart"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <a class="product-name" href="single-product.html">${item.name}</a>
                                        <div class="price-box pb-1">
                                            <span class="new-price">${item.price.toLocaleString("vi-VN")} đ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        });
                        if (item_grid === "") {
                            item_grid = "<img style='width:300px; display: block; margin: auto; margin-top:80px' src='http://127.0.0.1:8000/storage/files/1/Item/6359876.png'/>"
                        }
                        
                        $("#product-container-grid").html(item_grid);
                        renderPagination(response);
                    }
                });
            }

            function renderPagination(data) {
                var paginationHTML = '<ul class="pagination justify-content-center">';
                if (data.prev_page_url) {
                    paginationHTML += `<li class="page-item">
                        <a class="page-link" href="#" data-page="${data.current_page - 1}">
                            <span class="fa fa-chevron-left"></span>
                        </a>
                    </li>`;
                }

                for (let i = 1; i <= data.last_page; i++) {
                    paginationHTML += `<li class="page-item ${data.current_page == i ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>`;
                }

                if (data.next_page_url) {
                    paginationHTML += `<li class="page-item">
                        <a class="page-link" href="#" data-page="${parseInt(data.current_page) + 1}">
                            <span class="fa fa-chevron-right"></span>
                        </a>
                    </li>`;
                }
                paginationHTML += '</ul>';
                $(".pagination-area").html(paginationHTML);
            }

            $("#search-name").on("input", function () {
                loadItems(1);
            });

            $("#min-price, #max-price").on("change", function () {
                loadItems(1);
            });

            $(document).on("click", ".pagination a", function (e) {
                e.preventDefault();
                var page = $(this).data("page");
                loadItems(page);
            });

            loadItems();

            $(".menu-filter, .combo-filter").change(function () {
                loadItems();
            });
        });
    </script>
@endsection