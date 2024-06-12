@extends('admin.layouts.master')
@section('content')
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
   .switch {
    position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
    }

    .switch input { 
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked + .slider {
      background-color: #2196F3;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }
</style>
@endpush

<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">{{$page_title}} </h1>
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
                        <div class="col-md-4">
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
                        <div class="col-md-6">
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
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-lg-12 col-xs-12">
                           <div class="table-responsive">
                              <table class="table align-items-center" id="datatable">
                                 <thead>
                                    <tr>
                                       <th scope="col">#</th>
                                       <th scope="col">User</th>
                                       <th scope="col">status</th>
                                       <th scope="col">Txn. ID</th>
                                       <th scope="col">Zook Txn. Reference No.</th>
                                       <th scope="col">Unique Txn. Reference No.</th>
                                       <th scope="col">Bank Reference No.</th>
                                       <th scope="col">MID</th>
                                       <th scope="col">Currency</th>
                                       <th scope="col">Activity title</th>
                                       <th scope="col">Money flow</th>
                                       <th scope="col">Gross</th>
                                       <th scope="col">Fee</th>
                                       <th scope="col">Net</th>
                                       <th scope="col">Balance</th>
                                       <th scope="col">PayIn Source</th>
                                       <th scope="col">Vpa</th>
                                       {{--<th>Main Hold Balance</th>
                                       <th>Payout Hold Balance</th>--}}
                                       <th scope="col">Currency symbol</th>
                                       <th scope="col">Created At</th>
                                       <th scope="col">Chargeback</th>
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
</div>
</div>
</section>
</div>
<div class="modal" tabindex="-1" role="dialog" id="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Send Transaction Report</h5>
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
   var userId = '';
   const queryString = window.location.search;
   const urlParams = new URLSearchParams(queryString);
   if (urlParams.has('userId')) {
      userId = urlParams.get('userId');
   }

    $(document).on("change", ".charge_data_toggle", function () {
         var request_id = $(this).attr('data-id');
         if (this.checked) {
            var checked = 1;
         }else{
            checked = 0;
         }

         $.ajax({
            url: "{{route('admin.chargeback')}}",
            type: "GET",
            data:{
               request_id:request_id,
               checked:checked
            },
            success: function (response) {
               
            }
         });
      });

   $('#transactionStatus').select2();

   const datatable = $('#datatable').DataTable({
      searchDelay: 500,
      processing: true,
      serverSide: true,
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
            pageSize : 'A2', // You can also use "A1","A2" or "A3", most of the time "A3" works the best.
            text : '<i class="fa fa-file-pdf-o"> PDF</i>',
            titleAttr : 'PDF'
        }
          
          ],
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
      ajax: {
            url: '{{ route("admin.transaction.list") }}',
            type: 'GET',
            data: function (d) {
                  d.transaction_state_id = $("#transactionStatus").val();
                  d.created_at_from = $('#transactionDateFrom').val();
                  d.created_at_to = $('#transactionDateTo').val();
                  d.multiple_email = $('#emailVerify').val();
                  d.openEmailModal = $('#openEmailModal').val();
                  d.userId = userId;
            }
      },
      columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
            { data: 'user_name', name: 'User.name' },
            { data: 'status' },
            { data: 'transactionable_id' },
            { data: 'unique_transaction_id' ,name:'Deposits.unique_transaction_id' },
            { data: 'ref' ,name:'Requests.ref'},
            { data: 'ag_bank_reference_no' ,name:'Deposits.ag_bank_reference_no' }, 
            { data: 'entity_id' ,name:'entity_id'}, 
            { data: 'currency' },
            { data: 'activity_title' },
            { data: 'money_flow' },
            { data: 'gross' },
            { data: 'fee' },
            { data: 'net' },
            { data: 'balance' },
            { data: 'qr_service_payload' },
            { data: 'vpa' },
            // { data: 'main_hold_balance' },
            // { data: 'payout_hold_balance'},
            { data: 'currency_symbol' },
            { data: 'created_at' },
            { data: 'chargeback_status',orderable: false,searchable: false},
      ],
      columnDefs: [
         {
             targets: 7,
             searchable:true,
              render: function (data, type, row) {
               return type === 'filter' ? data : data.replace('%','');
              },
              filter:function (data,type) {
                 if (type === 'search') {
                  var SearchTerm = this.search().replace('%','');
                  return data !== SearchTerm;
                 }
                 return true;
              }
         },
         { 
            targets: '_all',
            defaultContent: 'N/A'
         }
      ],
      "language": 
      {     
         processing: '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
      },
   });

   $('.dt-buttons').before('<b><p>Export To </p></b>');

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
   });

   $('#transactionDateFrom').datepicker({
      clearBtn: true,
      format: {

         toDisplay: function (date, format, language) {
            return new Date(date).toLocaleDateString();
         },
         toValue: function (date, format, language) {
            return new Date(d);
         }
      }
   }).on('changeDate', function(e) {
      datatable.draw();
   });
   $('#transactionDateTo').datepicker({
      clearBtn: true,
      format: {

         toDisplay: function (date, format, language) {
            return new Date(date).toLocaleDateString();
         },
         toValue: function (date, format, language) {
            return new Date(d);
         }
      }
   }).on('changeDate', function(e) {
      datatable.draw();
   });

   $("#transactionStatus").on('select2:select', function (e) {
      datatable.draw();
   });

   $('body').on("click",".delete",function (event){
      event.preventDefault();
      var id = $(this).attr('data-id');
      if(id !='')
      {
         swal.fire({
               title: "Are you sure?",
               text: "You will not be able to recover this record!",
               type: "warning",
               showCancelButton: true,
               confirmButtonColor: "#DD6B55",
               confirmButtonText: "Yes, delete it!",
               closeOnConfirm: false
         }).then((result) => {
               if(result.value){
                  window.location.href = "{{route('admin.transaction.delete')}}"+'/'+id;
               }
         });
      }
      
   });

   function validateEmail($email) {
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      return emailReg.test( $email );
   }
</script>
@endpush
