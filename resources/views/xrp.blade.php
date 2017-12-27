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
                        <form class="form-horizontal" method="GET" action="{{ route('xrp') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('send_btc_amount') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">送るXRP量</label>

                                <div class="col-md-6">
                                    <input id="send_btc_amount" type="number" step="0.01" class="form-control" name="send_btc_amount" value="{{ old('send_btc_amount') }}" required autofocus>

                                    @if ($errors->has('send_btc_amount'))
                                        <span class="help-block">
							<strong>{{ $errors->first('send_btc_amount') }}</strong>
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
                                    1XRPの日本円価格(¥)
                                </th>
                                <td>
                                    {{number_format($jp_price)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    1XRPの韓国ウォン価格(W)
                                </th>
                                <td>
                                    {{number_format($kr_price)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    1XRP基準円ウォン為替
                                </th>
                                <td>
                                    {{number_format($one_jpy_to_btc_to_krw, 5)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    銀行為替
                                </th>
                                <td>
                                    {{number_format($one_jp_won_at_real, 5)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    送るXRP量
                                </th>
                                <td>
                                    {{number_format($send_btc_amount, 2)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    XRP price jp
                                </th>
                                <td>
                                    {{number_format($send_btc_price)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    最終的に得られる日本円の想定額
                                </th>
                                <td>
                                    {{number_format($final_jpy)}}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    XRP購入額と戻ってくる金額の差分
                                </th>
                                <td>
                                    {{number_format($gap)}}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    レート
                                </th>
                                <td>
                                    {{number_format($rate, 3)}}
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection