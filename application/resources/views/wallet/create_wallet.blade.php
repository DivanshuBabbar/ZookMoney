@extends('layouts.app')

@section('styles')
    @include('wallet.styles')
@endsection

@section('content')
	<div class="row clearfix">
		
		<div class="col-md-12 " >
        	@include('partials.flash')
    	</div>

    </div>
	<div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                     <div class="header">
                        <h2><strong>{{__('Create a wallet')}}</strong></h2>
                    </div>
                    <div class="body block-header">
                        <div class="row">
                            <div class="col">
                               <ul id="glbreadcrumbs-two">
                                   
                                    <li><a href="#" class="a"><strong>1.</strong> Select method.</a></li>
                                    <li><a href="#"><strong>2.</strong>Register.</a></li>
                                </ul>
                            </div>            
                          
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="row clearfix">
		<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
            <a href="{{url('/')}}/{{app()->getLocale()}}/wallet/create/{{$method->id}}">
                <div class="card product_item">
                    <div class="body text-center">
                        <div class="cp_img">
                            <img src="{{$method->thumbnail}}" alt="Product" class="img-fluid">
                       
                        </div>
                        <h6 style="margin-top: 10px">{{$method->name}}</h6>
                     
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">

            <div class="card bg-white">
                <div class="header">
                    <h2><strong>{{__('Register your')}}</strong> {{$method->accont_identifier_mechanism}}</h2>
                    
                </div>
                <form method="post" action="{{Route('add.method.wallet', app()->getLocale())}}">
                    {{csrf_field()}}
                    <input type="hidden" name="transfer_method_id" value="{{$method->id}}">
                <div class="body" style="padding-top: 0;">
                  <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-lg-12 ">
                            <label for=""></label>
                            <div class="" role="alert" style="color: #383d41">
                                <div class="form-group ">
                                  <input type="text" class="form-control" id="accont_identifier_mechanism_id" name="accont_identifier_mechanism_id"  required="">
                                </div>

                                 @if ($errors->has('transfer_method_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('transfer_method_id') }}</strong>
                                    </span>
                                @endif
                                  @if ($errors->has('accont_identifier_mechanism_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('accont_identifier_mechanism_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col">
                                    <input type="submit" class="btn btn-primary float-right" value="{{__('Register')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer')
	@include('partials.footer')
@endsection
