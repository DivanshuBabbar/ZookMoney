@extends('layouts.app')
@section('content')
{{-- @include('partials.nav') --}}
<div class="row">
    @include('partials.sidebar')
    <div class="col-md-9 " style="padding-right: 0">
        <div class="card">
            <div class="body">
                <a href="{{url('/').'/'.app()->getLocale().'/exchange_currency'}}" class="btn btn-primary  mr-1">Exchange Currency
                </a>
            </div>
        </div>
        <div class="card">
            <div class="header">
                <h2><strong>{{__('Exchange Currencies List')}}</strong></h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table m-b-0">
                        <thead>
                            <tr>
                                <th>{{__('SN')}}</th>
                                <th class="hidden-xs text-center">{{__('From Currency')}}
                                </th>
                                <th  class="hidden-xs text-center">{{__('To Currency')}}
                                </th>
                                <th class="hidden-xs text-center">{{__('Amount')}}</th>
                                <th class="hidden-xs text-center">{{__('Exchange rate')}}</th>
                                <th class="hidden-xs text-center">{{__('Total amount')}}</th>
                                <th class="hidden-xs text-center">{{__('Date')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exchangeCurrenyDetail as $key=> $row)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">@isset($row->from_currency_id){{$row->first_currency_name->name}}@endisset</td>
                                <td class="text-center">@isset($row->to_currency_id){{$row->second_currency_name->name}}@endisset</td>
                                <td class="text-center">@isset($row->amount){{$row->amount}}@endisset</td>
                                <td class="text-center">@isset($row->exchange_rate){{$row->exchange_rate}}@endisset</td>
                                <td class="text-center">@isset($row->total_amount){{$row->total_amount}}@endisset</td>
                                <td class="text-center">{{ localDate($row->created_at) }}</td>
                                <!-- <td class="text-center">
                                    <a href="{{url('/').'/'.app()->getLocale().'/generatePDF'}}/{{$row->id}}" class="btn btn-info">
                                        <i class="fa fa-print"></i>
                                        Print Again
                                    </a> -->
                                </td>
                            </tr>
                            @empty
            
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($exchangeCurrenyDetail->LastPage() != 1)
            <div class="footer">
                {{$exchangeCurrenyDetail->links()}}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('footer')
  @include('partials.footer')
@endsection