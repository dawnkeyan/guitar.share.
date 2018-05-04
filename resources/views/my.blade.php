
@extends('layouts.app')

@section('content')
    <script>
        $("#navbar-my").addClass("active");
    </script>
    <div class="container">
        <a href="{{ url('/save_video') }}"><button type="button" class="btn btn-success">新增</button></a>
        <table class="table">
            <thead>
            <tr>
                <th>标题</th>
                <th>封面图</th>
                <th>视频</th>
                <th>简介</th>
                @if ($user)
                    <th>评论</th>
                @endif
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($videos as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td><img src="{{ $item->cover }}" width="95" height="84"></td>
                    <td><a href="{{ $item->file }}">观看</a></td>
                    <td>{{ $item->context }}</td>
                    @if ($user)
                        <td>{{ $item->comments }}</td>
                    @endif
                    <td>
                        <a href="{{ $item->file }}" download="{{ $item->file }}">
                            <button type="button" class="btn btn-default">下载</button>
                        </a>
                        @if ($user)
                            @if ($user->is_super==1)
                                <button type="button" class="btn btn-danger" onclick="deleteVideo({{ $item->id }})">删除</button>
                                <a href="{{ url('/save_video?id='.$item->id) }}"><button type="button" class="btn btn-warning">编辑</button></a>
                                @if ($item->is_recommend === 1)
                                    <button type="button" class="btn btn-default" onclick="recommend({{ $item->id }})">取消推荐</button>
                                @else
                                    <button type="button" class="btn btn-default" onclick="recommend({{ $item->id }})">推荐</button>
                                @endif
                            @endif

                            @if ($item->is_like === 1)
                                <button type="button" class="btn btn-default" onclick="like({{ $item->id }})">取消收藏</button>
                            @else
                                <button type="button" class="btn btn-default" onclick="like({{ $item->id }})">收藏</button>
                            @endif

                            <button type="button" class="btn btn-default" onclick="comment({{ $item->id }})">评论</button>

                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

<script>

    function comment(id,comments) {
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
                        url:'/comment_video?id=' + id,
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
                        url:'/like_video?id=' + id,
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
                        url:'/recommend_video?id=' + id,
                        success:function (data) {
                            window.location.reload();
                        },
                        error:function (data) {
                            console.log(data);
                        }
                    });
                });
    }


    function deleteVideo(id) {
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
                        url:'/delete_video?id=' + id,
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

