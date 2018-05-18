@extends('layouts.app')

@section('content')
    <script>
        $("#navbar-first").addClass("active");
    </script>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                        <div class="panel-heading">
                            <a href="{{ $data->file }}" download="{{ $data->file }}">
                                <button type="button" class="btn btn-default">下载</button>
                            </a>
                            @if ($user)
                                <button type="button" class="btn btn-success" onclick="comment('')">评论</button>
                                @if ($user->is_super)
                                    @if($category=='videos')
                                        <a href="{{ url('/save_video?id='.$data->id) }}"><button type="button" class="btn btn-warning">编辑</button></a>
                                    @else
                                        <a href="{{ url('/save_yuepu?id='.$data->id) }}"><button type="button" class="btn btn-warning">编辑</button></a>
                                    @endif

                                    @if ($data->is_recommend === 1)
                                        @if($category=='videos')
                                            <button type="button" class="btn btn-default" onclick="recommend({{ $data->id }},'video')">取消推荐</button>
                                        @else
                                            <button type="button" class="btn btn-default" onclick="recommend({{ $data->id }},'yuepu')">取消推荐</button>
                                        @endif
                                    @else
                                        @if($category=='videos')
                                            <button type="button" class="btn btn-default" onclick="recommend({{ $data->id }},'video')">推荐</button>
                                        @else
                                            <button type="button" class="btn btn-default" onclick="recommend({{ $data->id }},'yuepu')">推荐</button>
                                        @endif
                                    @endif
                                @endif
                                @if ($data->is_like === 1)
                                    @if($category=='videos')
                                        <button type="button" class="btn btn-default" onclick="like({{ $data->id }},'videos')">取消收藏</button>
                                    @else
                                        <button type="button" class="btn btn-default" onclick="like({{ $data->id }},'yuepus')">取消收藏</button>
                                    @endif
                                @else
                                    @if($category=='videos')
                                        <button type="button" class="btn btn-default" onclick="like({{ $data->id }},'videos')">收藏</button>
                                    @else
                                        <button type="button" class="btn btn-default" onclick="like({{ $data->id }},'yuepus')">收藏</button>
                                    @endif
                                @endif

                            @endif
                        </div>
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
                                    @if($category=='videos')
                                        <label class="col-md-2 control-label">资源</label>

                                        <div class="col-md-10">
                                            <video width="320" height="240" controls="controls" autoplay="autoplay">
                                                <source id="bigfile" src="{{ (isset($data->file)?$data->file : null) }}" type="video/ogg" />
                                            </video>
                                        </div>
                                    @else

                                    <div class="col-md-12">
                                        <IFRAME marginWidth=0 marginHeight=0 src="{{ (isset($data->file)?$data->file : null) }}" frameBorder=0 width="100%" scrolling=no height=600></IFRAME>
                                    </div>
                                    @endif
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
                                        @if ($user && $user->id == $item->user_id)
                                            <button type="button" class="btn btn-danger" onclick="deleteItem({{ $item->id ,'comments'}})">删除</button>
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
    function like(id,table) {
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
                        url:'/like_'+ table + '?id=' + id,
                        success:function (data) {
                            window.location.reload();
                        },
                        error:function (data) {
                            console.log(data);
                        }
                    });
                });
    }

    function recommend(id,table) {
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
                        url:'/recommend_' + table + '?id=' + id,
                        success:function (data) {
                            window.location.reload();
                        },
                        error:function (data) {
                            console.log(data);
                        }
                    });
                });
    }

    function deleteItem(id,table) {
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
                        url:'/delete_item?id=' + id + '&table=' + table,
                        success:function (data) {
                            if(data.code==0){
                                window.location.reload();
                            }
                            else{
                                swal(data.message);
                            }

                        },
                        error:function (data) {
                            console.log(data);
                        }
                    });
                });
    }


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