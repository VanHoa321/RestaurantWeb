@extends('layout/web_layout')
@section('content')
    <main class="main-content">
        <div class="breadcrumb-area breadcrumb-height" data-bg-image="http://127.0.0.1:8000/storage/files/1/Slide/banner_1.png">
            <div class="container h-100">
                <div class="row h-100">
                    <div class="col-lg-12">
                        <div class="breadcrumb-item">
                            <h2 class="breadcrumb-heading">Liên hệ với nhà hàng</h2>
                            <ul>
                                <li>
                                    <a href="index.html">Trang chủ <i class="pe-7s-angle-right"></i></a>
                                </li>
                                <li>Liên hệ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="shipping-area section-space-y-axis-100">
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

        <div class="contact-with-map">
            <div class="contact-map">
                <iframe class="contact-map-size" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3779.9639822781937!2d105.67748507491531!3d18.66561218245627!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3139ce7c57ebc277%3A0xf69ed478a01dc609!2zVHLhuqduIFBow7osIEzDqiBNYW8sIFZpbmgsIE5naOG7hyBBbiwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1742262852829!5m2!1svi!2s" allowfullscreen="" loading="lazy"></iframe>
            </div>
            <div class="contact-form-area section-space-bottom-100">
                <div class="container">
                    <div class="contact-form-wrap">
                        <form id="contact-form" class="contact-form" action="https://whizthemes.com/mail-php/mamunur/harmic/harmic.php">
                            <h4 class="contact-form-title pb-2 mb-7">Gửi phản hồi nhà hàng</h4>
                            <div class="form-field">
                                <input type="text" name="con_name" placeholder="Họ tên" class="input-field">
                            </div>
                            <div class="form-field mt-6">
                                <input type="text" name="con_email" placeholder="Email" class="input-field">
                            </div>
                            <div class="form-field mt-6">
                                <textarea name="con_message" placeholder="Nội dung" class="textarea-field"></textarea>
                            </div>
                            <div class="button-wrap mt-8">
                                <button type="submit" value="submit" class="btn btn btn-custom-size lg-size btn-primary btn-secondary-hover rounded-0" name="submit">Gửi</button>
                            </div>
                            <p class="form-messege mt-3 mb-0"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection