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
</style>
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ $page_title }} </h1>
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
                                            <label>Deposit Methods</label>
                                            <select class="form-control select2" style="width: 100%;" id="depositMethods">
                                                <option value=''>--Select All--</option>
                                                @foreach($depositMethods as $id => $name)
                                                <option value="{{$id}}">{{$name}}</option>
                                                @endforeach   
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
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
                                                <button type="button" id="openDepositModal" class="btn btn-primary" value="">Email</button>
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
                                            <table class="table align-items-center datatable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>id</th>
                                                        <th> User Name </th>
                                                        <th> Gross </th>
                                                        <th> Fee </th>
                                                        <th> Net </th>
                                                        <th> Transaction Receipt </th>
                                                        <th> Currency Symbol </th>
                                                        <th> Message </th>
                                                        <th> Created At </th>
                                                        <th> Deposit Method </th>
                                                        <th> wallets </th>
                                                        <th> Unique Transaction Id </th>
                                                        <th> Transaction states </th>
                                                        <th class="actions">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
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
    <div class="modal" tabindex="-1" role="dialog" id="myDepositModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Send Deposit List</h5>
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
                   columns: [1, 2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 13, 14 ]
                }
            },

             {
                extend : 'pdf',
                orientation : 'landscape',
                pageSize : 'A4', // You can also use "A1","A2" or "A3", most of the time "A3" works the best.
                text : '<i class="fa fa-file-pdf-o"> PDF</i>',
                titleAttr : 'PDF',
                exportOptions: {
                   columns: [1, 2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 13, 14 ]
                }
            }
              
            ],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: {
                url: '{{ route("admin.deposits.list") }}',
                type: 'GET',
                data: function (d) {
                    d.transaction_state_id = $("#transactionStatus").val();
                    d.deposit_method_id = $("#depositMethods").val();
                    d.created_at_from = $('#createdAtFrom').val();
                    d.created_at_to = $('#createdAtTo').val();
                    d.multiple_email = $('#emailVerify').val();
                    d.openDepositModal = $('#openDepositModal').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'id' },
                { data: 'user_name' , name: 'User.name'},
                { data: 'gross' },
                { data: 'fee' },
                { data: 'net' },
                { data: 'transaction_receipt' },
                { data: 'currency_symbol' },
                { data: 'message' },
                { data: 'created_at' },
                { data: 'deposit_method_name' },
                { data: 'wallet_id' },
                { data: 'unique_transaction_id' },
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
        
        $('.dt-buttons').before('<b><p>Export To </p></b>');

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

        $('#depositMethods').select2();
        $('#transactionStatus').select2();

        $("#transactionStatus").on('select2:select', function (e) {
            datatable.draw();
        });
        $("#depositMethods").on('select2:select', function (e) {
            datatable.draw();
        });

        $('#openDepositModal').on('click', function(e) {
            $('#myDepositModal').modal('show');
        });

        $('#sendEmail').on('click', function(e) {
            $('#openDepositModal').val('1');
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
                $('#myDepositModal').modal('hide');
            }
        });

        $('#myDepositModal').on('hidden.bs.modal', function (e) {
            $('#openDepositModal').val('');
            $(this).find("input").val('').end();
        });

        function validateEmail($email) {
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            return emailReg.test( $email );
        }

    </script>
@endpush