@extends('layouts.app')
@section('content')
{{--  @include('partials.nav')  --}}
<div class="row">
@include('partials.sidebar')
<div class="col-md-9 ">
   @include('partials.flash')
   <div class="card bg-light">
      <div class="header" style="background-color:#ffffff;">
     <!--    <h2>{{__('Select an adress to send the money from withdraw')}} </h2>
      </div>
      <div class="body"style="background-color:#ffffff;">
         <div class="row mb-4">
            <div class="col">
               <a href="javascript:void(0)" class="btn btn-primary btn-round bg-blue   ">+ Add account to receive withdraw</a>
            </div>
         </div>
         <div class="clearfix"></div>
      </div>
   </div> -->
   <div class="row">
      <div class="col-lg-12">
         <div class="row">
            <div class="col" >
               <div class="card bg-light" >
                  <div class="header" style="background-color:#ffffff;">
                     <h2><strong>{{ __('Select withdraw method') }}</strong></h2>
                  </div>
                  <div class="body"style="background-color:#ffffff;">
                     <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                           <form action="{{ route('create.withdrawal.method', app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                              @csrf
                              <div class="row">
                                 <div class="col-lg-6">
                                    <div class="form-group">
                                       <label>Amount</label>
                                       <input type="text" id="withdrawAmount" name="amount" class="form-control" required>
                                    </div>
                                 </div>
                                 <div class="col-lg-6">
                                    <div class="form-group">
                                       <label>Withdrawal Method</label>
                                       <select class="form-control" name="withdrawal_method_id" required>
                                          <option data-eligible="" data-currency="" value="">--select--</option>
                                          @if(!empty($withdrawalMethod))
                                          @foreach($withdrawalMethod as $row)
                                             <option data-eligible="{{$row->is_eligible}}" data-currency="{{$row->currency_id}}" data-name="{{$row->name}}" value="{{$row->id}}"  {{ empty($row->is_eligible) ? "style=color:#bc0000;" : "" }}>
                                                {{$row->name}} {{ empty($row->is_eligible) ? ' - ineligible' : '' }}
                                          </option>
                                          @endforeach
                                          @endif
                                       </select>
                                       <div class="mt-2">
                                          <p id="exchangeHelper"></p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              
                              <div class="row mt-1">
                                  <div class="col-lg-6 detail_bank">
                                      <div class="form-group">
                                         <label class="form-label" for="textAreaExample2">Detail</label>
                                          <textarea class="form-control" name="detail" rows="2"></textarea>
                                      </div>
                                  </div>
                                  <div class="col-lg-6">
                                    <div class="form-group bank_class d-none">
                                       <label>Withdrawal Account</label>
                                       <select class="form-control" name="withdrawal_bank" required>
                                          <option>--select--</option>
                                          @if(!empty($whitelistacount))
                                          @foreach($whitelistacount as $row)
                                             <option value="{{$row->name}} - {{ $row->account_number}} - {{ $row->ifsc_number}}">
                                                {{$row->name}} - {{ $row->account_number}} - {{ $row->ifsc_number}} 
                                          </option>
                                          @endforeach
                                          @endif
                                       </select>
                                       <div class="mt-2">
                                          <p id="exchangeHelper"></p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <button type="submit" class="btn btn-primary btn-round bg-blue m-l-10 eligible-row" >{{__('Submit')}}</button>
                           </form>
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Terms and conditions: </label>
                                <div style="overflow: auto;max-height: 400px">{!! general_setting('withdrawal_terms_and_conditions') !!}</div>
                            </div>
                     </div>
                    </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('js')
<script>
   let exchanges = @json($exchanges);
   let withdrawAmount = $("#withdrawAmount").val();
   let currency = $("select[name='withdrawal_method_id'] option:selected").data('currency');
   let conversionText = ''; 

   $("select[name='withdrawal_method_id']").change(function() {
      currency  = $(this).find('option:selected').data('currency')
      currency_name  = $(this).find('option:selected').data('name')
      if (currency_name == 'INR - Indian Bank transfer') {
         $('.detail_bank').addClass('d-none');
         $('.bank_class').removeClass('d-none');
      }else{
         $('.bank_class').addClass('d-none');
         $('.detail_bank').removeClass('d-none');

      }
      let eligible = $(this).find('option:selected').data('eligible')

      if(eligible != '' || eligible != 0) {
            $('.eligible-row').show();
      } else {
            $('.eligible-row').hide();
      }
      staticConversionOnlyForInrToUsdt();
   });
   $("#withdrawAmount").keyup(function() {
      withdrawAmount = $(this).val();
      staticConversionOnlyForInrToUsdt();
   });

   // not in work anymore. But need this method later.
   // function changeConversionText() {
   //    
   //    
   //    
   //    $("select[name='withdrawal_method_id'] option:selected").val();
   //    if(!withdrawAmount || !currency || !exchanges[currency]) {
   //       $("#exchangeHelper").text('')
   //       return;
   //    }
   //    conversionText = `${withdrawAmount} ${exchanges[currency]['first_currecny_name']} 
   //             : ${parseFloat(withdrawAmount * exchanges[currency]['rate']).toFixed(3)} ${exchanges[currency]['second_currency_name']}`
   //    $("#exchangeHelper").text(conversionText)
   // }

   // Static conversion text implementation.
   function staticConversionOnlyForInrToUsdt() {

      let mId = $("select[name='withdrawal_method_id'] option:selected").val();
      if(mId != {{ config('app.static_inr_to_usdt_withdrawal_method_id') }}) {
         $("#exchangeHelper").text('')

         return;
      }
      let currency = {{ config('app.static_inr_to_usdt_withdrawal_usdt_currency_id') }};
      
      if(!withdrawAmount || !currency || !exchanges[currency]) {
         $("#exchangeHelper").text('')

         return;
      }
      conversionText = `${withdrawAmount} ${exchanges[currency]['first_currecny_name']} 
               : ${parseFloat(withdrawAmount * exchanges[currency]['rate']).toFixed(3)} ${exchanges[currency]['second_currency_name']}`
      $("#exchangeHelper").text(conversionText)
   }
</script>
@endsection
@section('footer')
@include('partials.footer')
@endsection