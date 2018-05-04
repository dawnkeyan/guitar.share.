
@extends('layouts.app')

@section('content')
    <script>
        $("#navbar-my").addClass("active");
    </script>
    <div class="container">
        <table class="table">
            <caption>我的收藏</caption>
            <thead>
            <tr>
                <th>标题</th>
                <th>封面图</th>
                <th>内容</th>
                <th>类别</th>
                <th>收藏时间</th>
                <th>简介</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->detail->name }}</td>
                    <td><img src="{{ $item->detail->cover }}" width="95" height="84"></td>
                    <td><a href="{{ $item->detail->file }}">查看</a></td>
                    @if ($item->category == 'yuepus')
                        <td>乐谱</td>
                    @else
                        <td>视频</td>
                    @endif
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->detail->context }}</td>
                    <td>
                        <a href="{{ $item->detail->file }}" download="{{ $item->detail->file }}">
                            <button type="button" class="btn btn-default">下载</button>
                        </a>
                    @if ($item->category == 'videos')
                            <button type="button" class="btn btn-default" onclick="like({{ $item->detail->id }}, 'videos')">删除收藏</button>
                            <button type="button" class="btn btn-default">
                                <a href="/comment?category_id={{ $item->id }}&category=videos">评论</a>
                            </button>
                    @else
                            <button type="button" class="btn btn-default" onclick="like({{ $item->detail->id }}, 'yuepus')">删除收藏</button>
                            <button type="button" class="btn btn-default">
                                <a href="/comment?category_id={{ $item->detail->id }}&category=yuepus">评论</a>
                            </button>
                    @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

<script>

    function comment(id,category) {
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
                        url:'/comment_' + category + '?id=' + id,
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

    function like(id,category) {
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
                        url:'/like_' + category + '?id=' + id,
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

