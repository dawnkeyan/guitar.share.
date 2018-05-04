<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/sweetalert.css">
    <script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="/js/sweetalert.min.js"></script>
    {{--<script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                {{--<div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand active" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>--}}

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    {{--<ul class="nav navbar-nav">
                        &nbsp;
                    </ul>--}}

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-center">
                        <li id="navbar-first"><a href="{{ url('/') }}" style="font-size:18px">{{ config('app.name', 'Laravel') }}</a></li>
                        <li id="navbar-yuepu"><a href="{{ url('/yuepu') }}">乐谱</a></li>
                        <li id="navbar-video"><a href="{{ url('/video') }}">视频</a></li>
                        <li id="navbar-about"><a href="{{ url('/about') }}">关于</a></li>
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li id="navbar-login"><a href="{{ route('login') }}">登录</a></li>
                            <li id="navbar-register"><a href="{{ route('register') }}">注册</a></li>
                        @else
                            <li class="dropdown"  id="navbar-my_message">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    我的信息 <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    {{--<li>
                                        <a href="{{ url('') }}">
                                            私信
                                        </a>
                                    </li>--}}
                                    @if(Auth::user()->is_super == 1)
                                        <li>
                                            <a href="{{ url('/private_list') }}">
                                                私信
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/my_message_comment?type=comment') }}">
                                                用户评轮
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{ url('/my_message_comment?type=reply') }}">
                                            评轮回复
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown"  id="navbar-my">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/my_like') }}">
                                            我的收藏
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/user_info') }}">
                                            个人信息
                                        </a>
                                    </li>
                                    {{--<li>
                                        <a href="{{ url('my_message') }}">
                                            我的消息
                                        </a>
                                    </li>--}}
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            退出登录
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
    </div>
    <br><br><br><div style="text-align:center;">Copyright © zzf &nbsp;&nbsp;&nbsp;&nbsp;邮箱：zzf@126.com</div><br><br><br><br><br>
    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
