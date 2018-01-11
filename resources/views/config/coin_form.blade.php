@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{$coin->coin_type}} Setting</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('setting_coin_submit', $coin->coin_type) }}">
                        {{ csrf_field() }}
                        <input type="hidden" value="{{$coin->id}}">
                        <!-- coin_type LABEL -->
                        <div class="form-group{{ $errors->has('coin_type') ? ' has-error' : '' }}">
                            <label for="{{'coin_type'}}" class="col-md-4 control-label">{{$coin->coin_type}}</label>
                        </div>

                        <!-- enable radio true false -->
                        <div class="form-group{{ $errors->has('enable') ? ' has-error' : '' }}">
                            <label for="{{'enable'}}" class="col-md-4 control-label">相場トラッキング</label>

                            <div class="col-md-6">
                                <input type="radio" name="enable" value="{{true}}" class="radio-inline" @if ($coin->enable) checked @endif>する
                                <input type="radio" name="enable" value="{{false}}" class="radio-inline" @if (!$coin->enable) checked @endif>しない

                                @if ($errors->has('enable'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('enable') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- buy_flag radio true false -->
                        <div class="form-group{{ $errors->has('buy_flag') ? ' has-error' : '' }}">
                            <label for="{{'buy_flag'}}" class="col-md-4 control-label">売買対象</label>

                            <div class="col-md-6">
                                <input type="radio" name="buy_flag" value="{{true}}" class="radio-inline" @if ($coin->buy_flag) checked @endif>する
                                <input type="radio" name="buy_flag" value="{{false}}" class="radio-inline" @if (!$coin->buy_flag) checked @endif>しない

                                @if ($errors->has('buy_flag'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('buy_flag') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- buy_market_type STORE EXCHANGE -->
                        <div class="form-group{{ $errors->has('buy_market_type') ? ' has-error' : '' }}">
                            <label for="{{'buy_market_type'}}" class="col-md-4 control-label">日本</label>

                            <div class="col-md-6">
                                <input type="radio" name="buy_market_type" value="STORE" class="radio-inline" @if ($coin->buy_market_type == 'STORE') checked @endif>販売所
                                <input type="radio" name="buy_market_type" value="EXCHANGE" class="radio-inline" @if ($coin->buy_market_type == 'EXCHANGE') checked @endif>取引所

                                @if ($errors->has('buy_market_type'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('buy_market_type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- sell_market_type STORE EXCHANGE -->
                        <div class="form-group{{ $errors->has('sell_market_type') ? ' has-error' : '' }}">
                            <label for="{{'sell_market_type'}}" class="col-md-4 control-label">{{'sell_market_type'}}</label>

                            <div class="col-md-6">
                                <input type="radio" name="sell_market_type" value="STORE" class="radio-inline" @if ($coin->sell_market_type == 'STORE') checked @endif>販売所
                                <input type="radio" name="sell_market_type" value="EXCHANGE" class="radio-inline" @if ($coin->sell_market_type == 'EXCHANGE') checked @endif>取引所

                                @if ($errors->has('sell_market_type'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('sell_market_type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- track_amount text -->
                        <div class="form-group{{ $errors->has('track_amount') ? ' has-error' : '' }}">
                            <label for="{{'track_amount'}}" class="col-md-4 control-label">コイン数量</label>

                            <div class="col-md-6">
                                <input id="{{'track_amount'}}" type="text" class="form-control" name="{{'track_amount'}}" value="{{ (old('track_amount')) ? old('track_amount') : $coin->track_amount}}" required>

                                @if ($errors->has('track_amount'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('track_amount') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- decimal_number number tick 1 -->
                        <div class="form-group{{ $errors->has('decimal_number') ? ' has-error' : '' }}">
                            <label for="{{'decimal_number'}}" class="col-md-4 control-label">数量小数点切り捨て桁</label>

                            <div class="col-md-6">
                                <input id="{{'decimal_number'}}" type="text" class="form-control" name="{{'decimal_number'}}" value="{{ (old('decimal_number')) ? old('decimal_number') : $coin->decimal_number}}" required>

                                @if ($errors->has('decimal_number'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('decimal_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- buy_minimum_amount -->
                        <div class="form-group{{ $errors->has('buy_minimum_amount') ? ' has-error' : '' }}">
                            <label for="{{'buy_minimum_amount'}}" class="col-md-4 control-label">取引可能最小コイン数</label>

                            <div class="col-md-6">
                                <input id="{{'buy_minimum_amount'}}" type="text" class="form-control" name="{{'buy_minimum_amount'}}" value="{{ (old('buy_minimum_amount')) ? old('buy_minimum_amount') : $coin->buy_minimum_amount}}" required>

                                @if ($errors->has('buy_minimum_amount'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('buy_minimum_amount') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- 'send_minimum_amount' => 0.5,-->
                        <div class="form-group{{ $errors->has('send_minimum_amount') ? ' has-error' : '' }}">
                            <label for="{{'send_minimum_amount'}}" class="col-md-4 control-label">送金可能最小コイン数</label>

                            <div class="col-md-6">
                                <input id="{{'send_minimum_amount'}}" type="text" class="form-control" name="{{'send_minimum_amount'}}" value="{{ (old('send_minimum_amount')) ? old('send_minimum_amount') : $coin->send_minimum_amount}}" required>

                                @if ($errors->has('send_minimum_amount'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('send_minimum_amount') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- 'sell_minimum_amount' => 0.5, -->
                        <div class="form-group{{ $errors->has('sell_minimum_amount') ? ' has-error' : '' }}">
                            <label for="{{'sell_minimum_amount'}}" class="col-md-4 control-label">売却可能最小コイン数</label>

                            <div class="col-md-6">
                                <input id="{{'sell_minimum_amount'}}" type="text" class="form-control" name="{{'sell_minimum_amount'}}" value="{{ (old('sell_minimum_amount')) ? old('sell_minimum_amount') : $coin->sell_minimum_amount}}" required>

                                @if ($errors->has('sell_minimum_amount'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('sell_minimum_amount') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- 'buy_fee_rate' => 0.15, -->
                        <div class="form-group{{ $errors->has('buy_fee_rate') ? ' has-error' : '' }}">
                            <label for="{{'buy_fee_rate'}}" class="col-md-4 control-label">購入時手数料</label>

                            <div class="col-md-6">
                                <input id="{{'buy_fee_rate'}}" type="text" class="form-control" name="{{'buy_fee_rate'}}" value="{{ (old('buy_fee_rate')) ? old('buy_fee_rate') : $coin->buy_fee_rate}}" required>

                                @if ($errors->has('buy_fee_rate'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('buy_fee_rate') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- 'sell_fee_rate' => 0.15, -->
                        <div class="form-group{{ $errors->has('sell_fee_rate') ? ' has-error' : '' }}">
                            <label for="{{'sell_fee_rate'}}" class="col-md-4 control-label">売却手数料</label>

                            <div class="col-md-6">
                                <input id="{{'sell_fee_rate'}}" type="text" class="form-control" name="{{'sell_fee_rate'}}" value="{{ (old('sell_fee_rate')) ? old('sell_fee_rate') : $coin->sell_fee_rate}}" required>

                                @if ($errors->has('sell_fee_rate'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('sell_fee_rate') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- 'send_fee' => 0.001, -->
                        <div class="form-group{{ $errors->has('send_fee') ? ' has-error' : '' }}">
                            <label for="{{'send_fee'}}" class="col-md-4 control-label">送金手数料</label>

                            <div class="col-md-6">
                                <input id="{{'send_fee'}}" type="text" class="form-control" name="{{'send_fee'}}" value="{{ (old('send_fee')) ? old('send_fee') : $coin->send_fee}}" required>

                                @if ($errors->has('send_fee'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('send_fee') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection