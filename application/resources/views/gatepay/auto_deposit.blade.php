@extends('layouts.app')
@section('content')
{{--  @include('partials.nav')  --}}
<div class="row">
  @include('partials.sidebar')
  <div class="col-md-9 ">
    @include('flash')
    <div class="card" >
      <div class="header">
          <h2><strong>{{__('Auto Deposit') }}</strong></h2>
      </div>
      <div class="body">
        <form action="{{route('auto_deposit_post', app()->getLocale())}}" method="post" enctype="multipart/form-data" >
          {{csrf_field()}}
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                  <label>{{  __('Deposit Amount')  }}</label>
                  <input type="text" class="form-control" required name="amount">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-3">
              <button type="submit" class="btn btn-primary">
                {{__('Next')}}
              </button>
            </div>
          </div>
        </form>                          
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
@endsection