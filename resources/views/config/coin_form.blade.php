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

                        <div class="form-group{{ $errors->has($config->name) ? ' has-error' : '' }}">
                            <label for="{{$config->name}}" class="col-md-4 control-label">{{$config->jp_name}}</label>

                            <div class="col-md-6">
                                <input id="{{$config->name}}" type="text" class="form-control" name="{{$config->name}}" value="{{ (old($config->name)) ? old($config->name) : $config->value }}" required>
                                <input name="id" type="hidden" value="{{$config->id}}">

                                @if ($errors->has($config->name))
                                    <span class="help-block">
                                    <strong>{{ $errors->first($config->name) }}</strong>
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