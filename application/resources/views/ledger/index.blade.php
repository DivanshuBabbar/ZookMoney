@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{asset('assets/admin/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('assets/admin/newdash/css/bootstrap-datepicker.min.css')}}">
@endpush
<style>
    .modal-backdrop {
        position: relative !important;
    }
    .modal-dialog {
       padding-top: 65px !important;
    }
     div#\#sendMoney {
       padding-top: 79px !important;
      }

     span.select2.select2-container.select2-container--default {
        display: none;
    }
    div#Transactiondatatable_filter {
        float: left;
        padding-top: 24px;
        padding-bottom: 18px;
    }
    button.dt-button.buttons-pdf.buttons-html5 {
       color: #f8f9fa;
       background: #f46000;
       border: solid;
       border-width: 2px;
       font-weight: 400;
       line-height: 1.35em;
       border: none;
       border-radius: 0.1875rem;
       padding: 11px 22px
    }
    button.dt-button.buttons-csv.buttons-html5{
       color: #f8f9fa;
       background: #f46000;
       border: solid;
       border-width: 2px;
       font-weight: 400;
       line-height: 1.35em;
       border: none;
       border-radius: 0.1875rem;
       padding: 11px 22px
     
    }
    div.dataTables_wrapper div.dataTables_paginate ul.pagination {
        margin:2px 0; 
        white-space:nowrap; 
        justify-content: center !important;
        padding-bottom: 8px !important;
    }
</style>
@section('content')

   <div class="row">
       <div class="col-lg-12 col-md-12">
         <div class="row">
           <div class="col"  id="#sendMoney">
             @include('flash')
             <div class="card">
               <div class="header">
                   <h2><strong>{{__('Transactions')}}</strong></h2>
                   
               </div>
               <div class="col-md-4" style="padding-left:21px;">
                  <div class="row">
                     <div class="col-6">
                           <div class="card">
                              <label style="padding-left:21px;"><b>Balance : {{ Auth::User()->balance }}</b></label>
                           </div>
                     </div>
                     <div class="col-6">
                           <div class="card">
                              <label><b>Today Deposited : </b>{{$today_payout}}</label>
                           </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12">
               <div class="row">
               <div class="col-md-3" style="padding-left: 21px;">
                  <div class="form-group">
                     <label>Transaction status</label>
                     <select class="form-control select2" style="width: 100%;" id="transactionStatus">
                        <option value=''>--Select All--</option>
                        @foreach($transactionStatus as $id => $name)
                        <option value="{{$id}}">{{$name}}</option>
                        @endforeach   
                     </select>
                  </div>
               </div>
              <div class="col-md-3" style="padding-left: 21px;">
                  <div class="form-group">
                     <label>Type</label>
                     <select class="form-control select2" style="width: 100%;" id="activity_type">
                        <option value=''>--Select All--</option>
                        <option value="UPI">UPI</option>
                        {{--<option value="Wire transfer">Wire transfer</option>--}}
                        <option value="Wallet Deposit">Wallet Deposit</option>
                        <option value="Settlement">Settlement</option>
                        {{--<option value="Refund">Refund</option>--}}
                        <option value="Payout A/C">Payout A/C</option>
                        <option value="Sent">Sent</option>
                        <option value="Received">Received</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                 <label>Created at</label>
                 <div class="form-group row">
                    <div class="col-md-12">
                       <div class="form-group d-flex flex-row justify-content-between gap-3">
                          <input type="text" class="form-control" id="transactionDateFrom">
                          <label class="col-form-label mx-4">To</label>
                          <input type="text" class="form-control" id="transactionDateTo">
                       </div>
                    </div>
                 </div>
               </div>
               <div class="col-md-2">
                <label>Email Report</label>
                 <div class="form-group row">
                    <div class="col-md-12">
                       <div class="form-group d-flex flex-row justify-content-between gap-3" style="padding-left: 23px;">
                          <button type="button" id="openEmailModal" class="btn btn-primary" value="">Email</button>
                       </div>
                    </div>
                 </div>
               </div>
              </div>
              </div>
               <div class="body">
                 <div class="table-responsive">
                   <table class="table align-items-center" id="Transactiondatatable">
                      <thead>
                         <tr>
                            <th>User</th>
                            <th>Order/Invoice Id</th>
                            <th>Zook Txn. Reference No.</th>
                            <th>Unique Txn. Reference No.</th>
                            <th>Bank Reference No.</th>
                            <th>Currency</th>
                            <th>Type</th>
                            <th>Gross</th>
                            <th>MDR</th>
                            <th>Net</th>
                            <th>Balance</th>
                            {{--<th>Main Hold Balance</th>
                            <th>Payout Hold Balance</th>--}}
                            <th>Currency Symbol</th>
                            <th>Money Flow</th>
                            <th>Transaction State</th>
                            <th>Created At</th>
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

