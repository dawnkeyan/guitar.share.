@extends('layouts.app')

@section('content')
    <script>
        $("#navbar-yuepu").addClass("active");
    </script>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">新增乐谱</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/save_yuepu?id='.(isset($data->id)?$data->id : null)) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">标题</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ (isset($data->name)?$data->name : null) }}" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cover" class="col-md-4 control-label">封面图</label>

                                <div class="col-md-6">
                                    <input type="file" onchange="upload(this,'cover');">
                                    <input id="cover" type="text" class="form-control" name="cover" value="{{ (isset($data->cover)?$data->cover : null) }}">
                                    <img id="bigcover"  src="{{ (isset($data->cover)?$data->cover : null) }}"  width="95" height="84"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="file" class="col-md-4 control-label">乐谱</label>

                                <div class="col-md-6">
                                    <input type="file" onchange="upload(this,'file')">
                                    <input id="file" type="text" class="form-control" name="file" value="{{ (isset($data->file)?$data->file : null) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="context" class="col-md-4 control-label">简介</label>

                                <div class="col-md-6">
                                    <input id="context" type="text" class="form-control" name="context" value="{{ (isset($data->context)?$data->context : null) }}" required autofocus>
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
        </div>
    </div>
@endsection
<script>
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