@extends('admin.layouts.master')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Viewing Withdrawal   </h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
               </ol>
            </div>
         </div>
      </div>
   </div>
   <div class="row mx-3">
      <div class="col-sm-12">
         @include('flash')
      </div>
   </div>
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <!-- <div class="card-header"> -->
                  <h3 class="card-title mx-4 mt-3"></h3>
                  <!-- </div> -->
                  <div class="card-body">
                     <div class="row">
                        <div class="col-lg-12 col-xs-10">
                           <div class="panel panel-bordered" style="padding-bottom:5px;">
                              <!-- form start -->
                              <div class="panel-heading" style="border-bottom:0;">
                                 <h5 class="panel-title">Id</h5>
                              </div>
                              <div class="panel-body" style="padding-top:0;">
                                 <p>@isset($withdrawal->id){{$withdrawal->id}}@endisset</p>
                              </div>
                              <!-- panel-body -->
                              <hr style="margin:0;">
                              <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Wallet Id</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($withdrawal->wallet_id){{$withdrawal->wallet_id}}@endisset</p>
                              </div>
                              <!-- panel-body -->
                              <hr style="margin:0;">
                              <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Currency Id</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($withdrawal->currency_id){{$withdrawal->currency_id}}@endisset</p>
                              </div>
                              <!-- panel-body -->
                              <hr style="margin:0;">
                              <!-- panel-body -->
                              <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Gross</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($withdrawal->gross){{$withdrawal->gross}}@endisset</p>
                              </div>
                               <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Fee</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($withdrawal->fee){{$withdrawal->fee}}@endisset</p>
                              </div>
                              <hr style="margin:0;">
                              <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Net</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($withdrawal->net){{$withdrawal->net}}@endisset</p>
                              </div>
                               <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Platform Id</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($withdrawal->platform_id){{$withdrawal->platform_id}}@endisset</p>
                              </div>
                               <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Currency Symbol</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($withdrawal->currency_symbol){{$withdrawal->currency_symbol}}@endisset</p>
                              </div>
                              <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Send To Platform Name</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($withdrawal->send_to_platform_name){{$withdrawal->send_to_platform_name}}@endisset</p>
                              </div>
                              <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Created At</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>{{localDate($withdrawal->created_at, 'M j, Y, g:i A') }}</p>
                              </div>
                              <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">User</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($withdrawal->user_id){{$withdrawal->users->name}}@endisset</p>
                              </div>
                              <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">transaction_states</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>{{$withdrawal->status->name}}</p>
                              </div>
                              <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Detail</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>{{$withdrawal->detail}}</p>
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
</div>
</div>
</section>
</div>
@endsection