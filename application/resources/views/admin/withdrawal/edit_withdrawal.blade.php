@extends('admin.layouts.master')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Edit Withdrawal</h1>
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
                           <table class="table table-striped" style="margin-bottom: 0;">
                              <thead>
                                 <tr>
                                    <th>Request ID </th>
                                    <th>Date</th>
                                    <th>Gross</th>
                                    <th>Fee</th>
                                    <th>Net</th>
                                    <th>Platform name</th>
                                    <th>Platform Id</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <td>@isset($withdrawal->id){{$withdrawal->id}}@endisset</td>
                                 <td>@isset($withdrawal->created_at){{localDate($withdrawal->created_at, 'M j, Y, g:i A')}}@endisset</td>
                                 <td>@isset($withdrawal->gross){{$withdrawal->gross}}@endisset</td>
                                 <td>@isset($withdrawal->fee){{$withdrawal->fee}}@endisset</td>
                                 <td>@isset($withdrawal->net){{$withdrawal->net}}@endisset</td>
                                 <td>@isset($withdrawal->send_to_platform_name){{$withdrawal->send_to_platform_name}}@endisset</td>
                                 <td>@isset($withdrawal->platform_id){{$withdrawal->platform_id}}@endisset</td>
                              </tbody>
                           </table>
                           
                           
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <!-- <div class="card-header"> -->
                     <h3 class="card-title mx-4 mt-3"></h3>
                  <!-- </div> -->
                  
                  <div class="card-body">

                     <div class="row">
                        <div class="col-lg-12 col-xs-10">
                           <form action="{{route('admin.withdrawal.withdrawal_update',$withdrawal->id)}}" method="POST">
                              @csrf
                              <div class="row">
                                 <div class="col-sm-6">
                                    <label>Transaction status</label>
                                    <select class="form-control" name="transaction_state_id">
                                          @if(DB::table('transaction_states')->count() > 0)
                                             @foreach(DB::table('transaction_states')->get() as $row)
                                                <option value="{{$row->id}}" @isset($withdrawal->transaction_state_id){{$withdrawal->transaction_state_id ==$row->id ? 'selected':''}}@endisset>{{$row->name}}</option>
                                             @endforeach
                                          @endif
                                    </select>
                                 </div>
                                 <div class="col-sm-6">
                                 <label for="remarks">Remarks</label>
                                 <textarea id="remarks" name="remarks" class="form-control">@isset($withdrawal->remarks){{$withdrawal->remarks}}@endisset</textarea>
                              </div>
                              </div>
                              <button type="submit" class="btn btn-danger mt-3">
                                 Update
                              </button>
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