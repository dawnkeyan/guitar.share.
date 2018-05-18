
@extends('layouts.app')

@section('content')
    <script>
        $("#navbar-yuepu").addClass("active");
    </script>
    <div class="container">
        <div class="row">
            <div class="col-md-1">
                @if ($user && $user->is_super==1)
                    <a href="{{ url('/save_yuepu') }}"><button type="button" class="btn btn-success">新增</button></a>
                @endif
            </div>
            <div class="col-md-3">
                <label for="name" class="col-md-3 control-label" >标题</label>

                <div class="col-md-8">
                    <input id="name" type="text" class="form-control" name="name" value="{{ (isset($name)?$name : null) }}">
                </div>
            </div>
            <div class="col-md-3">
                <label for="start_time" class="col-md-4 control-label">开始时间</label>
                <div class="col-md-8">
                    <input id="start_time" type="date" class="form-control" name="start_time" value="{{ (isset($start_time)?$start_time : null) }}">
                </div>
            </div>
            <div class="col-md-3">
                <label for="end_time" class="col-md-4 control-label">结束时间</label>
                <div class="col-md-8">
                    <input id="end_time" type="date" class="form-control" name="end_time" value="{{ (isset($end_time)?$end_time : null) }}">
                </div>
            </div>
            <div class="col-md-1">
                <button class="btn btn-success" onclick="search()">搜索</button>
            </div>
        </div>
        <br><br><br>
        <table class="table">
            <thead>
            <tr>
                <th>标题</th>
                <th>封面图</th>
                <th>创建时间</th>
                {{--<th>乐谱</th>--}}
                <th>简介</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($yuepus as $item)
                <tr>
                    <td><a href="/comment?category_id={{ $item->id }}&category=yuepus">{{ $item->name }}</a></td>
                    <td><a href="/comment?category_id={{ $item->id }}&category=yuepus"><img src="{{ $item->cover }}" width="95" height="84"></a></td>
                    <td><a href="/comment?category_id={{ $item->id }}&category=yuepus">{{ $item->created_at }}</a></td>
                    {{--<td><a href="{{ $item->file }}">查看</a></td>--}}
                    <td><a href="/comment?category_id={{ $item->id }}&category=yuepus">{{ $item->context }}</a></td>
                    <td>
                        {{--<a href="{{ $item->file }}" download="{{ $item->file }}">
                            <button type="button" class="btn btn-default">下载</button>
                        </a>--}}
                        @if ($user)
                            @if ($user->is_super==1)
                                <button type="button" class="btn btn-danger" onclick="deleteYuepu({{ $item->id }})">删除</button>
                                {{--<a href="{{ url('/save_yuepu?id='.$item->id) }}"><button type="button" class="btn btn-warning">编辑</button></a>
                                @if ($item->is_recommend === 1)
                                    <button type="button" class="btn btn-default" onclick="recommend({{ $item->id }})">取消推荐</button>
                                @else
                                    <button type="button" class="btn btn-default" onclick="recommend({{ $item->id }})">推荐</button>
                                @endif--}}
                            @endif

                            {{--@if ($item->is_like === 1)
                                <button type="button" class="btn btn-default" onclick="like({{ $item->id }})">取消收藏</button>
                            @else
                                <button type="button" class="btn btn-default" onclick="like({{ $item->id }})">收藏</button>
                            @endif

                                <button type="button" class="btn btn-default">
                                    <a href="/comment?category_id={{ $item->id }}&category=yuepus">评论</a>
                                </button>--}}

                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
<script>
    function search() {
        var name = $('#name').val();
        var start_time = $('#start_time').val();
        var end_time = $('#end_time').val();
        window.location.assign('/yuepu?name=' + name + '&start_time=' + start_time + '&end_time=' + end_time);
    }

    function comment(id) {
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
                function(inputValue){
                    if (inputValue === false) return false;

                    if (inputValue === "") {
                        swal.showInputError("请输入评论内容！");
                        return false
                    }

                    $.ajax({
                        type:'POST',
                        url:'/comment_yuepus?id=' + id,
                        data:{'context':inputValue},
                        success:function (data) {
                            window.location.reload();
                        },
                        error:function (data) {
                            console.log(data);
                        }
                    });
                });
    }

    function like(id) {
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
                function(){
                    $.ajax({
                        type:'GET',
                        url:'/like_yuepus?id=' + id,
                        success:function (data) {
                            window.location.reload();
                        },
                        error:function (data) {
                            console.log(data);
                        }
                    });
                });
    }

    function recommend(id) {
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
                function(){
                    $.ajax({
                        type:'GET',
                        url:'/recommend_yuepu?id=' + id,
                        success:function (data) {
                            window.location.reload();
                        },
                        error:function (data) {
                            console.log(data);
                        }
                    });
                });
    }


    function deleteYuepu(id) {
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
                function(){
                    $.ajax({
                        type:'GET',
                        url:'/delete_yuepu?id=' + id,
                        success:function (data) {
                            window.location.reload();
                        },
                        error:function (data) {
                            console.log(data);
                        }
                    });
                });
    }
</script>

