@extends('admin.layouts.master')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark"> {{$page_title}}</h1>
            </div>
            <div class="col-sm-12">
                   @include('flash')
               </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
               </ol>
            </div>
         </div>
      </div>
   </div>
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <!-- <div class="card-header"> -->
                  <!-- <h1 class="card-title mx-4 mt-3">{{$page_title}}</h1> -->
                  <!-- </div> -->
                  <div class="card-body">
                     <div class="row">
                        <div class="col-lg-12 col-xs-12">
                           <form action="{{route('admin.post_setting')}}" method="POST">
                              @csrf
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Website Name</label>
                                       <input type="text" name="setting[site_name]" class="form-control m-1" required value="{{general_setting('site_name')}}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Website url</label>
                                       <input type="text" name="setting[site_url]" class="form-control m-1" required value="@isset($site_url){{$site_url}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Admin email</label>
                                       <input type="email" name="setting[admin_email]" class="form-control m-1" required value="@isset($admin_email){{$admin_email}}@endisset">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Website title</label>
                                       <input type="text" name="setting[title]" class="form-control m-1" required value="@isset($title){{$title}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Merchant Fixed Fee</label>
                                       <input type="text" name="setting[merchant_fixed_fee]" class="form-control m-1" value="@isset($merchant_fixed_fee){{$merchant_fixed_fee}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Merchant Percentage Fee</label>
                                       <input type="text" name="setting[merchant_percentage_fee]" class="form-control m-1" value="@isset($merchant_percentage_fee){{$merchant_percentage_fee}}@endisset">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Money Transfer Fixed Fee</label>
                                       <input type="text" name="setting[mt_percentage_fee]" class="form-control m-1" value="@isset($mt_percentage_fee){{$mt_percentage_fee}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Money Transfer Percentage Fee</label>
                                       <input type="text" name="setting[mt_fixed_fee]" class="form-control m-1" value="@isset($mt_fixed_fee){{$mt_fixed_fee}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Escrow Fixed fee</label>
                                       <input type="text" name="setting[fixed_fee]" class="form-control m-1" value="@isset($fixed_fee){{$fixed_fee}}@endisset">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Escrow Percent Fee</label>
                                       <input type="text" name="setting[percent_fee]" class="form-control m-1" value="@isset($percent_fee){{$percent_fee}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Deposit Fixed Fee</label>
                                       <input type="text" name="setting[deposit_fixed_fee]" class="form-control m-1" value="@isset($deposit_fixed_fee){{$deposit_fixed_fee}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Deposit Percentage Fee</label>
                                       <input type="text" name="setting[deposit_percentage_fee]" class="form-control m-1" value="@isset($deposit_percentage_fee){{$deposit_percentage_fee}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Icon Url</label>
                                       <input type="text" name="setting[site_icon]" class="form-control m-1" required value="{{general_setting('site_icon')}}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Logo Url</label>
                                       <input type="text" name="setting[site_logo]" class="form-control m-1" required value="{{general_setting('site_logo')}}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Mollie_ApiKey</label>
                                       <input type="text" name="setting[Mollie_ApiKey]" class="form-control m-1" required value="{{general_setting('Mollie_ApiKey')}}">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Withdraw Fixed Fee</label>
                                       <input type="text" name="setting[withdraw_fixed_fee]" class="form-control m-1" value="@isset($withdraw_fixed_fee){{$withdraw_fixed_fee}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Withdraw Percentage Fee</label>
                                       <input type="text" name="setting[withdraw_percentage_fee]" class="form-control m-1" value="@isset($withdraw_percentage_fee){{$withdraw_percentage_fee}}@endisset">
                                    </div>
                                 </div>
                                 
                                  <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Strowallet_public_key</label>
                                       <input type="text" name="setting[stro_publickey]" class="form-control m-1" required value="{{general_setting('stro_publickey')}}">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Paypal Business Email</label>
                                       <input type="text" name="setting[paypal_email]" class="form-control m-1" value="{{general_setting('paypal_email')}}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>stripe_secret_key</label>
                                       <input type="text" name="setting[stripe_secret]" class="form-control m-1" value="{{general_setting('stripe_secret')}}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>stripe_public_key</label>
                                       <input type="text" name="setting[stripe_public]" class="form-control m-1" required value="{{general_setting('stripe_public')}}">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>flutter_public_key</label>
                                       <input type="text" name="setting[flutter_public]" class="form-control m-1" value="{{general_setting('flutter_public')}}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>flutter_secret_key</label>
                                       <input type="text" name="setting[flutter_secret]" class="form-control m-1" value="{{general_setting('flutter_secret')}}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>razorpay_keyId</label>
                                       <input type="text" name="setting[razorpay_keyId]" class="form-control m-1" value="{{general_setting('razorpay_keyId')}}">
                                    </div>
                                 </div>
                                  <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>paystack_public_key</label>
                                       <input type="text" name="setting[paystack_public]" class="form-control m-1" value="{{general_setting('paystack_public')}}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>paystack_secret_key</label>
                                       <input type="text" name="setting[paystack_secret]" class="form-control m-1" value="{{general_setting('paystack_secret')}}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>razorpay_Secretkey</label>
                                       <input type="text" name="setting[razorpay_Secretkey]" class="form-control m-1" value="{{general_setting('razorpay_Secretkey')}}">
                                    </div>
                                 </div>
                                  <div class="row">
                                    <div class="col-sm-4">
                                       <div class="form-group">
                                          <label>instamojo_ApiKey</label>
                                          <input type="text" name="setting[instamojo_ApiKey]" class="form-control m-1" value="{{general_setting('instamojo_ApiKey')}}">
                                       </div>
                                    </div>
                                    <div class="col-sm-4">
                                       <div class="form-group">
                                          <label>instamojo_AuthTokenKey</label>
                                          <input type="text" name="setting[instamojo_AuthTokenKey]" class="form-control m-1" value="{{general_setting('instamojo_AuthTokenKey')}}">
                                       </div>
                                    </div>
                                 </div>
                               <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>reloadly_client_id</label>
                                       <input type="text" name="setting[reloadly_client_id]" class="form-control m-1" value="{{general_setting('reloadly_client_id')}}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>reloadly_client_secret</label>
                                       <input type="text" name="setting[reloadly_client_secret]" class="form-control m-1" value="{{general_setting('reloadly_client_secret')}}">
                                    </div>
                                 </div>
                              </div>
            
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Merchant Payout Fixed fee</label>
                                       <input type="text" name="setting[payout_fixed_fee]" class="form-control m-1" value="@isset($payout_fixed_fee){{$payout_fixed_fee}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Merchant Payout Percentage fee</label>
                                       <input type="text" name="setting[payout_percentage_fee]" class="form-control m-1" value="@isset($payout_percentage_fee){{$payout_percentage_fee}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Wire Transfer Fixed fee</label>
                                       <input type="text" name="setting[wire_transfer_fixed_fee]" class="form-control m-1" value="@isset($wire_transfer_fixed_fee){{$wire_transfer_fixed_fee}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Wire Transfer Percentage fee</label>
                                       <input type="text" name="setting[wire_transfer_percentage_fee]" class="form-control m-1" value="@isset($wire_transfer_percentage_fee){{$wire_transfer_percentage_fee}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Minimum Payin</label>
                                       <input type="text" name="setting[min_payin]" class="form-control m-1" value="@isset($min_payin){{$min_payin}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Maximun Payin</label>
                                       <input type="text" name="setting[max_payin]" class="form-control m-1" value="@isset($max_payin){{$max_payin}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Minimum Payout</label>
                                       <input type="text" name="setting[min_payout]" class="form-control m-1" value="@isset($min_payout){{$min_payout}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Maximun Payout</label>
                                       <input type="text" name="setting[max_payout]" class="form-control m-1" value="@isset($max_payout){{$max_payout}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Set Time Limit</label>
                                       <select class="form-control select2" name="setting[time_status]" >
                                          <option value="">--select--</option>
                                          <option value="0" <?php echo (isset($time_status) && $time_status  == '0') ?'selected':''?>>T + 0</option>
                                          <option value="24" <?php echo (isset($time_status) && $time_status  == '24') ?'selected':''?>>T + 1</option>
                                          <option value="48" <?php echo (isset($time_status) && $time_status  == '48') ?'selected':''?> >T + 2</option>
                                          <option value="72" <?php echo (isset($time_status) && $time_status  == '72') ?'selected':''?>>T + 3</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">
                                       <div class="form-group">
                                          <label>Blackout</label>
                                          <select class="form-control select2" name="setting[website]" >
                                             <option value="">--select--</option>
                                             <option value="enabled" <?php echo (isset($website) && $website  == 'enabled') ?'selected':''?>>Enable</option>
                                             <option value="disabled" <?php echo (isset($website) && $website  == 'disabled') ?'selected':''?>>Disable</option>
                                          </select>
                                       </div>
                                 </div>
                                 <div class="col-sm-4">
                                       <div class="form-group">
                                          <label>FTD</label>
                                          <select class="form-control select2" name="setting[ftd]" >
                                             <option value="">--select--</option>
                                             <option value="enabled" <?php echo (isset($ftd) && $ftd  == 'enabled') ?'selected':''?>>Enable</option>
                                             <option value="disabled" <?php echo (isset($ftd) && $ftd  == 'disabled') ?'selected':''?>>Disable</option>
                                          </select>
                                       </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Min FTD Count </label>
                                       <input type="text" name="setting[ftd_count]" class="form-control m-1" value="@isset($ftd_count){{$ftd_count}}@endisset">
                                    </div>
                                 </div>
                              </div>
                                 @if(Auth::user()->role_id == 1)  <div class="">
                                 <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                                 @endif
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection