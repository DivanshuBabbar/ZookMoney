@extends('admin.layouts.master')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Edit merchant </h1>
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
                  <h3 class="card-title mx-4 mt-3"></h3>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-lg-12 col-xs-12">
                           <form action="{{route('admin.update.merchant')}}" method="POST">
                              @csrf
                              <div class="row">
                                 <div class="col-sm-4">
                                    <input type="hidden" name="id" value="@isset($merchant->id){{$merchant->id}}@endisset">
                                    <div class="form-group">
                                       <label>Currency Id</label>
                                       <input type="text" name="currency_id" class="form-control" value="@isset($merchant->currency_id){{$merchant->currency_id}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Merchant Key</label>
                                       <input type="text" name="merchant_Key" class="form-control" value="@isset($merchant->merchant_key){{$merchant->merchant_key}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Site Url</label>
                                       <input type="text" name="site_url" class="form-control" value="@isset($merchant->site_url){{$merchant->site_url}}@endisset">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Success Link</label>
                                       <input type="text" name="success_link" class="form-control" value="@isset($merchant->success_link){{$merchant->success_link}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Fail Link</label>
                                       <input type="text" name="fail_link" class="form-control" value="@isset($merchant->fail_link){{$merchant->fail_link}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Logo</label>
                                       <input type="text" name="logo" class="form-control" value="@isset($merchant->logo){{$merchant->logo}}@endisset">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Name</label>
                                       <input type="text" name="name" class="form-control" value="@isset($merchant->name){{$merchant->name}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Description</label>
                                       <input type="text" name="description" class="form-control" value="@isset($merchant->description){{$merchant->description}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Json Data</label>
                                       <input type="text" name="json_data" class="form-control" value="@isset($merchant->json_data){{$merchant->json_data}}@endisset">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Thumb</label>
                                       <input type="text" name="thumb" class="form-control" value="@isset($merchant->thumb){{$merchant->thumb}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>User</label>
                                       <select class="form-control select2" name="user_id">
                                          <option value="">--select--</option>
                                          @if(DB::table('users')->count() > 0)
                                          @foreach(DB::table('users')->get() as $row)
                                          <option value="{{$row->id}}"<?php echo (isset($merchant->user_id) && $merchant->user_id  ==$row->id) ?'selected':''?>>{{$row->name}}</option>
                                          @endforeach
                                          @endif   
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Status</label>
                                       <select class="form-control select2" name="merchant_status">
                                         <option value="">--select--</option>
                                         @foreach($status as $key => $row)
                                          <option value="{{$key}}" <?php echo (isset($merchant->status) && $merchant->status  ==$row) ?'selected':''?>>{{$row}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Merchant Fixed Fee<span class="text-danger"> *</span></label>
                                       <input required type="number" name="merchant_fixed_fee" class="form-control" 
                                          step="0.01" value="{{ $merchant->merchant_fixed_fee }}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Merchant Percentage Fee<span class="text-danger"> *</span></label>
                                       <input required type="number" name="merchant_percentage_fee" class="form-control" 
                                          step="0.01" value="{{ $merchant->merchant_percentage_fee }}">
                                    </div>
                                 </div>
                                  <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Merchant Payout Fixed fee<span class="text-danger"> *</span></label>
                                       <input required type="number" name="payout_fixed_fee" class="form-control" 
                                          step="0.01" value="{{ $merchant->payout_fixed_fee }}">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Merchant Payout Percentage fee<span class="text-danger"> *</span></label>
                                       <input required type="number" name="payout_percentage_fee" class="form-control" 
                                          step="0.01" value="{{ $merchant->payout_percentage_fee }}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Wire Transfer Fixed fee<span class="text-danger"> *</span></label>
                                       <input required type="number" name="wire_transfer_fixed_fee" class="form-control" 
                                          step="0.01" value="{{ $merchant->wire_transfer_fixed_fee }}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Wire Transfer Percentage fee<span class="text-danger"> *</span></label>
                                       <input required type="number" name="wire_transfer_percentage_fee" class="form-control" 
                                          step="0.01" value="{{ $merchant->wire_transfer_percentage_fee }}">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Set Time Limit</label>
                                       <select class="form-control select2" name="time_status">
                                         <option value="">--select--</option>
                                         @foreach($time_status as $key => $row)
                                          <option value="{{$row}}" <?php echo (isset($merchant->time_status) && $merchant->time_status  ==$row) ?'selected':''?>>{{$key}}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>

                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Minimum Payin<span class="text-danger"> *</span></label>
                                       <input required type="number" name="min_payin" class="form-control" 
                                          step="0.01" value="{{ $merchant->min_payin }}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Maximun Payin<span class="text-danger"> *</span></label>
                                       <input required type="number" name="max_payin" class="form-control" 
                                          step="0.01" value="{{ $merchant->max_payin }}">
                                    </div>
                                 </div>
                              </div>
                               <div class="row">
                                
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Minimum Payout<span class="text-danger"> *</span></label>
                                       <input required type="number" name="min_payout" class="form-control" 
                                          step="0.01" value="{{ $merchant->min_payout }}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Maximun Payout<span class="text-danger"> *</span></label>
                                       <input required type="number" name="max_payout" class="form-control" 
                                          step="0.01" value="{{ $merchant->max_payout }}">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Min FTD Count </label>
                                       <input type="text" name="min_ftd_count" class="form-control m-1" value="@isset($merchant->min_ftd_count){{$merchant->min_ftd_count}}@endisset">
                                    </div>
                                 </div>
                              </div>
                              <div class="">
                                 <button type="submit" class="btn btn-primary mt-2">{{__('Submit')}}</button>
                              </div>
                           </form>
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
<script type="text/javascript">
   document.addEventListener('DOMContentLoaded',function(){
      $('.select2').select2();
   })
</script>
@endsection