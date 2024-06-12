@extends('admin.layouts.master')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css">

@endpush
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark"> {{$page_title}}</h1>
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
                           <form action="{{route('admin.save.deposit.method')}}" method="POST">
                              @csrf
                              <input type="hidden" name="id" value="@isset($deposit_method->id){{$deposit_method->id}}@endisset">
                              <div class="row">
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Payment method name</label>
                                       <input type="text" name="name" class="form-control" required value="@isset($deposit_method->name){{$deposit_method->name}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Currency</label>
                                        <select class="form-control" name="currency_id" required>
                                           <option value="">-- select --</option>
                                           @if(!empty($currencies))
                                           @foreach($currencies as $key=> $row)
                                           <option value="@isset($row->id){{$row->id}}@endisset"@isset($deposit_method->currency_id){{$deposit_method->currency_id ==$row->id ? 'selected':''}}@endisset>@isset($row->name){{$row->name}}@endisset</option>
                                           @endforeach
                                           @endif
                                        </select>
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group">
                                       <label>Status</label>
                                       <select class="form-control" name="status" required>
                                           <option value="">-- select --</option>
                                           <option value="1"@isset($deposit_method->status){{$deposit_method->status ==1 ? 'selected':''}}@endisset>Active</option>
                                           <option value="0"@isset($deposit_method->status){{$deposit_method->status == 0 ? 'selected':''}}@endisset>Inactive</option>
                                        </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       <label>Transaction Ref No Format</label>
                                       <input type="text" name="transaction_receipt_ref_no_format" class="form-control" 
                                          value="@isset($deposit_method->transaction_receipt_ref_no_format){{$deposit_method->transaction_receipt_ref_no_format}}@endisset">
                                    </div>
                                 </div>
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                       <label>List Sequence</label>
                                       <input type="number" name="sequence_no" class="form-control" required value="@isset($deposit_method->sequence_no){{$deposit_method->sequence_no}}@endisset">
                                    </div>
                                 </div>
                                 
                                 <div class="col-sm-2">
                                    <div class="form-group">
                                       <label>Is Eligible ?</label>
                                       <select class="form-control" name="is_eligible" required>
                                             <option value="">-- select --</option>
                                             <option value="1"@isset($deposit_method->is_eligible){{$deposit_method->is_eligible ==1 ? 'selected':''}}@endisset>Yes</option>
                                             <option value="0"@isset($deposit_method->is_eligible){{$deposit_method->is_eligible == 0 ? 'selected':''}}@endisset>No</option>
                                       </select>
                                    </div>
                                 </div>
                                 
                              </div>
                              <div class="row">
                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label>Detail Instructions of Payment</label>
                                       <textarea class="form-control" name="detail" rows="3">@isset($deposit_method->detail){{$deposit_method->detail}}@endisset</textarea>
                                    </div>
                                 </div>
                              </div>
                             @if(Auth::user()->role_id == 1)  <div class="">
                                 <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                              </div> @endif
                              
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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js" 
   integrity="sha512-ZESy0bnJYbtgTNGlAD+C2hIZCt4jKGF41T5jZnIXy4oP8CQqcrBGWyxNP16z70z/5Xy6TS/nUZ026WmvOcjNIQ==" 
   crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

   $("textarea[name='detail']").summernote({
      tabsize: 2,
      height: 300,
      toolbar: [
         ['style', ['style']],
         ['color', ['color']],
         ['font', ['fontsize', 'bold', 'italic', 'underline', 'clear', 'strikethrough', 'superscript', 'subscript']],
         ['para', ['ul', 'ol', 'paragraph', 'height']],
         ['insert', ['table', 'link', 'picture', 'hr']],
         ['view', ['fullscreen']]
      ]
   });
</script>
@endpush