@extends('layouts.app')

@section('content')
    <script>
        $("#navbar-my_message").addClass("active");
    </script>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    @if ($user)
                        <div class="panel-heading"><button type="button" class="btn btn-success" onclick="comment('')">评论</button></div>
                    @endif
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-md-2 control-label">标题</label>

                                <div class="col-md-8">
                                    <label  class=" control-label">{{ (isset($data->name)?$data->name : null) }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">封面图</label>

                                <div class="col-md-10">
                                    <img  src="{{ (isset($data->cover)?$data->cover : null) }}"  width="125" height="110"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">资源</label>

                                <div class="col-md-10">
                                    <label class=" control-label"><a href="{{ (isset($data->file)?$data->file : null) }}">查看</a></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">简介</label>

                                <div class="col-md-8">
                                    <label class=" control-label">{{ (isset($data->context)?$data->context : null) }}</label>
                                </div>
                            </div>
                        </form>
                        <table class="table">
                            <tbody>
                            @foreach ($comment as $item)
                                <tr>
                                    <td>
                                        @if($item->be_user_id)
                                            <a href="/user_info?id={{ $item->user_id }}">{{ $item->user_name }}</a>
                                            回复
                                            <a href="/user_info?id={{ $item->be_user_id }}">{{ $item->be_user_name }}</a>：
                                        @else
                                            <a href="/user_info?id={{ $item->user_id }}">{{ $item->user_name }}</a>:
                                    @endif
                                    {{ $item->context }}
                                    <td/>
                                    <td>
                                        {{ $item->created_at }}
                                    </td>
                                    <td>@if ($user && $user->id != $item->user_id)
                                            <button type="button" class="btn btn-default" onclick="comment({{ $item->user_id }})">回复</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    function getQuest() {
        var data = location.search.substring(1);
        strs = data.split("&");
        var request = [];
        for (var i = 0; i < strs.length; i++) {
            request[strs[i].split("=")[0]] = strs[i].split("=")[1];
        }
        return request;
    }

    function comment(user_id) {
        var query = getQuest();
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
                        url: '/comment?category_id='+ query['category_id'] + '&category=' + query['category'],
                        data: {'context': inputValue,'be_user_id':user_id},
                        success: function (data) {
                            if(data.code != 0){
                                swal(data.message);
                            }
                            else{
                                window.location.reload();
                            }
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                });
    }
</script>