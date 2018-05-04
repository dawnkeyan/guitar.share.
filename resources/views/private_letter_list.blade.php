
@extends('layouts.app')

@section('content')
    <script>
        $("#navbar-video").addClass("active");
    </script>
    <div class="container">
        <table class="table">
            <thead>
            <tr>
                <th>用户名</th>
                <th>最后一条信息</th>
                <th>最后一条信息时间</th>
                <th>消息未读数</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item['user_name'] }}</td>
                    <td>{{ $item['last_message'] }}</td>
                    <td>{{ $item['last_time'] }}</td>
                    <td>
                        @if($item['wd_number']>0)
                            <div style="width: 25px; height: 25px;  background-color:red; border-radius: 50%; -moz-border-radius: 50%;-webkit-border-radius: 50%; "><a style="color: #F2F4F6" href="/private_letter?user_id={{$item['id'] }}">&nbsp;&nbsp;{{ $item['wd_number'] }}</a></div>
                        @else
                            <div style="width: 25px; height: 25px;  background-color:white; border-radius: 50%; -moz-border-radius: 50%;-webkit-border-radius: 50%; "><a href="/private_letter?user_id={{$item['id'] }}">&nbsp;&nbsp;{{ $item['wd_number'] }}</a></div>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection


