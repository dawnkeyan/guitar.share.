@extends('layouts.app')

@section('content')
    <script>
        $("#navbar-my_message").addClass("active");
    </script>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">发私信给{{ $from_user->name }}</div>
                    <div class="panel-body">
                        <table class="table">
                            <tbody>
                            <tr>
                                <div class="row">
                                    <div class="col-md-10">
                                        <input id="message_input" type="text" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-success" onclick="send_message({{$from_user->id}})">发送</button>
                                    </div>
                                </div>
                            </tr>
                            <br>
                            @foreach ($data as $item)
                                <tr>
                                    <div class="row">
                                        @if($item->from_user_id == $from_user->id)
                                            <div class="col-md-6">
                                                @if(!empty($from_user->avatar))
                                                    <img src="{{ $from_user->avatar }}" width="45px" height="45px">
                                                @else
                                                    {{ $from_user->name }}
                                                @endif


                                                :{{$item->context}}&nbsp;&nbsp;{{$item->created_at}}</div>
                                            <div class="col-md-6"></div>
                                        @else
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6">
                                                @if(!empty($user->avatar))
                                                    <img src="{{ $user->avatar }}" width="45px" height="35px">
                                                @else
                                                    {{ $user->name }}
                                                @endif

                                                :{{$item->context}}&nbsp;&nbsp;{{$item->created_at}}</div>
                                        @endif
                                    </div>
                                </tr>
                                <br>
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

    function send_message(from_user_id) {
        var message = $("#message_input").val();
        if(!message){
            swal("不能发送空信息！");
        }
        $.ajax({
            type: 'POST',
            url: '/private_letter',
            data: {'message':message ,'user_id':from_user_id},
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
    }
</script>