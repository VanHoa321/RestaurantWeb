<style>
    .short-desc-2 {
        display: -webkit-box;
        display: box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@extends('layout/web_layout')
@section('content')
<main class="main-content">
    <div class="breadcrumb-area breadcrumb-height" data-bg-image="http://127.0.0.1:8000/storage/files/1/Slide/banner_1.png">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-lg-12">
                    <div class="breadcrumb-item">
                        <h2 class="breadcrumb-heading">Bài viết của nhà hàng</h2>
                        <ul>
                            <li>
                                <a href="{{ route("home.index") }}">Trang chủ <i class="pe-7s-angle-right"></i></a>
                            </li>
                            <li>Bài viết</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="blog-area section-space-y-axis-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 order-lg-1 order-2 pt-10 pt-lg-0">
                    <div class="sidebar-area">
                        <div class="widgets-searchbox mb-9">
                            <input class="input-field" type="text" id="search-name" placeholder="Tìm bài viết">
                        </div>
                        <div class="widgets-area mb-9">
                            <h2 class="widgets-title mb-5">Danh mục</h2>
                            <div class="widgets-item">
                                <ul class="widgets-checkbox">
                                    @foreach ($categories as $cate)
                                    <li>
                                        <input class="input-checkbox cate-filter" type="checkbox" id="refine-item-{{ $cate->id }}" value="{{ $cate->id }}" checked>
                                        <label class="label-checkbox mb-0" for="refine-item-{{ $cate->id }}"> {{ $cate->name }}
                                            <span>{{ $cate->blogs_count }}</span>
                                        </label>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="widgets-area mb-9">
                            <h2 class="widgets-title mb-5">Combo mới</h2>
                            <div class="widgets-item">
                                <div class="product-list">
                                    @foreach ($commbos_new as $new)
                                    <div class="product-item d-flex align-items-center mb-3">
                                        <div class="product-img">
                                            <a href="single-product.html">
                                                <img class="img-fluid" src="{{ $new->avatar }}" alt="Product Image" style="width: 80px; height: 80px; object-fit: cover;">
                                            </a>
                                        </div>
                                        <div class="product-info ms-3">
                                            <h6 class="mb-1"><a href="blog-detail-left-sidebar.html" class="text-dark">{{ $new->name }}</a></h6>
                                            <span class="text-muted small">{{ number_format($new->price, 0, ',', '.') }} đ</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 order-lg-2 order-1">
                    <div class="product-topbar">
                        <h3 style="margin-top: 5px; color: #bac34e;">Danh sách bài viết</h3>
                    </div>
                    <div class="blog-item-wrap list-item-wrap row" id="blog-list-views"></div>
                    <div id="pagination-wrapper" class="pagination-area pt-10">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        function loadBlogs(page = 1) {

            var selecedCate = [];
            var searchName = $("#search-name").val();
            $(".cate-filter:checked").each(function() {
                selecedCate.push($(this).val());
            });

            $.ajax({
                url: "/blog-getData?page=" + page,
                method: "GET",
                data: {
                    categoryIds: selecedCate,
                    search_name: searchName
                },
                success: function(response) {
                    var blog_list = "";
                    console.log(response);
                    $.each(response.blogs.data, function(index, item) {
                        blog_list += `
                            <div class="col-12 pt-6">
                                <div class="blog-item row g-0">
                                    <div class="col-md-6">
                                        <div class="blog-img img-zoom-effect h-100">
                                            <a href="/blog/detail/${item.id}">
                                                <img class="img-full h-100" src="${item.image}" alt="Blog Image">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="blog-content">
                                            <div class="inner-content">
                                                <div class="blog-meta text-dim-gray pb-3">
                                                    <ul>
                                                        <li class="date"><i class="fa fa-calendar-o me-2"></i>${new Date(item.created_at).toLocaleDateString('vi-VN')}</li>
                                                        <li>
                                                            <span class="comments me-3">
                                                            <a href="javascript:void(0)">${item.user.name}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <h5 class="title mb-4">
                                                    <a href="/blog/detail/${item.id}">${item.title}</a>
                                                </h5>
                                                <p class="short-desc-2 mb-5">${item.abstract}</p>
                                                <div class="button-wrap">
                                                    <a class="btn btn-custom-size lg-size btn-dark rounded-0" href="/blog/detail/${item.id}">Chi tiết</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                        `;
                    });
                    if (blog_list === "") {
                        blog_list = "<img style='width:300px; display: block; margin: auto; margin-top:80px' src='http://127.0.0.1:8000/storage/files/1/Item/6359876.png'/>"
                    }

                    $("#blog-list-views").html(blog_list);
                    var pagination_html = generatePagination(response.blogs);
                    $("#pagination-wrapper .pagination").html(pagination_html);
                }
            });
        }

        function generatePagination(blogs) {
            var pages = '';

            if (blogs.prev_page_url) {
                pages += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${blogs.current_page - 1}">
                        <span style='font-size:25px'><</span>
                    </a>
                </li>`;
            }

            for (var i = 1; i <= blogs.last_page; i++) {
                pages += `
                <li class="page-item ${blogs.current_page == i ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            }

            if (blogs.next_page_url) {
                pages += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${blogs.current_page + 1}">
                        <span style='font-size:25px'>></span>
                    </a>
                </li>`;
            }

            return pages;
        }

        $(document).on("click", ".pagination .page-link", function(e) {
            e.preventDefault();
            var page = $(this).data("page");
            if (page) {
                loadBlogs(page);
            }
        });

        $("#search-name").on("input", function() {
            loadBlogs(1);
        });

        loadBlogs();

        $(".cate-filter").change(function() {
            loadBlogs();
        });
    });
</script>
@endsection