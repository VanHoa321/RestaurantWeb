@extends('layout/web_layout')
@section('content') 
    <main class="main-content">
        <div class="breadcrumb-area breadcrumb-height" data-bg-image="http://127.0.0.1:8000/storage/files/1/Slide/banner_1.png">
            <div class="container h-100">
                <div class="row h-100">
                    <div class="col-lg-12">
                        <div class="breadcrumb-item">
                            <h2 class="breadcrumb-heading">Đặt bàn trước</h2>
                            <ul>
                                <li>
                                    <a href="{{ route("home.index") }}">Trang chủ <i class="pe-7s-angle-right"></i></a>
                                </li>
                                <li>Đặt bàn</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cart-area section-space-y-axis-100">
            <div class="container">
                <div class="row">
                    <div class="col-12" id="cartContainer">
                        @if(session('cart') && count(session('cart')) > 0)
                            <div class="table-content table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="product-thumbnail">Hình ảnh</th>
                                            <th class="cart-product-name">Món ăn</th>
                                            <th class="product-price">Giá bán</th>
                                            <th class="product-quantity">Số lượng</th>
                                            <th class="product-subtotal">Thành tiền</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(session('cart') as $item)
                                            <tr id="cart{{ $item['id'] }}-{{ $item['type'] }}">
                                                <td class="product-thumbnail">
                                                    <a href="javascript:void(0)">
                                                        <img src="{{ $item['img'] }}" alt="Cart Image" style="width: 80px; height: 80px;">
                                                    </a>
                                                </td>
                                                <td class="product-name"><a href="javascript:void(0)">{{ $item['name'] }}</a></td>
                                                <td class="product-price"><span class="amount">{{ number_format($item['price'], 0, ',', '.') }} đ</span></td>
                                                <td>
                                                    <div class="cart-container">
                                                        <button class="cart-minus">-</button>
                                                        <input id="quantity-item" class="cart-input" value="{{ $item['quantity'] }}" type="text">
                                                        <button class="cart-plus">+</button>
                                                    </div>
                                                </td>                                                
                                                <td class="product-subtotal"><span class="amount">{{ number_format($item['quantity']*$item['price'], 0, ',', '.') }} đ</span></td>
                                                <td><a class="btn btn-sm btn-danger delete-cart" href="#" data-id="{{ $item['id'] }}" data-type="{{ $item['type'] }}"><i class="fa-solid fa-trash"></i></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="coupon-all">
                                        <div class="coupon">
                                            <a href="{{ route('ft-item.index') }}" class="btn btn-sm p-2 btn-primary">Tiếp tục chọn món</a>
                                            <a href="{{ route('cart.clear') }}" class="btn btn-sm p-2 btn-danger">Xóa hết</a>
                                            <a href="{{ route("order.index") }}" class="btn btn-sm p-2 btn-success">Đặt bàn</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="cart-total float-right p-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Tạm tính:</span>
                                            <span><strong id="cart-subtotal">{{ number_format($subtotal, 0, ',', '.') }} đ</strong></span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>Khuyến mãi:</span>
                                            <span id="cart-discount">{{ number_format($discount, 0, ',', '.') }} đ</span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2 border-top pt-2">
                                            <span>Thành tiền:</span>
                                            <span class="text-danger font-weight-bold"><strong id="cart-total">{{ number_format($total, 0, ',', '.') }} đ</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>      
                        @else
                            <div>
                                <img style='width:300px; display: block; margin: auto; margin-top:20px' src='http://127.0.0.1:8000/storage/files/1/Item/6359876.png'/>
                                <p class="text-danger text-center mt-4">Chưa có sản phẩm trong giỏ hàng, hãy thêm món ăn trước khi đặt bàn</p>
                            </div>
                        @endif
                    </div>
                    <div class="col-12"></div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        $(document).on("click", ".delete-cart", function (e) {
            e.preventDefault();
            var id = $(this).data("id");
            var type = $(this).data("type");
            $.ajax({
                url: "/cart/remove",
                method: "POST",
                data: {
                    id: id,
                    type: type,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $("#cartItemCount").text(response.count);

                    $("#cart"+id+"-"+type).remove();

                    $("#cart-subtotal").text(response.subtotal.toLocaleString("vi-VN") + " đ");
                    $("#cart-discount").text(response.discount.toLocaleString("vi-VN") + " đ");
                    $("#cart-total").text(response.total.toLocaleString("vi-VN") + " đ");

                    if ($("#cartContainer tbody tr").length === 0) {
                        $("#cartContainer").html(`
                            <div>
                                <img style='width:300px; display: block; margin: auto; margin-top:20px' src='http://127.0.0.1:8000/storage/files/1/Item/6359876.png'/>
                                <p class="text-danger text-center mt-4">Chưa có sản phẩm trong giỏ hàng, hãy thêm món ăn trước khi đặt bàn</p>
                            </div>
                        `);
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    toastr.error('Đã xảy ra lỗi!');
                }
            });
        });

        $(document).on("change", "#quantity-item", function () {
            updateCart($(this));
        });

        $(document).on("click", ".cart-plus, .cart-minus", function (e) {
            e.preventDefault();

            let input = $(this).closest(".cart-container").find("#quantity-item");
            let currentValue = parseInt(input.val()) || 1;

            if ($(this).hasClass("cart-plus")) {
                input.val(currentValue + 1);
            } else if ($(this).hasClass("cart-minus") && currentValue > 1) {
                input.val(currentValue - 1);
            }

            input.trigger("change");
        });

        // Hàm cập nhật giỏ hàng
        function updateCart(input) {
            let quantity = parseInt(input.val());
            let row = input.closest("tr");
            let id = row.find(".delete-cart").data("id");
            let type = row.find(".delete-cart").data("type");

            if (quantity < 1 || isNaN(quantity)) {
                toastr.error("Số lượng không hợp lệ!");
                input.val(1);
                return;
            }

            $.ajax({
                url: "{{ route('cart.update') }}",
                type: "POST",
                data: {
                    id: id,
                    type: type,
                    quantity: quantity,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        let price = parseInt(row.find(".product-price .amount").text().replace(/\D/g, ""));
                        let totalPrice = price * quantity;
                        row.find(".product-subtotal .amount").text(totalPrice.toLocaleString("vi-VN") + " đ");
                        updateCartTotal(response.cart);
                    }
                }
            });
        }

        // Cập nhật tổng tiền của giỏ hàng
        function updateCartTotal(cart) {
            let subtotal = 0;
            Object.values(cart).forEach(item => {
                subtotal += item.quantity * item.price;
            });

            $(".cart-total strong").text(subtotal.toLocaleString("vi-VN") + " đ");
            $(".text-danger.font-weight-bold strong").text(subtotal.toLocaleString("vi-VN") + " đ");
        }
    </script>
@endsection