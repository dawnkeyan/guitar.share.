
@extends('layouts.app')

@section('content')
    <script>
        $("#navbar-my_message").addClass("active");
    </script>
    <div class="container">
        <table class="table">
            @if($type=='reply')
                <caption>评论回复</caption>
            @else
                <caption>用户评论</caption>
            @endif

            <thead>
            <tr>
                <th>标题</th>
                <th>类别</th>
                <th>封面图</th>
                <th>内容</th>
                <th>时间</th>
                <th>用户名</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->detail->name }}</td>
                    @if ($item->category == 'yuepus')
                        <td>乐谱</td>
                    @else
                        <td>视频</td>
                    @endif
                    <td><img src="{{ $item->detail->cover }}" width="95" height="84"></td>
                    <td>{{ $item->context }}</td>

                    <td>{{ $item->created_at }}</td>
                    <td><a href="/user_info?id={{ $item->user_id }}">{{ $item->user_name }}</a></td>

                    <td>
                        <a href="/comment?category_id={{ $item->category_id }}&category={{ $item->category }}">
                            <button type="button" class="btn btn-default" onclick="changeStatus({{ $item->id }},'href')">详情</button>
                        </a>
                        @if($item->status==0)
                            <button type="button" class="btn btn-danger" onclick="changeStatus({{ $item->id }},'')">标为已读</button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
<script>

    function changeStatus(id,source) {
        $.ajax({
            type:'GET',
            url:'/change_status_comment?id=' + id,
            success:function (data) {
                if(!source){
                    window.location.reload();
                }
            },
            error:function (data) {
            }
        });
    }

    function deleteItem(id) {
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

