@extends('layouts.app')

@section('content')
<div class="container">

    <a href="{{ url('/save') }}"><button type="button" class="btn btn-success">新增</button></a>
    <table class="table">
        {{--<caption>基本的表格布局</caption>--}}
        <thead>
        <tr>
            <th>名称</th>
            <th>国家</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($yqs as $yq)
            <tr>
                <td>{{ $yq->name }}</td>
                <td>{{ $yq->nation }}</td>
                <td><button type="button" class="btn btn-danger">删除</button>
                    <a href="{{ url('/save?id='.$yq->id) }}"><button type="button" class="btn btn-warning">编辑</button></a>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
