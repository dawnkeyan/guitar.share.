@extends('layouts.app')

@section('content')
    <script>
        $("#navbar-my").addClass("active");
    </script>
    <div class="container">
        <div class="row">
            @if($user->id==$data->id)
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">个人信息</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/user_info?') }}" enctype="multipart/form-data" onsubmit="return doValidate()">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">用户名</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" name="name" value="{{ (isset($data->name)?$data->name : null) }}" required autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-md-4 control-label">邮箱</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email" value="{{ (isset($data->email)?$data->email : null) }}" required autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">性别</label>

                                    <div class="col-md-6">
                                        @if ($data->gender == 1)
                                            <label for="gender1"><input id="gender1" type="radio" value="1" name="gender" checked>女</label>
                                            <label for="gender2"><input id="gender2" type="radio" value="2" name="gender">男</label>
                                        @elseif ( $data->gender == 2)
                                            <label for="gender1"><input id="gender1" type="radio" value="1" name="gender">女</label>
                                            <label for="gender2"><input id="gender2" type="radio" value="2" name="gender" checked>男</label>
                                        @else
                                            <label for="gender1"><input id="gender1" type="radio" value="1" name="gender">女</label>
                                            <label for="gender2"><input id="gender2" type="radio" value="2" name="gender">男</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="hobby" class="col-md-4 control-label">爱好</label>

                                    <div class="col-md-6">
                                        <input id="hobby" type="text" class="form-control" name="hobby" value="{{ (isset($data->hobby)?$data->hobby : null) }}" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="avatar" class="col-md-4 control-label">头像</label>

                                    <div class="col-md-6">
                                        <input type="file" onchange="upload(this,'avatar');">
                                        <input id="avatar" type="text" class="form-control" name="avatar" value="{{ (isset($data->avatar)?$data->avatar : null) }}">
                                        <img id="bigavatar"  src="{{ (isset($data->avatar)?$data->avatar : null) }}"  width="95" height="84"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="city" class="col-md-4 control-label">城市</label>

                                    <div class="col-md-6">
                                        <input id="city" type="text" class="form-control" name="city" value="{{ (isset($data->city)?$data->city : null) }}" required autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="col-md-4 control-label">电话号码</label>

                                    <div class="col-md-6">
                                        <input id="phone" type="number" class="form-control" name="phone" value="{{ (isset($data->phone)?$data->phone : null) }}" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="skill" class="col-md-4 control-label">技能</label>

                                    <div class="col-md-6">
                                        <input id="skill" type="text" class="form-control" name="skill" value="{{ (isset($data->skill)?$data->skill : null) }}" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            提交
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-horizontal" enctype="multipart/form-data">
                                <a href="/private_letter?user_id={{ $data->id }}">
                                    <label style="font-size: larger">私信</label>
                                </a>
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">用户名</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" name="name" value="{{ (isset($data->name)?$data->name : null) }}" required autofocus readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-md-4 control-label">邮箱</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email" value="{{ (isset($data->email)?$data->email : null) }}" required autofocus readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">性别</label>

                                    <div class="col-md-6">
                                        @if ($data->gender == 1)
                                            <label for="gender1"><input id="gender1" type="radio" value="1" name="gender" checked>女</label>
                                        @else
                                            <label for="gender2"><input id="gender2" type="radio" value="2" name="gender" checked>男</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="hobby" class="col-md-4 control-label">爱好</label>

                                    <div class="col-md-6">
                                        <input id="hobby" type="text" class="form-control" name="hobby" value="{{ (isset($data->hobby)?$data->hobby : null) }}" required autofocus readonly="readonly">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="avatar" class="col-md-4 control-label">头像</label>

                                    <div class="col-md-6">
                                        {{--<input type="file" onchange="upload(this,'avatar');">
                                        <input id="avatar" type="text" class="form-control" name="avatar" value="{{ (isset($data->avatar)?$data->avatar : null) }}">--}}
                                        <img id="bigavatar"  src="{{ (isset($data->avatar)?$data->avatar : null) }}"  width="95" height="84"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="city" class="col-md-4 control-label">城市</label>

                                    <div class="col-md-6">
                                        <input id="city" type="text" class="form-control" name="city" value="{{ (isset($data->city)?$data->city : null) }}" required autofocus readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="col-md-4 control-label">电话号码</label>

                                    <div class="col-md-6">
                                        <input id="phone" type="number" class="form-control" name="phone" value="{{ (isset($data->phone)?$data->phone : null) }}" required autofocus readonly="readonly">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="skill" class="col-md-4 control-label">技能</label>

                                    <div class="col-md-6">
                                        <input id="skill" type="text" class="form-control" name="skill" value="{{ (isset($data->skill)?$data->skill : null) }}" required autofocus readonly="readonly">
                                    </div>
                                </div>

                                {{--<div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            提交
                                        </button>
                                    </div>
                                </div>--}}
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
<script>
    function doValidate() {

        //手机号正则
        var phoneReg = /(^1[3|4|5|7|8]\d{9}$)|(^09\d{8}$)/;
        //电话
        var phone = $.trim($('#phone').val());
        if (!phoneReg.test(phone)) {
            swal("请输入有效的手机号码！");
            return false;
        }
        return true;
    }


    function upload(node,id) {
        var file = node.files[0];
        var formData = new FormData();
        formData.append("file", file);
        $.ajax({
            url: '/upload',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            /*xhr: function() {
             var xhr = $.ajaxSettings.xhr();
             $(".progress-bar").css("width", "0%");
             $('.progress').show();
             if (onprogress && xhr.upload) {
             xhr.upload.addEventListener('progress', onprogress, false);
             return xhr;
             }
             },*/
            success: function(data) {
                $("#" + id).attr('value',data.path);
                $("#big" + id).attr('src',data.path);
                /*$('.progress').hide();
                 $('#input_video').val('');
                 if (data.CODE == 'ok') {
                 var video_src = data.DATA['url'];
                 $('#prev_video').attr('src', video_src);
                 } else {
                 toastr.warning(data.MESSAGE);
                 }*/
            },
            error: function(err) {
                // console.log(err);
                /*$('.progress').hide();
                 $('#input_video').val('');
                 toastr.error(err.statusText || '上传失败');*/
            }
        });
    }
</script>