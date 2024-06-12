@extends('admin.layouts.master')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Viewing Merchant</h1>
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
                                 <h5 class="panel-title">Currency Id</h5>
                              </div>
                              <div class="panel-body" style="padding-top:0;">
                                 <p>@isset($merchant->currency_id){{$merchant->currency_id}}@endisset</p>
                              </div>
                              <!-- panel-body -->
                              <hr style="margin:0;">
                              <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Merchant Key</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($merchant->merchant_key){{$merchant->merchant_key}}@endisset</p>
                              </div>
                              <!-- panel-body -->
                              <hr style="margin:0;">
                              <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Site Url</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($merchant->site_url){{$merchant->site_url}}@endisset</p>
                              </div>
                              <!-- panel-body -->
                              <hr style="margin:0;">
                              <!-- panel-body -->
                              <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Success Link
                                 </h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($merchant->success_link){{$merchant->success_link}}@endisset</p>
                              </div>
                               <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Fail Link</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($merchant->fail_link){{$merchant->fail_link}}@endisset</p>
                              </div>
                              <hr style="margin:0;">
                              <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Logo</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($merchant->logo){{$merchant->logo}}@endisset</p>
                              </div>
                               <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Name</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($merchant->name){{$merchant->name}}@endisset</p>
                              </div>
                               <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Description </h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($merchant->description){{$merchant->description}}@endisset</p>
                              </div>
                              <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Json Data</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>@isset($merchant->json_data){{$merchant->json_data}}@endisset</p>
                              </div>
                              <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Thumb </h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>{{$merchant->thumb}}</p>
                              </div>
                              <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">Created At</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p> {{localDate($merchant->created_at, 'M j, Y, g:i A') }}</p>
                              </div>
                              <hr style="margin:0;">
                               <div class="panel-heading mt-2" style="border-bottom:0;">
                                 <h5 class="panel-title">User</h5>
                              </div>
                              <div class="panel-body mt-2" style="padding-top:0;">
                                 <p>{{$merchant->user->name}}</p>
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