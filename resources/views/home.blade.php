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
                                    {{$value['coin_type']}}
                                </th>
                                <td>{{number_format($value['rate'], 4)}}</td>
                                <td>
                                    <a href="{{route('chart')}}/{{$value['coin_type']}}">
                                        Chart
                                    </a>
                                </td>
                                <td>
                                    <a href="{{route('simulation',$value['coin_type'])}}">
                                        Simulation
                                    </a>
                                </td>
                            </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
