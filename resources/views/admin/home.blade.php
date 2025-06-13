@extends('admin/blade/master')
@section('source_css')

@endsection
@section('main_css')
<style>
    .section1_row{
        width: 100%;
        display: flex;
        margin: auto;
        flex-wrap: wrap;
    }
    .section1_col{
        min-width: 224px;
        margin:0 5px;
        background-color: wheat;
        display: flex;
        flex-wrap: wrap;
        border:2px solid #d8d8d8;
        border-radius: 5px;
        text-decoration: none;
        color:black;
    }
    .section1_col:hover{
        background-color: #fbbd46;
        transition: background-color .25s;
        color:black;
    }
    .number{
        font-size: xx-large;
    }
    .DataBlock i{
        width: 20%;
        font-size: x-large;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color:white;
    }
    .section1_text{
        width: 80%;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        border-bottom: 1px solid white;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        /*width: 100px;*/
    }

    th:first-child,
    td:first-child {
        width: 10px;
    }

    th {
        background-color: #f2f2f2;
    }

    td {
        background-color: white;
    }
    ::selection {
        background: #7ac0ec !important;
        color: black;
    }
</style>
@endsection
@section('main_section')
<div class="">
    <h3>租車訂單資訊</h3>
    <div class="section1_row">
        <a href="{{route('admin.rental','all')}}" class="section1_col DataBlock">
            <i class="fa fa-clone" aria-hidden="true" style="background-color: #4895fc"></i>
            <div class="section1_text">
                <div class="number" style="color:#4895fc">{{ $total }}</div>
                <div class="title">訂單總數</div>
            </div>
        </a>

        <a href="{{route('admin.rental','pending')}}" class="section1_col DataBlock">
            <i class="fa fa-hourglass-start" aria-hidden="true" style="background-color: #828282"></i>
            <div class="section1_text">
                <div class="number" style="color:#828282">{{ $counts['pending'] ?? 0 }}</div>
                <div class="title">尚未審核</div>
            </div>
        </a>

        <a href="{{route('admin.rental','active_not_started')}}" class="section1_col DataBlock">
            <i class="fa fa-cog" aria-hidden="true" style="background-color: #ffffff;color:black"></i>
            <div class="section1_text">
                <div class="number" style="color:#ffffff">{{ $counts['active_not_started'] ?? 0 }}</div>
                <div class="title">審過-未開始</div>
            </div>
        </a>

        <a href="{{route('admin.rental','active_ongoing')}}" class="section1_col DataBlock">
            <i class="fa fa-cog" aria-hidden="true" style="background-color: #ff8c00"></i>
            <div class="section1_text">
                <div class="number" style="color:#ff8c00">{{ $counts['active_ongoing'] ?? 0 }}</div>
                <div class="title">審過-進行中</div>
            </div>
        </a>

        <a href="{{route('admin.rental','completed')}}" class="section1_col DataBlock">
            <i class="fa fa-check-circle" aria-hidden="true" style="background-color: #90ed7d"></i>
            <div class="section1_text">
                <div class="number" style="color:#90ed7d">{{ $counts['completed'] ?? 0 }}</div>
                <div class="title">訂單完成</div>
            </div>
        </a>

        <a href="{{route('admin.rental','cancelled')}}" class="section1_col DataBlock">
            <i class="fa fa-window-close" aria-hidden="true" style="background-color: #ff6d6d"></i>
            <div class="section1_text">
                <div class="number" style="color:#ff6d6d">{{ $counts['cancelled'] ?? 0 }}</div>
                <div class="title">訂單取消</div>
            </div>
        </a>
    </div>
</div>
<div class="mt-3">
    <h3>租賃結束未完成的訂單</h3>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Gmail</th>
            <th>電話</th>
            <th>車型</th>
            <th>車牌號碼</th>
            <th>租用開始</th>
            <th>租用結束</th>
            <th>狀態</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($expried_datas))
        @foreach($expried_datas as $expried_data)
            <tr>
                <td>{{ $expried_data->id }}</td>
                <td>{{ $expried_data->gmail }}</td>
                <td>{{ $expried_data->phone }}</td>
                <td>{{ $expried_data->full_model_name }}</td>
                <td>{{ $expried_data->plate_number }}</td>
                <td>{{ $expried_data->start_date }}</td>
                <td>{{ $expried_data->end_date }}</td>
                <td> @if($expried_data->rental_status == "pending")
                        未審核
                    @elseif($expried_data->rental_status == "active")
                        審過-進行中
                    @else
                        {{$expried_data->rental_status}}
                    @endif
                </td>
            </tr>
        @endforeach
        @endif
        </tbody>
    </table>
</div>
@endsection
