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
                        <form class="form-horizontal" method="GET" action="{{ route('simulation', $data['coin_type']) }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('send_btc_amount') ? ' has-error' : '' }}">
                                <label for="coin_name" class="col-md-4 control-label">COIN種類</label>
                                <div class="col-md-6">
                                    <input id="coin_name" type="text" name="coin_name" value="{{$data['coin_type']}}" class="form-control">
                                </div>

                                <label for="amount" class="col-md-4 control-label">送るCOIN 量</label>

                                <div class="col-md-6">
                                    <input id="amount" type="text" class="form-control" name="amount" value="{{ (old('amount'))? old('amount') : $data['buy_amount'] }}" required autofocus>

                                    @if ($errors->has('amount'))
                                        <span class="help-block">
							                <strong>{{ $errors->first('amount') }}</strong>
						                </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                        <table class="table">
                            <tr>
                                <th>
                                    1{{$data['coin_type']}}の日本円価格(¥)
                                </th>
                                <td>
                                    {{number_format($data['jp_price'])}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    1{{$data['coin_type']}}の韓国ウォン価格(W)
                                </th>
                                <td>
                                    {{number_format($data['kr_price'])}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    1{{$data['coin_type']}}基準円ウォン為替
                                </th>
                                <td>
                                    {{number_format(($data['jp_price'] / $data['kr_price']), 5)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    銀行為替
                                </th>
                                <td>
                                    {{number_format($data['cash_rate'], 5)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    送るコイン量
                                </th>
                                <td>
                                    {{number_format($data['send_amount'], 2)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{$data['coin_type']}} 購入費用(¥)
                                </th>
                                <td>
                                    {{number_format($data['input_jp'])}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    最終的に得られる日本円の想定額
                                </th>
                                <td>
                                    {{number_format($data['return_jpy'])}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{$data['coin_type']}} 購入額と戻ってくる金額の差分
                                </th>
                                <td>
                                    {{number_format($data['gap'])}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    期待レート
                                </th>
                                <td>
                                    {{number_format($data['rate'])}}
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection