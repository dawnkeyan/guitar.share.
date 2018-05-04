@extends('layouts.app')

@section('content')
    <script>
        $("#navbar-first").addClass("active");
    </script>
    {{--<script src="http://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}
    <div class="container">
        <div id="myCarousel" class="carousel slide">
            <!-- 轮播（Carousel）指标 -->
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
                <li data-target="#myCarousel" data-slide-to="3"></li>
                <li data-target="#myCarousel" data-slide-to="4"></li>
            </ol>
            <!-- 轮播（Carousel）项目 -->
            <div class="carousel-inner" style="height: 350px">
                <div class="item active">
                    <img src="/storage/2018-04-30-21-35-21_5ae71b99e77b8.png" alt="First slide">
                </div>
                <div class="item">
                    <img src="/storage/2018-04-30-21-20-44_5ae7182cf2676.png" alt="Second slide">
                </div>
                <div class="item">
                    <img src="/storage/2018-04-30-21-18-10_5ae7179208381.png" alt="Third slide">
                </div>
                {{--<div class="item">
                    <img src="/storage/2018-04-30-21-19-44_5ae717f0afa5a.png" alt="Fourth slide">
                </div>
                <div class="item">
                    <img src="/storage/2018-05-01-10-23-26_5ae7cf9e4328a.png" alt="Fifth slide">
                </div>--}}
                <div class="item">
                    <img src="/storage/2018-04-30-21-19-44_5ae717f0afa5a.png" alt="Fourth slide">
                </div>
                <div class="item">
                    <img src="/storage/2018-05-01-10-23-26_5ae7cf9e4328a.png" alt="Fifth slide">
                </div>
            </div>
            <!-- 轮播（Carousel）导航 -->
            <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <div>
            <div class="panel-heading" style="font-size:23px;">推荐视频</div>
            <div class="row">
                @foreach ($videos as $item)
                    <div class="col-md-3">
                        <a href="{{ $item->file }}"><img src="{{ $item->cover }}" width="185" height="165">
                            <div class="play">
                                <img style="
                                        position: absolute;
                                          left: 75px;
                                          top: 50px;
                                          width: 42px;
                                          height: 42px;
                                          z-index: 1000;
                                          cursor: pointer;" src="/images/bfan.jpg"/>
                            </div>
                        </a>
                        <p>{{ $item->name }}</p>
                        @if(!empty($user))
                            @if ($item->is_like === 1)
                                <button type="button" class="btn btn-default" onclick="like( {{ $item->id }}, 'videos')">
                                    取消收藏
                                </button>
                            @else
                                <button type="button" class="btn btn-default" onclick="like( {{ $item->id }}, 'videos')">收藏
                                </button>
                            @endif
                        @endif
                        <button type="button" class="btn btn-default">
                            <a href="/comment?category_id={{ $item->id }}&category=videos">评论</a>
                        </button>
                        <button type="button" class="btn btn-default">
                            <a href="{{ $item->file }}" download="{{ $item->file }}">下载</a>
                        </button>
                        {{--@if ($user->is_super==1)
                            <button type="button" class="btn btn-default"
                                    onclick="recommend( {{ $item->id }} , 'video'">取消推荐
                            </button>
                        @endif--}}
                    </div>
                @endforeach
            </div>
        </div>
        <br><br>
        <div>
            <div class="panel-heading" style="font-size:23px;">推荐乐谱</div>
            <div class="row">
                @foreach ($yuepus as $item)
                    <div class="col-md-3">
                        <a href="{{ $item->file }}"><img src="{{ $item->cover }}" width="185" height="165">
                            <p>{{ $item->name }}</p>
                        </a>
                        @if(!empty($user))
                            @if ($item->is_like === 1)
                                <button type="button" class="btn btn-default" onclick="like( {{ $item->id }}, 'yuepus')">
                                    取消收藏
                                </button>
                            @else
                                <button type="button" class="btn btn-default" onclick="like( {{ $item->id }}, 'yuepus')">收藏
                                </button>
                            @endif

                        @endif
                        <button type="button" class="btn btn-default">
                            <a href="/comment?category_id={{ $item->id }}&category=yuepus">评论</a>
                        </button>
                        <button type="button" class="btn btn-default">
                            <a href="{{ $item->file }}" download="{{ $item->file }}">下载</a>
                        </button>
                        {{--@if ($user->is_super==1)
                            <button type="button" class="btn btn-default"
                                    onclick="recommend( {{ $item->id }} , 'yuepu'">取消推荐
                            </button>
                        @endif--}}
                    </div>
                @endforeach
            </div>
        </div>
        <div>
        </div>
    </div>
@endsection
<script>

    function comment(id, table) {
        swal({
                    title: "评论！",
                    text: "请输入评论内容：",
                    type: "input",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonText: "确定！",
                    cancelButtonText: "取消！",
                    animation: "slide-from-top",
                    inputPlaceholder: "评论"
                },
                function (inputValue) {
                    if (inputValue === false) return false;

                    if (inputValue === "") {
                        swal.showInputError("请输入评论内容！");
                        return false
                    }

                    $.ajax({
                        type: 'POST',
                        url: '/comment_' + table + '?id=' + id,
                        data: {'context': inputValue},
                        success: function (data) {
                            window.location.reload();
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                });
    }

    function like(id, table) {
        swal({
                    title: "确定吗？",
                    text: "",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定！",
                    cancelButtonText: "取消！",
                    closeOnConfirm: false
                },
                function () {
                    $.ajax({
                        type: 'GET',
                        url: '/like_' + table + '?id=' + id,
                        success: function (data) {
                            window.location.reload();
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                });
    }

    function recommend(id, table) {
        swal({
                    title: "确定吗？",
                    text: "",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定！",
                    cancelButtonText: "取消！",
                    closeOnConfirm: false
                },
                function () {
                    $.ajax({
                        type: 'GET',
                        url: '/recommend_' + table + '?id=' + id,
                        success: function (data) {
                            window.location.reload();
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                });
    }


    function deleteYuepu(id, table) {
        swal({
                    title: "确定删除吗？",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定！",
                    cancelButtonText: "取消！",
                    closeOnConfirm: false
                },
                function () {
                    $.ajax({
                        type: 'GET',
                        url: '/delete_' + table + '?id=' + id,
                        success: function (data) {
                            window.location.reload();
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                });
    }
</script>