<div class="modal" tabindex="-1" role="dialog" id="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Send User Transaction Report</h5>
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

@section('js')
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('assets/admin/newdash/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/admin/newdash/js/dataTables.bootstrap4.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/admin/newdash/js/dataTables.buttons.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/admin/newdash/js/buttons.print.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/admin/newdash/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/1.5.1/js/buttons.flash.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/1.5.1/js/buttons.html5.js"></script>
    <script src="{{asset('assets/admin/newdash/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/admin/newdash/js/bootstrap-datepicker.en-US.min.js')}}" type="text/javascript"></script>

    <script>
        var datepicker = $.fn.datepicker.noConflict();
        $.fn.bootstrapDP = datepicker;

        $('#transactionStatus').select2();
        $('#activity_type').select2();

        $('#transactionDateFrom').datepicker({
             clearBtn: true,
             format: {

                toDisplay: function (date, format, language) {
                   return new Date(date).toLocaleDateString();
                },
                toValue: function (date, format, language) {
                   return new Date();
                }
             }
          }).on('change', function(e) {
             datatable.draw();
        });

     
     
        $('#transactionDateTo').datepicker({
         clearBtn: true,
         format: {

            toDisplay: function (date, format, language) {
               return new Date(date).toLocaleDateString();
            },
            toValue: function (date, format, language) {
               return new Date();
            }
         }
        }).on('change', function(e) {
         datatable.draw();
        });




        const datatable = $('#Transactiondatatable').DataTable({
         searchDelay: 500,
         processing: true,
         serverSide: true,
         orderable: true,
         dom: 'Blfrtip',
         fixedHeader: true,
         buttons: [

            {
               extend : 'csv',
               text : '<i class="fa fa-file-csv"> Excel</i>',
               titleAttr : 'Excel'
           },

            {
               extend : 'pdf',
               orientation : 'landscape',
               pageSize : 'A3', // You can also use "A1","A2" or "A3", most of the time "A3" works the best.
               text : '<i class="fa fa-file-pdf-o"> PDF</i>',
               titleAttr : 'PDF'
           }
             
             ],
         lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
         ajax: {
               url: '{{ route("ledger.list",app()->getLocale()) }}',
               type: 'GET',
               data: function (d) {
                 d.transaction_state_id = $("#transactionStatus").val();
                 d.created_at_from = $('#transactionDateFrom').val();
                 d.created_at_to = $('#transactionDateTo').val();
                 d.multiple_email = $('#emailVerify').val();
                 d.openEmailModal = $('#openEmailModal').val();
                 d.activity_type = $("#activity_type").val();

               }
         },
         columns: [
              
               { data: 'user_name' },
               { data: 'transactionable_id' },
               { data: 'unique_transaction_id' },
               { data: 'ref' },
               { data: 'ag_bank_reference_no' },
               { data: 'currency' },
               { data: 'activity_title' },
               { data: 'gross' },
               { data: 'fee' },
               { data: 'net' },
               { data: 'balance' },
               // { data: 'main_hold_balance' },
               // { data: 'payout_hold_balance'},
               { data: 'currency_symbol' },
               { data: 'money_flow' },
               { data: 'status' },
               { data: 'created_at' },
         ],
         columnDefs: [
            { 
               targets: '_all',
               defaultContent: 'N/A'
            }
            
         ],
         "language": 
         {     
            processing: '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
         }
        });

        $('.dt-buttons').before('<b><p>Export To </p></b>');

        $("#transactionStatus").on('change', function (e) {
         datatable.draw();
        });

        $("#activity_type").on('change', function (e) {
         datatable.draw();
        });

        $('#openEmailModal').on('click', function(e) {
         $('#myModal').modal('show');
        });

       $('#sendEmail').on('click', function(e) {
          $('#openEmailModal').val('1');
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
             $('#myModal').modal('hide');
          }
         
       });

       $('#myModal').on('hidden.bs.modal', function (e) {
          $('#openEmailModal').val('');
         $(this).find("input").val('').end();
         $('.errorEmail').html('');
       });

       function validateEmail($email) {
          var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
          return emailReg.test( $email );
       }
    </script>
   

@endsection

