@extends('layouts.app')
@section('content')
    <div class="row">
        @include('partials.sidebar')
         <div class="col-lg-9 col-md-12">
             
            <div class="row">
                <div class="col" >
                 
                   <div class="card " >
                    <div class="header">
                        <h2><strong>{{ __('Automatic deposit') }}</strong></h2>
                    </div>
                    <div class="body">
                     
                        <div class="row">
                            <div class="col">
                                <p>You can add credit to your wallets automatically using popular payment methods.</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <!-- <a href="https://paypage-demo.livelyworks.net/" class="btn btn-primary btn-round  btn-simple  m-l-10"> 
                                    {{__('Add Funds')}}
                                </a> -->
                                <a href="#" class="btn btn-primary btn-round  btn-simple  m-l-10"> 
                                    {{__('Add Funds')}}
                                </a>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col" >
                 
                   <div class="card bg-light" >
                    <div class="header">
                        <h2><strong>{{ __('Select manual deposit method') }}</strong></h2>
                    </div>
                    <div class="body">
                     
                      <div class="row">
                        @forelse($transferMethods as $method)
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <a href="{{url('/')}}/{{app()->getLocale()}}/deposit/m/{{$method->id}}">
                                <div class="card product_item">
                                    <div class="body text-center">
                                        <div class="cp_img">
                                            <img src="{{$method->thumbnail}}" alt="Product" class="img-fluid">
                                       
                                        </div>
                                        <h6 style="margin-top: 10px">{{$method->name}} </h6>
                                     
                                    </div>
                                </div>
                            </a>                
                        </div>
                        @empty
                        @endforelse
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
@endsection
