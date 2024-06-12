@extends('layouts.app')

@section('content')
    <div class="row">
        @include('partials.sidebar')
         <div class="col-lg-9 col-md-12">
          <div class="row">
            <div class="col ">
              <div class="card">
                <div class="header">
                    <h2><strong>{{__('My Merchants')}}</strong></h2>
                   
                </div>
                <div class="body">
                  <div class="row">
                    <div class="col">
                      <a href="{{route('merchant.new' , app()->getLocale())}}" class="btn btn-primary float-right mb-4">{{__('Add Merchant')}}</a>
                    </div>
                  </div>
                  <div class="clearfix"></div>                          
                  @if( $merchants->total() > 0 )
                  <div class="table-responsive">
                    <table class="table table-striped" style="margin-bottom: 0;">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>{{__('MId')}}</th>
                          <th>{{__('Logo')}}</th>
                          <th>{{__('Cur Code')}}</th>
                          <th>{{__('Name')}}</th>
                          <th>{{__('Status')}}</th>
                          <th class="hidden-xs">{{__('Site Url')}}</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach( $merchants as $merchant)
                          <?php
                            $m_id = bin2hex($merchant->id);
                            if(strlen($merchant->id) == 2){
                              $mid = $m_id.'000000000'.$merchant->id;
                            }elseif(strlen($merchant->id) == 3){
                              $mid = $m_id.'0000000'.$merchant->id;
                            }else{
                              $mid = $m_id.'00000'.$merchant->id;
                            }
                          ?>
                            <tr>
                              <td scope="row">{{$loop->iteration}}</td>
                              <td scope="row">{{$mid}}</td>
                              <td ><img style="width: 45px; " src="{{$merchant->logo}}" alt="" class="rounded" loading="lazy"></td>
                              <td>{{$merchant->currency->code}}</td>
                              <td>{{$merchant->name}}</td>
                              <td>{{$merchant->status}}</td>
                              <td class="hidden-xs"><a href="{{$merchant->site_url}}" target="blank">{{$merchant->site_url}}</a></td>
                              <td><a href="{{url('/')}}/{{app()->getLocale()}}/merchant/{{$merchant->id}}/docs" class="btn btn-primary btn-sm">{{__('Integration Guide')}}</a></td>
                            </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  @endif
                </div>
              @if( $merchants->LastPage() > 1 )
                <div class="footer">
                      {{$merchants->links()}}
                </div>
              @endif
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection
@section('footer')
  @include('partials.footer')
@endsection