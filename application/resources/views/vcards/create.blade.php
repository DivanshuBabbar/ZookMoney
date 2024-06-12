 @extends('layouts.app')
@section('content')
{{-- @include('partials.nav') --}}
<div class="row">
  @include('partials.sidebar')
    <div class="col-md-9 " style="padding-right: 0">
        @include('flash')
        <div class="card">
            <div class="header">
                <h2><strong>{{__('Add a new virtual card')}}</strong></h2>
            </div>
            <div class="body">
                <form method="POST" action="{{ route('vcard.store',  app()->getLocale()) }}">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label>
                                   <strong>
                                       {{__('Card creation fee : ')}}{{$fee}}$</strong>
                                </label>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label>
                                   <strong>
                                       {{__('Minimum Pre-fund amount : 5$ ')}}</strong>
                                </label>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 {{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label for="name">
                                {{__('Amount to pre-fund the card')}}
                            </label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                value="5" required="">
                            </div>
                            @if ($errors->has('amount'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 {{ $errors->has('holder') ? ' has-error' : '' }}">
                            <label for="holder">
                                {{__('Card holder\'s name')}}
                            </label>
                            <input type="text" class="form-control" id="holder" name="holder" 
                                required="">
                            @if ($errors->has('holder'))
                                <span class="help-block text-danger">
                                    <strong>
                                        {{ $errors->first('holder') }}      </strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                       <div class="col">
                                    <button type="submit" class="btn btn-primary float-right" id='submit'>
                                        {{__('submit')}}
                                    </button>
                                    <i class="loading-icon fa-lg fas fa-spinner fa-spin hide"></i>
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
     <script>
      $(document).ready(function() {
        $(".btn").on("click", function() {
          $(".result").text("");
          $(".loading-icon").removeClass("hide");
          $(".btn").attr("disabled", true);
          $(".btn-txt").text("Processing ...");
 
setTimeout(function(){

            $(".loading-icon").addClass("hide");
          $(".button").attr("disabled", false);
          $(".btn-txt").text("Submit");
          }, 5000);
          });
        });
        
    </script>

@endsection