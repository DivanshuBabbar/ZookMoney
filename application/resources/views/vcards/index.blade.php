@extends('layouts.app')
@section('content')
{{-- @include('partials.nav') --}}
<div class="row">
  @include('partials.sidebar')
  <div class="col-md-9 " style="padding-right: 0">
        <div class="card">
            <div class="body">
                <div class="media border border-radius">
                    <div class="media-body">
                        <p>
                            <strong class="title pt-2 pl-2 float-left">{{__('Add a new virtual card')}} 
                            </strong>
                            <a href="{{ route('vcard.create',  app()->getLocale()) }}" class="btn btn-primary float-right mr-1">Add
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="header">
                <h2><strong>{{__('My virtual cards')}}</strong></h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table m-b-0">
                        <thead>
                            <tr>
                                <th>{{__('Id')}}</th>
                                <th class="hidden-xs text-center">{{__('Card Number')}}
                                </th>
                                <th class="hidden-xs text-center">{{__('Card Type')}}
                                </th>
                                <th class="hidden-xs text-center">{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vcards as $vcard)
                            <tr>
                                <td class="text-center">{{$vcard->rave_id}}</td>
                                <td class="text-center">{{$vcard->cardpan}}</td>
                                <td class="text-center">{{$vcard->type}}</td>
                                <td class="text-center">
                                    <a class="btn btn-success mr-1 text-white" href="{{url('/').'/'.app()->getLocale().'/vcard/details/'.$vcard->id}}">{{__('View Card')}}</a>
                                    <a class="btn btn-danger text-white" href="{{url('/').'/'.app()->getLocale().'/vcard/fund/'.$vcard->id}}" >{{__('Fund Card')}}</a>
                                </td>
                            </tr>
                            @empty
            
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($vcards->LastPage() != 1)
            <div class="footer">
                {{$vcards->links()}}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('footer')
  @include('partials.footer')
@endsection