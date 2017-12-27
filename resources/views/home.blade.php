@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table">
                            <tr>
                                <th>
                                    COIN TYPE
                                </th>
                                <th>
                                    KR PRICE
                                </th>
                                <th>
                                    JP PRICE
                                </th>
                                <th>
                                    RATE
                                </th>
                                <th>
                                </th>
                            </tr>
                            @foreach ($data as $key => $value)
                                <tr>
                                    <td>
                                        <a href="{{route('simulation', $key)}}"> {{$key}}</a>
                                    </td>
                                    <td>
                                        {{$value['price_kr']}}
                                    </td>
                                    <td>
                                        {{$value['price_jp']}}
                                    </td>
                                    <td>
                                        {{$value['rate']}}
                                    </td>
                                    <td>
                                        @if (isset($value['max'])) MAX @endif
                                        @if (isset($value['min'])) MIN @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <table class="table">




                            <tr>
                                <td>日本円
                                </td>
                                <td>
                                    {{number_format($calc['amount_jp'])}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    日本円で買えるコイン
                                </td>
                                <td>
                                    {{number_format($calc['buy_coin_max'], 5)}} {{$calc['max_name']}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    送金後韓国で販売できるコイン
                                </td>
                                <td>
                                    {{number_format($calc['sell_coin_max'], 5)}} {{$calc['max_name']}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    コイン販売で得られる韓国ウォン
                                </td>
                                <td>
                                    {{number_format($calc['amount_kr'])}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    ウォンで買えるコイン
                                </td>
                                <td>
                                    {{number_format($calc['buy_coin_min'], 5)}} {{$calc['min_name']}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    送金後日本で販売できるコイン
                                </td>
                                <td>
                                    {{number_format($calc['sell_coin_min'], 5)}} {{$calc['min_name']}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    １回転で得られる日本円
                                </td>
                                <td>
                                    {{number_format($calc['final_amount_jp'])}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    差額
                                </td>
                                <td>
                                    {{number_format($calc['final_amount_jp'] - $calc['amount_jp'])}}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection