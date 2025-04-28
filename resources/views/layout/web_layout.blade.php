<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Restaurant</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset("/web/assets/favicon.png") }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset("web/assets/css/vendor/Pe-icon-7-stroke.css") }}" />
    <link rel="stylesheet" href="{{ asset("web/assets/css/plugins/animate.min.css") }}">
    <link rel="stylesheet" href="{{ asset("web/assets/css/plugins/jquery-ui.min.css") }}">
    <link rel="stylesheet" href="{{ asset("web/assets/css/plugins/swiper-bundle.min.css") }}">
    <link rel="stylesheet" href="{{ asset("web/assets/css/plugins/nice-select.css") }}">
    <link rel="stylesheet" href="{{ asset("web/assets/css/plugins/magnific-popup.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("web/assets/css/style.css") }}">    
    <link href="{{asset("assets/plugins/toastr/toastr.css")}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <div class="main-wrapper">
        <header class="main-header_area position-relative">
            <div class="header-middle py-5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="header-middle-wrap">
                                <a href="#" class="header-logo">
                                    <h3 style="color: #bac34e">Restaurant</h3>
                                </a>
                                <div class="header-search-area d-none d-lg-block">
                                    <form action="#" class="header-searchbox">
                                        <input class="input-field" type="text" placeholder="Tìm kiếm món ăn">
                                        <button class="btn btn-outline-whisper btn-primary-hover" type="submit"><i class="pe-7s-search"></i></button>
                                    </form>
                                </div>
                                <div class="header-right">
                                    <ul>
                                        @if (!Auth::guard("customer")->user())
                                            <li class="dropdown dropup">
                                                <button class="btn btn-link dropdown-toggle ht-btn p-0" type="button" id="settingButtonTwo" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="pe-7s-users"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingButtonTwo">
                                                    <li><a class="dropdown-item" href="{{ route("login") }}">Admin</a></li>
                                                    <li><a class="dropdown-item" href="{{ route("google.redirect") }}">Khách hàng</a></li>
                                                </ul>
                                            </li>
                                        @else
                                            <li class="dropdown dropup">
                                                <button class="btn btn-link dropdown-toggle ht-btn p-0" type="button" id="settingButtonTwo" data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{ Auth::guard("customer")->user()->full_name }}
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingButtonTwo">
                                                    <li><a class="dropdown-item" href="{{ route("login") }}">Lịch sử đặt bàn</a></li>
                                                    <li><a class="dropdown-item" href="{{ route("cus.logout") }}">Đăng xuất</a></li>
                                                </ul>
                                            </li>
                                        @endif
                                        <li class="d-block d-lg-none">
                                            <a href="#searchBar" class="search-btn toolbar-btn">
                                                <i class="pe-7s-search"></i>
                                            </a>
                                        </li>
                                        <li class="minicart-wrap me-3 me-lg-0">
                                            <a href="#miniCart" class="minicart-btn toolbar-btn">
                                                <i class="pe-7s-shopbag"></i>
                                                <span class="quantity" id="cartItemCount">
                                                    {{ session('cart') ? count(session('cart')) : 0 }}
                                                </span>
                                            </a>
                                        </li>
                                        <li class="mobile-menu_wrap d-block d-lg-none">
                                            <a href="#mobileMenu" class="mobile-menu_btn toolbar-btn pl-0">
                                                <i class="pe-7s-menu"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layout.partial.menu')

            <div class="offcanvas-minicart_wrapper" id="miniCart"></div>

            <div class="global-overlay"></div>
        </header>
        @include('layout.partial.chatbot')
        @yield('content')
        @include('layout.partial.footer')
        
        <a class="scroll-to-top" href="">
            <i class="fa fa-chevron-up"></i>
        </a>
    </div>

    <script src="{{ asset("web/assets/js/vendor/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/vendor/jquery-3.5.1.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/vendor/jquery-migrate-3.3.0.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/vendor/modernizr-3.11.2.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/vendor/jquery.waypoints.js") }}"></script>
    <script src="{{ asset("web/assets/js/plugins/wow.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/plugins/jquery-ui.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/plugins/swiper-bundle.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/plugins/jquery.nice-select.js") }}"></script>
    <script src="{{ asset("web/assets/js/plugins/parallax.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/plugins/jquery.magnific-popup.min.js") }}"></script>
    <script src="{{ asset("web/assets/js/main.js") }}"></script>
    <script src="{{asset("assets/plugins/toastr/toastr.min.js")}}"></script>
    @yield('scripts')

    <script>
        $(document).ready(function () {
            $(".minicart-btn").on("click", function (e) {
                e.preventDefault();
                updateMiniCart();
            });

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            },3500);

            $("#sendMessageChatbot").click(function () {
                sendChatbotMessage();
            });

            $("#chatBotInput").keypress(function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    sendChatbotMessage();
                }
            });

            function sendChatbotMessage(){
                let userMessage = $("#chatBotInput").val();
                if (userMessage === ""){
                    toastr.error("Vui lòng nhập câu hỏi");
                    return;
                }
                $("#chatbotIntro").remove(); 
                let userBubble = 
                `<div class="d-flex justify-content-end mb-1">                   
                    <div class="bg-light p-2 rounded w-75 text-end">${userMessage}</div>
                    <div class="ms-2">
                        <img src="/storage/files/1/Avatar/12225935.png" alt="Avatar" class="img-fluid rounded-circle" style="width: 40px; height: 40px;">
                    </div>
                </div>`;
                $("#chatbotContent").append(userBubble);
                $("#chatBody").animate({ scrollTop: $('#chatBody')[0].scrollHeight }, 500);
                $("#chatBotInput").val("");

                $.ajax({
                    url: "/api/ask-ai",
                    type: "POST",
                    data: { 
                        question: userMessage 
                    },
                    beforeSend: function() {
                        let loadingBubble = `
                        <div id="loadingMessage" class="d-flex align-items-start mb-1">
                            <div class="me-2">
                                <img src="/storage/files/1/Avatar/ai-avatar.jpg" alt="Avatar" class="img-fluid rounded-circle" style="width: 40px; height: 40px;">
                            </div>
                            <div class="bg-light p-2 rounded w-25 text-center"><i class="fa-solid fa-ellipsis"></i></div>
                        </div>`;
                        $("#chatbotContent").append(loadingBubble);
                        $("#chatBody").animate({ scrollTop: $('#chatBody')[0].scrollHeight }, 500);
                    },
                    success: function (response) {
                        $("#loadingMessage").remove(); 
                        let botMessage = response.answer;
                        botMessage = botMessage.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                        botMessage = botMessage.replace(/\*(.*?)\*/g, '<em>$1</em>');
                        let botBubble = 
                        `<div class="d-flex align-items-start mb-1">
                            <div class="me-2">
                                <img src="/storage/files/1/Avatar/ai-avatar.jpg" alt="Avatar" class="img-fluid rounded-circle" style="width: 40px; height: 40px;">
                            </div>
                            <div class="bg-light p-2 rounded w-75">${botMessage}</div>
                        </div>`;
                        $("#chatbotContent").append(botBubble);
                    },
                    error: function () {
                        toastr.error("Có lỗi xảy ra, vui lòng thử lại");
                    }
                });
            }
        });

        $(document).on("click", ".remove-mini-cart", function (e) {
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
                    updateMiniCart();
                },
                error: function (xhr) {
                    toastr.error('Đã xảy ra lỗi!');
                }
            });
        });

        function updateMiniCart() {
            $.ajax({
                url: "{{ route('cart.get') }}",
                type: "GET",
                success: function (response) {
                    let cartArray = Object.values(response.cart);
                    console.log(cartArray);
                    let cartHtml = `
                    <div class="harmic-offcanvas-body">
                        <div class="minicart-content">
                            <div class="minicart-heading">
                                <h4 class="mb-0">Giỏ hàng</h4>
                            </div>`;

                    if (cartArray.length > 0) {
                        cartHtml += `<ul class="minicart-list">`;
                        cartArray.forEach((item, index) => {
                            cartHtml += `
                                <li class="minicart-product" id="mini-cart-item-${item.id}-${item.type}">
                                    <a class="product-item_remove remove-mini-cart" data-id="${item.id}" data-type="${item.type}" href="#">
                                        <i class="pe-7s-close"></i>
                                    </a>
                                    <a href="#" class="product-item_img">
                                        <img class="img-full" src="${item.img}" alt="Product Image">
                                    </a>
                                    <div class="product-item_content">
                                        <a class="product-item_title" href="#">${item.name}</a>
                                        <span class="product-item_quantity">${item.quantity} x ${new Intl.NumberFormat('vi-VN').format(item.price)} đ</span>
                                    </div>
                                </li>`;
                        });

                        cartHtml += `</ul>
                            <div class="minicart-item_total">
                                <span>Tổng tiền</span>
                                <span class="ammount" id="mini-cart-total">${response.subtotal} đ</span>
                            </div>
                            <div class="group-btn_wrap d-grid gap-2">
                                <a href="{{ route('cart.index') }}" class="btn btn-secondary btn-primary-hover">Chi tiết</a>
                            </div>`;
                    } else {
                        cartHtml += `<p class="text-center text-danger py-4">Không có sản phẩm nào trong giỏ hàng</p>`;
                    }

                    cartHtml += `</div>`;

                    $("#miniCart").html(cartHtml);
                },
                error: function () {
                    console.error("Không thể tải giỏ hàng.");
                }
            });
        }
    </script>
    <script>
        const chatButton = document.getElementById("chatButton");
        const chatBox = document.getElementById("chatBox");

        chatButton.addEventListener("click", function () {
            chatBox.classList.toggle("d-none");
            chatButton.innerHTML = chatBox.classList.contains("d-none") ? "<i class='bi bi-chat-dots fs-4'></i>" : "<i class='bi bi-x-lg fs-4'></i>";
        });

        document.getElementById("closeChat").addEventListener("click", function () {
            chatBox.classList.add("d-none");
            chatButton.innerHTML = "<i class='bi bi-chat-dots fs-4'></i>";
        });

        document.addEventListener("click", function (event) {
            if (!chatBox.contains(event.target) && !chatButton.contains(event.target)) {
                chatBox.classList.add("d-none");
                chatButton.innerHTML = "<i class='bi bi-chat-dots fs-4'></i>";
            }
        }, true);
    </script>
</body>
</html>