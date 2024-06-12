@extends('layouts.app')
@section('content')
<div class="row">
  @include('partials.sidebar')
    <div class="col-md-9 " style="padding-right: 0">
        @include('flash')
        <div class="card">
            <div class="row api_response" style="">
                @if(session()->has('detail'))
                    @php $res = session()->get('detail') @endphp
                    @foreach($res['response']['TXN_EPIN']  as $key => $item)
                        <div class="alert alert-success">
                            <span class="font-weight:600">Mobile Network : {{$item['mobilenetwork']}}</span><br>
                            <span class="font-weight:600">Serial No. : {{$item['sno']}}</span><br>
                            <span class="font-weight:600">Pin No. : {{$item['pin']}}</span><br>
                            <span class="font-weight:600">Amount : {{$item['amount']}}</span><br>
                            <span class="font-weight:600">Transaction Date : {{$item['transactiondate']}}</span><br>
                        </div>
                    @endforeach
                @endif
            </div>
            <h5 class="card-header">@lang('Buy Epin')</h5>
            <div class="card-body bg-white">
                @php $transaction_id = session()->get('transaction_id') @endphp
                @if(isset($transaction_id))
                    <a href="{{url('/').'/'.app()->getLocale().'/generatePDF'}}/{{$transaction_id}}" class="btn btn-info"><i class="fa fa-print"></i>Print</a>
                @endif
                <form action="{{url('/').'/'.app()->getLocale().'/buyEpinAction'}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="color: black;">Mobile Network</label>
                                <select class="form-control" id="MobileNetwork" name="MobileNetwork" required="">
                                    <option value="">--Select--</option>
                                    <option value="01">MTN</option>
                                    <option value="02">Glo</option>
                                    <option value="03">Etisalat</option>
                                    <option value="04">Airtel</option>
                                </select>
                            </div>
                        </div>
                         <div class="col-sm-6">
                            <div class="form-group">
                                <label style="color: black;">Amount</label>
                                <select class="form-control" id="Value" name="Value" required="">
                                    <option value="">--Select--</option>
                                    <option value="100">#100</option>
                                    <option value="200">#200</option>
                                    <option value="500">#500</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="color: black;">Quantity</label>
                                <input type="number" class="form-control" required="" min="1" max="100" name="Quantity">
                            </div>
                        </div>
                        <input type="hidden" class="form-control meter_number" required="" name="UserID">
                        <input type="hidden" class="form-control meter_number" required="" name="UserID">
                    </div>
                    <div class="row" style="">
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-info">Submit</button>
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