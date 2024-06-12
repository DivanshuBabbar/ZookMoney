@extends('layouts.app')
@section('content')
{{-- @include('partials.nav') --}}
<div class="row">
    @include('partials.sidebar')
    <div class="col-md-9 " style="padding-right: 0">
        @include('flash')
        <div class="card">
            <div class="header">
                <h2><strong>{{__('Add Fund to a virtual card')}}</strong></h2>
            </div>
            <div class="body">
                <form method="POST" action="{{url('/').'/'.app()->getLocale().'/vcard/postFund/'.$id}}">
                    @csrf
                    <input type="hidden" name="id" value="@isset($id){{$id}}@endisset">
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 {{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label for="name">
                                {{__('Amount to fund')}}
                            </label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                value="1" required="">
                            </div>
                            @if ($errors->has('amount'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="submit" class="btn btn-primary float-right" value="{{__('Add Fund')}}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
  @include('partials.footer')
@endsection