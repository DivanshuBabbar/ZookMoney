@extends('admin.layouts.master')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Escrows </h1>
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
                           <div class="table-responsive">
                              <table class="table align-items-center">
                                 <thead>
                                    <tr>
                                       <th scope="col">User Id</th>
                                       <th scope="col">To</th>
                                       <th scope="col">Currency Id</th>
                                       <th scope="col">Gross</th>
                                       <th scope="col">Description</th>
                                       <th scope="col">Json Data</th>
                                       <th scope="col">Currency Symbol</th>
                                       <th scope="col">Escrow Transaction Status</th>
                                       <th scope="col">Created At</th>
                                       <th scope="col">Deleted At</th>
                                       <th scope="col">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                   <tr>
                                      <td colspan="8"><em>{{__('No record found.')}}</em></td>
                                   </tr>
                                 </tbody>
                              </table>
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