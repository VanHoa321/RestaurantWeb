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
                        <div class="col-md-12">
                            <h4 class="fw-bold text-center text-primary mt-3">Gợi ý món ăn</h4>
                            <div class="row recommendations mt-5" id="recommendations">
                            </div>
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
                                        <a href="#">
                                            <img class="img-full" src="${item.item.avatar}" alt="Item Image">
                                        </a>
                                        <div class="product-add-action">
                                            <ul>
                                                <li><a href="#" class="add-cart-item" data-id="${item.item.id}"><i class="pe-7s-cart"></i></a></li>
                                                <li><a href="#" class="openItemModal" data-bs-toggle="modal" data-id="${item.item.id}" data-bs-target="#itemModal"><i class="pe-7s-search"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <a class="product-name" href="#">${item.item.name}</a>
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
                                        <a href="#">
                                            <img class="img-full" src="${item.avatar}" alt="Item Image">
                                        </a>
                                        <div class="product-add-action">
                                            <ul>
                                                <li><a href="#" class="add-cart-combo" data-id="${item.id}"><i class="pe-7s-cart"></i></a></li>
                                                <li><a href="#" class="openComboModal" data-bs-toggle="modal" data-id="${item.id}" data-bs-target="#itemModal"><i class="pe-7s-search"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <a class="product-name" href="#">${item.name}</a>
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

        $(document).on("click", ".add-cart-item", function (e) {
            e.preventDefault();
            var id = $(this).data("id");
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
                        "timeOut": "1000"
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
                        "timeOut": "1000"
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
                        "timeOut": "1000"
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
                        "timeOut": "1000"
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

                        $.ajax({
                            url: "/api/recommendation",
                            type: "GET",
                            data: {
                                id: item.id,
                                type: "item"
                            },
                            success: function(response) {
                                if (response.success) {
                                    var recommendations = response.data;
                                    $("#recommendations").empty();
                                    recommendations.forEach(function(rec) {
                                        if (rec.type === 'item') {
                                            $("#recommendations").append(`
                                                <div class="col-md-3 col-6">
                                                    <div class="item-wrapper position-relative border border-success rounded overflow-hidden">
                                                        <img src="${rec.avatar}" class="w-100 img-fluid product-img" alt="${rec.name}">

                                                        <div class="hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white" style="background-color: rgba(151, 151, 151, 0.2)">
                                                            <div class="price fw-bold mb-2">${rec.price.toLocaleString("vi-VN")} đ</div>
                                                            <button class="btn btn-success add-cart-item" data-id="${rec.id}">
                                                                <i class="bi bi-cart"></i>
                                                            </button>
                                                        </div>

                                                        <div class="product-name position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white text-center py-1">
                                                            ${rec.name}
                                                        </div>
                                                    </div>
                                                </div>
                                            `);
                                        } else if (rec.type === 'combo') {
                                            $("#recommendations").append(`
                                                <div class="col-md-3 col-6">
                                                    <div class="item-wrapper position-relative border border-success rounded overflow-hidden">
                                                        <img src="${rec.avatar}" class="w-100 img-fluid product-img object-fit-cover" style="height: 150px;" alt="${rec.name}">

                                                        <div class="hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white" style="background-color: rgba(151, 151, 151, 0.2)">
                                                            <div class="price fw-bold mb-2">${rec.price.toLocaleString("vi-VN")} đ</div>
                                                            <button class="btn btn-success add-cart-combo" data-id="${rec.id}">
                                                                <i class="bi bi-cart"></i>
                                                            </button>
                                                        </div>

                                                        <div class="product-name position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white text-center py-1">
                                                            ${rec.name}
                                                        </div>
                                                    </div>
                                                </div>
                                            `);
                                        }
                                    });
                                } 
                                else {
                                    toastr.error("Không tìm thấy gợi ý sản phẩm!");
                                }
                            },
                            error: function() {
                                toastr.error("Lỗi khi tải gợi ý sản phẩm!");
                            }
                        });
                    } 
                    else {
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

                        $.ajax({
                            url: "/api/recommendation",
                            type: "GET",
                            data: {
                                id: combo.id,
                                type: "combo"
                            },
                            success: function(response) {
                                if (response.success) {
                                    var recommendations = response.data;
                                    $("#recommendations").empty();
                                    recommendations.forEach(function(rec) {
                                        if (rec.type === 'item') {
                                            $("#recommendations").append(`
                                                <div class="col-md-3 col-6">
                                                    <div class="item-wrapper position-relative border border-success rounded overflow-hidden">
                                                        <img src="${rec.avatar}" class="w-100 img-fluid product-img" alt="${rec.name}">

                                                        <div class="hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white" style="background-color: rgba(151, 151, 151, 0.2)">
                                                            <div class="price fw-bold mb-2">${rec.price.toLocaleString("vi-VN")} đ</div>
                                                            <button class="btn btn-success add-cart-item" data-id="${rec.id}">
                                                                <i class="bi bi-cart"></i>
                                                            </button>
                                                        </div>

                                                        <div class="product-name position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white text-center py-1">
                                                            ${rec.name}
                                                        </div>
                                                    </div>
                                                </div>
                                            `);
                                        } else if (rec.type === 'combo') {
                                            $("#recommendations").append(`
                                                <div class="col-md-3 col-6">
                                                    <div class="item-wrapper position-relative border border-success rounded overflow-hidden">
                                                        <img src="${rec.avatar}" class="w-100 img-fluid product-img object-fit-cover" style="height: 150px;" alt="${rec.name}">

                                                        <div class="hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white" style="background-color: rgba(151, 151, 151, 0.2)">
                                                            <div class="price fw-bold mb-2">${rec.price.toLocaleString("vi-VN")} đ</div>
                                                            <button class="btn btn-success add-cart-combo" data-id="${rec.id}">
                                                                <i class="bi bi-cart"></i>
                                                            </button>
                                                        </div>

                                                        <div class="product-name position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white text-center py-1">
                                                            ${rec.name}
                                                        </div>
                                                    </div>
                                                </div>
                                            `);
                                        }
                                    });
                                } 
                                else {
                                    toastr.error("Không tìm thấy gợi ý sản phẩm!");
                                }
                            },
                            error: function() {
                                toastr.error("Lỗi khi tải gợi ý sản phẩm!");
                            }
                        });
                    } 
                    else {
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