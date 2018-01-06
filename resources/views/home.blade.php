@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    現在のレート
                    <table class="table">
                    @foreach($data as $key => $value)
                            <tr>
                                <th>
                                    <a href="{{route('chart')}}/{{$value['coin_type']}}">
                                        {{$value['coin_type']}}
                                    </a>
                                </th>
                                <td>{{number_format($value['rate'], 4)}}</td>
                            </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
