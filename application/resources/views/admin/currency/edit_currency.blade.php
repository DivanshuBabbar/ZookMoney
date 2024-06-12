@extends('admin.layouts.master')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark"> Edit Currency</h1>
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
                     <h3 class="card-title mx-4 mt-3"></h3>
                  <!-- </div> -->
                  <div class="card-body">
                     <div class="row">
                        <div class="col-lg-12 col-xs-12">
                     <form action="{{route('admin.update.currency')}}" method="POST">
                        @csrf
                        <div class="row">
                              <div class="col-sm-4">
                                 <input type="hidden" name="id" value="@isset($currency->id){{$currency->id}}@endisset">
                                 <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" value="@isset($currency->name){{$currency->name}}@endisset" required>
                                 </div>
                                 
                              </div>
                              <div class="col-sm-4">
                                 <div class="form-group">
                                 <label>Symbol</label>
                                 <input type="text" name="symbol" class="form-control" value="@isset($currency->symbol){{$currency->symbol}}@endisset" required>
                                 </div>
                              </div>
                              <div class="col-sm-4">
                                 <div class="form-group">
                                 <label>Code</label>
                                 <input type="text" name="code" class="form-control" value="@isset($currency->code){{$currency->code}}@endisset" required>
                                 </div>
                              </div>
                        </div>
                        <br>
                        <div class="row">
                              <div class="col-sm-4">
                                 <div class="form-group">
                                 <label>Is Crypto</label>
                                 <input type="text" name="is_crypto" class="form-control" value="@isset($currency->is_crypto){{$currency->is_crypto}}@endisset" required>
                                 </div>
                              </div>
                              <div class="col-sm-4">
                                 <div class="form-group">
                                 <label>Thumb</label>
                                 <input type="text" name="thumb" class="form-control" value="@isset($currency->thumb){{$currency->thumb}}@endisset" required>
                                </div>
                              </div>
                        </div>
                        <br>
                     @if(Auth::user()->role_id == 1)   <div class="">
                           <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                        </div>  @endif
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
@endsection