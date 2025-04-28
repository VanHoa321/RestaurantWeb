@extends('layout/web_layout')
@section('content')
    <main class="main-content">
        <div class="breadcrumb-area breadcrumb-height" data-bg-image="http://127.0.0.1:8000/storage/files/1/Slide/banner_1.png">
            <div class="container h-100">
                <div class="row h-100">
                    <div class="col-lg-12">
                        <div class="breadcrumb-item">
                            <h2 class="breadcrumb-heading">Chi tiết bài viết</h2>
                            <ul>
                                <li>
                                    <a href="{{ route("home.index") }}">Trang chủ <i class="pe-7s-angle-right"></i></a>
                                </li>
                                <li>Chi tiết bài viết</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="blog-area section-space-y-axis-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="blog-detail-item">
                            <div class="blog-img">
                                <img class="img-full" src="{{ $blog->image }}" alt="Blog Image">
                            </div>
                            <div class="blog-content text-start pb-0">
                                <div class="blog-meta text-dim-gray pb-3">
                                    <ul>
                                        <li class="date"><i class="fa fa-calendar-o me-2"></i>{{ $blog->created_at->format('d/m/Y') }}</li>
                                        <li>
                                            <span class="comments me-3">
                                                <a href="javascript:void(0)">{{ $blog->user->name }}</a>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <h5 class="title mb-4">
                                    <a href="javascript:void(0)">{{ $blog->title }}</a>
                                </h5>
                                <p class="short-desc mb-4 mb-7">{{ $blog->abstract }}</p>
                                <p class="short-desc mb-4 mb-9">{!! $blog->content !!}</p>
                                <div class="tag-wtih-social">
                                    <div class="tag">
                                        <span class="title text-primary">Phân loại:</span>
                                        <ul>
                                            <li>
                                                <a href="javascript:void(0)">{{ $blog->category->name }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="social-link">
                                        <ul>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <i class="fa fa-facebook"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <i class="fa fa-pinterest-p"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">
                                                    <i class="fa fa-dribbble"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection