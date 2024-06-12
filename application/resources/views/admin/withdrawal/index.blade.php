@extends('admin.layouts.master')
@push('styles')
<link rel="stylesheet" href="{{asset('assets/admin/newdash/css/bootstrap-datepicker.min.css')}}">
<style>
   .dataTables_length > label{
      float: right;
    padding-left: 20px;
   }
   button.dt-button.buttons-pdf.buttons-html5 {
      color: #fff;
      background: #f4bc4b;
      border: solid;
      border-radius: 8px;
   }
   button.dt-button.buttons-csv.buttons-html5{
      color: #f8f9fa;
      background: #02bcd1;
      border: solid;
      border-radius: 8px
   }
</style>
@endpush
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Withdrawals</h1>
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
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label>Platform Name</label>
                                 <select class="form-control select2" style="width: 100%;" id="withdrawMethods">
                                       <option value=''>-- Select All --</option>
                                       @foreach($withdrawMethods as $id => $name)
                                       <option value="{{$id}}">{{$name}}</option>
                                       @endforeach   
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label>Transaction status</label>
                                 <select class="form-control select2" style="width: 100%;" id="transactionStatus">
                                       <option value=''>-- Select All --</option>
                                       @foreach($transactionStatus as $id => $name)
                                       <option value="{{$id}}">{{$name}}</option>
                                       @endforeach   
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <label>Created at</label>
                              <div class="form-group row">
                                 <div class="col-md-12">
                                       <div class="form-group d-flex flex-row justify-content-between gap-3">
                                          <input type="text" class="form-control" id="createdAtFrom">
                                          <label class="col-form-label mx-4">To</label>
                                          <input type="text" class="form-control" id="createdAtTo">
                                       </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-2">
                             <label>Email Report</label>
                              <div class="form-group row">
                                 <div class="col-md-12">
                                    <div class="form-group d-flex flex-row justify-content-between gap-3" style="padding-left: 23px;">
                                       <button type="button" id="openWithdrawlModal" class="btn btn-primary" value="">Email</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-lg-12 col-xs-10">
                           <div class="table-responsive">
                              <table class="table align-items-center datatable">
                                 <thead>
                                    <tr>
                                       <th scope="col">#</th>
                                       <th scope="col">ID</th>
                                       <th scope="col">User</th>
                                       <th scope="col">Wallet Id</th>
                                       <th scope="col">Currency Symbol</th>
                                       <th scope="col">Currency</th>
                                       <th scope="col">Gross</th>
                                       <th scope="col">Fee</th>
                                       <th scope="col">Net</th>
                                       <th scope="col">Send To Platform Name</th>
                                       <th scope="col">Created at</th>
                                       <th scope="col">transaction_states</th>
                                       <th scope="col">Actions</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    
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
   </section>
</div>
<div class="modal" tabindex="-1" role="dialog" id="myWithdrawlModal">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Send Withdrawl List</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="input-group mb-3">
             <div class="input-group-prepend">
               <span class="input-group-text" id="basic-addon1">@</span>
             </div>
             <input type="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" id="emailVerify" multiple>
           </div>
           <span class="errorEmail" style="color: red;" ></span>
           <p>Enter multiple email addresses, separated by a comma.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="sendEmail">Send</button>
        </div>
      </div>
  </div>
</div>
@endsection
@push('scripts')
   <script src="{{asset('assets/admin/newdash/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
   <script src="{{asset('assets/admin/newdash/js/bootstrap-datepicker.en-US.min.js')}}" type="text/javascript"></script>

   <script>

      const datatable = $('.datatable').DataTable({
         searchDelay: 500,
         processing: true,
         serverSide: true,
         dom: 'Blfrtip',
         fixedHeader: true,
         buttons: [

          {
             extend : 'csv',
             text : '<i class="fa fa-file-csv"> Excel</i>',
             titleAttr : 'Excel',
             exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
             }
         },

          {
             extend : 'pdf',
             orientation : 'landscape',
             pageSize : 'A4', // You can also use "A1","A2" or "A3", most of the time "A3" works the best.
             text : '<i class="fa fa-file-pdf-o"> PDF</i>',
             titleAttr : 'PDF',
             exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
             }
         }
           
         ],
         lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
         ajax: {
            url: '{{ route("admin.withdrawal.list") }}',
            type: 'GET',
            data: function (d) {
               d.transaction_state_id = $("#transactionStatus").val();
               d.withdrawal_method_id = $("#withdrawMethods").val();
               d.created_at_from = $('#createdAtFrom').val();
               d.created_at_to = $('#createdAtTo').val();
               d.multiple_email = $('#emailVerify').val();
               d.openWithdrawlModal = $('#openWithdrawlModal').val();
            }
         },
         columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'id' },
            { data: 'users.name' },
            { data: 'wallet_id' },
            { data: 'currency_symbol' },
            { data: 'currency.name' },
            { data: 'gross' },
            { data: 'fee' },
            { data: 'net' },
            { data: 'send_to_platform_name' },
            { data: 'created_at' },
            { data: 'status' },
            { data: 'action' },
         ],
         columnDefs: [
            { 
               targets: '_all',
               defaultContent: 'N/A'
            },
            {
               targets: -1,
               title: 'Actions',
               orderable: false,
            },
         ],
         order: [[10, 'desc']],
         "language": 
         {     
            processing: '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
         }
      });

      $('#createdAtFrom').datepicker({
         clearBtn: true,
         format: {

            toDisplay: function (date, format, language) {
               return new Date(date).toLocaleDateString();
            },
            toValue: function (date, format, language) {
               return new Date(d);
            }
         }
      }).on('changeDate', function (e) {
         datatable.draw();
      });
      $('#createdAtTo').datepicker({
         clearBtn: true,
         format: {

            toDisplay: function (date, format, language) {
               return new Date(date).toLocaleDateString();
            },
            toValue: function (date, format, language) {
               return new Date(d);
            }
         }
      }).on('changeDate', function (e) {
         datatable.draw();
      });

      $('#withdrawMethods').select2();
      $('#transactionStatus').select2();

      $("#transactionStatus").on('select2:select', function (e) {
         datatable.draw();
      });
      $("#withdrawMethods").on('select2:select', function (e) {
         datatable.draw();
      });

      $('.dt-buttons').before('<b><p>Export To </p></b>');


      $('#openWithdrawlModal').on('click', function(e) {
         $('#myWithdrawlModal').modal('show');
      });

      $('#sendEmail').on('click', function(e) {
         $('#openWithdrawlModal').val('1');
         var email_address = $('#emailVerify').val();
         var multiple_email = email_address.split(',');

         for (var i = 0; i < multiple_email.length; i++) {
             if( !validateEmail(multiple_email[i])) {
                 $('.errorEmail').html('');
                 $('.errorEmail').html('incorrect email address');
                 return false; 
             }
         }

         if ($('#emailVerify').val() == '' || $('#emailVerify').val() == null ) {
             $('.errorEmail').html('');
             $('.errorEmail').html('field required');
             return false;
         }else{
             $('.errorEmail').html('');
             datatable.draw();
             $('#myWithdrawlModal').modal('hide');
         }
      });

      $('#myWithdrawlModal').on('hidden.bs.modal', function (e) {
         $('#openWithdrawlModal').val('');
         $(this).find("input").val('').end();
      });

      function validateEmail($email) {
         var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
         return emailReg.test( $email );
      }
   </script>
@endpush