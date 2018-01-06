@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Setting</div>

                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <td>
                                <a href="{{route('setting_config_form')}}">共通</a>
                            </td>
                        </tr>
                        @foreach($coins as $coin)
                            <tr>
                                <td>
                                    <a href="{{route('setting_coin_form', $coin->coin_type)}}">{{$coin->coin_type}} Setting</a>
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