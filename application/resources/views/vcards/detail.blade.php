@extends('layouts.app')
@section('content')
{{-- @include('partials.nav') --}}
<div class="row">
    @include('partials.sidebar')
    <div class="col-md-9 " style="padding-right: 0">
    <div class="card">
            <div class="header">
                <h2><strong>{{__('Add a new virtual card')}}</strong></h2>
            </div>
            <div class="body">
                <div id="card-design" class="p-4 d-flex text-white flex-column mx-auto mb-4" style="max-width:300px;border-radius: .55em; background: #2a43aa;">
                    <div class="mb-4 d-flex flex-row justify-content-between">
                        <span class="h5">{{general_setting('site_name')}}</span>
                        <span>
                            @isset($data['current_balance']){{'$'.$data['current_balance']}}@endisset
                        </span>
                    </div>   
                    <div class="d-flex mb-4 flex-row justify-content-between">
                        <div>{{substr($vcard->cardpan,0,4)}}</div>
                        <div class="mx-1">{{substr($vcard->cardpan,4,4)}}</div>
                        <div class="mx-1">{{substr($vcard->cardpan,8,4)}}</div>
                        <div>{{substr($vcard->cardpan,12,4)}}</div>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-row">
                                <span class="mr-2" style="font-size:8px;">{{__('VALID')}}<br>{{__('TILL')}}</span>
                                <div class="align-self-center">{{$vcard->expiration}}</div>
                            </div>
                            <div>
                                @isset($data['card_holder_name']){{$data['card_holder_name']}}@endisset
                            </div>
                        </div>
                        <img class="align-self-end mb-2" src="https://strowallet.com/assets/master.png" alt="" width="15%" height="15%">
                    </div>
                </div>
                <div class="card">
                    <div class="header">
                        <h2 class="text-black"><strong>{{__('current_balance: ').$data['current_balance'].'$'}}</strong></h2>
                    </div>
                    <div class="body d-flex flex-column">
                        <div class="d-flex flex-row">
                            <span class="mr-3">{{__('Card Number')}}</span>&nbsp;&nbsp;
                            <span><strong>{{substr($vcard->cardpan,0,4).'-'.substr($vcard->cardpan,4,4).'-'.substr($vcard->cardpan,8,4).'-'.substr($vcard->cardpan,12,4)}}</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3">{{__('CVV')}}</span>&nbsp;&nbsp;
                            <span><strong>{{$data['cvc']}}</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3">{{__('Card Type')}}</span>&nbsp;&nbsp;
                            <span><strong>{{$vcard->type}}</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3">{{__('Valid Until')}}</span>&nbsp;&nbsp;
                            <span><strong>{{$vcard->expiration}}</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3">{{__('State')}}</span>&nbsp;&nbsp;
                            <span><strong>CA</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3">{{__('Country')}}</span>&nbsp;&nbsp;
                            <span><strong>USA</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3">{{__('Postal code')}}</span>&nbsp;&nbsp;
                            <span><strong>94105</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3">{{__('Address 1')}}</span>&nbsp;&nbsp;
                            <span><strong>1088 Roosevelt Street</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3">{{__('Phone Number')}}</span>&nbsp;&nbsp;
                            <span><strong>+1(857) 5745195</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3">{{__('Date of creation')}}</span>&nbsp;&nbsp;
                            <span><strong>{{$vcard->created_at}}</strong></span>
                        </div>
                    </div>
                    <div class="footer">
                        <div class="float-right mr-3">
                            <a href="{{url('/').'/'.app()->getLocale().'/vcard/fund/'.$vcard->id}}" class="btn btn-primary">Add Funds
                            </a>
                        </div>
                         <!-- <div class="float-right mr-3">
                            <a href="https://www.payzoft.com/page/9" class="btn btn-primary">Withdraw from Card
                            </a>
                        </div> -->
                    </div>
                </div>
                <div class="card">
                    <div class="header">
                        <h2><strong>{{__('Card Transactions')}}</strong></h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table m-b-0">
                                <thead>
                                    <tr>
                                        <th class="hidden-xs text-center">{{__('Transaction Type')}}</th>
                                        <th class="hidden-xs text-center">{{__('Details')}}</th>
                                        <th class="hidden-xs text-center">{{__('Amount')}}
                                        </th>
                                        <!-- <th class="hidden-xs text-center">{{__('Fee')}}
                                        </th> -->
                                        <th class="hidden-xs text-center">{{__('Date')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tranxs as $tranx)
                                    <tr>
                                        <td class="text-center">{{$tranx['type']}}</td>
                                        <td class="text-center">{{$tranx['narration']}}</td>
                                        <!-- <td class="text-center">{{$tranx['amount']}}</td> -->
                                        <td class="text-center">{{$tranx['amount']}} {{$tranx['currency']}}</td>
                                        <td class="text-center">
                                            {{date('Y-m-d  H:i:s',strtotime($tranx['narration']))}}
                                        </td>
                                    </tr>
                                    @empty
                    
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($tranxs->LastPage() != 1)
                    <div class="footer">
                        {{$tranxs->links()}}
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