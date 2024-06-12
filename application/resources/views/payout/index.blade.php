@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{asset('assets/admin/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('assets/admin/newdash/css/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            <div class="col" id="#sendMoney">
                @include('flash')
                <div class="card">
                    <div class="header">
                        <h2><strong>{{__('Payout')}}</strong></h2>
                    </div>
                    <div class="col-md-4" style="padding-left:21px;">
                  <div class="row">
                     <div class="col-6">
                           <div class="card">
                              <label style="padding-left:21px;"><b>Balance : {{ round($check_payout_balance,2) }}</b></label>
                           </div>
                     </div>
                     <div class="col-6">
                           <div class="card">
                              <label style="padding-left:21px;"><b>Today Deposited : </b>{{$bulk_payout}}</label>
                           </div>
                     </div>
                  </div>
               </div>

                    <div class="col-md-12 mt-2" >
                        <div class="row">
                            <div class="col-md-3" style="padding-left: 21px;">
                                <div class="form-group">
                                    <label>Transaction status</label>
                                    <select class="form-control select2" style="width: 100%;" id="transactionStatus">
                                        <option value=''>--Select All--</option>
                                        <option value="1">Completed</option>
                                        <option value="2">Canceled</option>
                                        <option value="3">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" style="padding-left: 21px;">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select class="form-control select2" style="width: 100%;" id="activity_type">
                                        <option value=''>--Select All--</option>
                                        <option value="Wire - IMPS">Wire - IMPS</option>
                                        <option value="Transferred">Transferred</option>
                                        <option value="Bulk Transfer">Bulk Transfer</option>

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
                            <table class="table align-items-center" id="payoutdatatable">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Payout Id</th>
                                        <th>Payout Group Id</th>
                                        <th>Currency</th>
                                        <th>Type</th>
                                        <th>Gross</th>
                                        <th>MDR</th>
                                        <th>Net</th>
                                        <th>Balance</th>
                                        <th>Currency Symbol</th>
                                        <th>Money Flow</th>
                                        <th>Transaction State</th>
                                        <th>Created At</th>
                                        <th>Action</th>
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
                <h5 class="modal-title">Send Payout Report</h5>
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
                <span class="errorEmail" style="color: red;"></span>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.js" integrity="sha512-9yoLVdmrMyzsX6TyGOawljEm8rPoM5oNmdUiQvhJuJPTk1qoycCK7HdRWZ10vRRlDlUVhCA/ytqCy78+UujHng==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


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

        
        $('#scheduledataandtime').datetimepicker({
            clearBtn: true,
            format: 'Y-m-d H:i:s'
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




   const datatable = $('#payoutdatatable').DataTable({
    searchDelay: 500,
    processing: true,
    serverSide: true,
    orderable: true,
    dom: 'Blfrtip',
    fixedHeader: true,
    buttons: [
        {
            extend: 'csv',
            text: '<i class="fa fa-file-csv"> Excel</i>',
            titleAttr: 'Excel'
        },
        {
            extend: 'pdf',
            orientation: 'landscape',
            pageSize: 'A3', // You can also use "A1","A2" or "A3", most of the time "A3" works the best.
            text: '<i class="fa fa-file-pdf-o"> PDF</i>',
            titleAttr: 'PDF'
        }
    ],
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
    ajax: {
        url: '{{ route("getpayout",app()->getLocale()) }}',
        type: 'GET',
        data: function (d) {
            d.created_at_from = $('#transactionDateFrom').val();
            d.created_at_to = $('#transactionDateTo').val();
            d.multiple_email = $('#emailVerify').val();
            d.openEmailModal = $('#openEmailModal').val();
            d.transaction_state_id = $("#transactionStatus").val();
            d.activity_type = $("#activity_type").val();
        }
    },
    columns: [
        { data: 'user_name' },
        {
            data: 'payout_id',
            render: function (data, type, row) {
                // Render 'null' for payout_id if payout_group_id is not null
                return row.payout_group_id !== null ? 'N/A' : data;
            }
        },
        {
            data: 'payout_group_id',
            render: function (data) {
                return data === null ? 'N/A' : data;
            }
        },
        { data: 'currency_symbol' },
        { data: 'transactionable_type' },
        {data: 'gross'},
        {data: 'fee'},
        {data: 'net'},
        { data: 'balance' },
        { data: 'currency_symbol' },
        { data: 'money_flow' },
        { data: 'status' },
        { data: 'created_at' },
        {
            data: null,
            render: function (data, type, row) {
                var view_button = '';
                if (row.payout_group_id) {
                    view_button = '<button type="button" class="btn btn-custom btn-sm view-details" style="background-color: #02bcd1; border-color: #02bcd1; color: white; font-weight: bold;" data-id="' + row.id + '">View</button>';
                }
                return view_button;
            },
            searchable: false,
            orderable: false
        }
    ],
    columnDefs: [
        {
            targets: '_all',
            defaultContent: 'N/A'
        }
    ],
    "language": {
        processing: '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
    },
    "drawCallback": function (settings) {
        var api = this.api();
        var rows = api.rows({ page: 'current' }).nodes();
        var last = null;

        // Calculate sum of fee and gross for each group
        var groupSums = {};
        var groupBalances = {}; // To store minimal balance for each group
        var groupStatuses = {}; // To track statuses for each group
        api.rows({ page: 'current' }).every(function () {
            var data = this.data();
            var group = data.payout_group_id;
            if (group && data.fee && data.gross && data.net) {
                if (!groupSums[group]) {
                    groupSums[group] = { feeSum: 0, grossSum: 0, netSum: 0 };
                }
                groupSums[group].feeSum += parseFloat(data.fee);
                groupSums[group].grossSum += parseFloat(data.gross);
                groupSums[group].netSum += parseFloat(data.net);
            }

            // Track minimal balance for each group
            if (group && data.balance !== null) {
                if (!groupBalances[group] || data.balance < groupBalances[group]) {
                    groupBalances[group] = data.balance;
                }
            }

            // Track statuses for each group
            if (group && data.status) {
                if (!groupStatuses[group]) {
                    groupStatuses[group] = [];
                }
                groupStatuses[group].push(data.status);
            }
        });

      api.rows({ page: 'current' }).every(function () {
            var data = this.data();
            var group = data.payout_group_id;
            if (group && groupSums[group]) {
                  $(this.node()).find('td:eq(5)').html(groupSums[group].grossSum);
                  $(this.node()).find('td:eq(6)').html(groupSums[group].feeSum);
                  $(this.node()).find('td:eq(7)').html(groupSums[group].netSum);
                  $(this.node()).find('td:eq(8)').html(groupBalances[group]);

                  // Check if all statuses in the group are 'Completed'
                  if (groupStatuses[group] && groupStatuses[group].length > 0) {
                   var allCompleted = groupStatuses[group].every(status => status.includes('Completed'));
                    if (allCompleted) {
                        $(this.node()).find('td:eq(11)').html('<span class="badge badge-info">Completed</span>');
                    } else {
                        $(this.node()).find('td:eq(11)').html('<span class="badge badge-info">Partially Completed</span>');
                    }
                        var hasCanceled = groupStatuses[group].every(status => status.includes('Canceled'));
                        if (hasCanceled) {
                        $(this.node()).find('td:eq(11)').html('<span class="badge badge-danger">Canceled</span>');
                        } else {
                        // Check if any status in the group is 'Pending'
                        var hasPending = groupStatuses[group].every(status => status.includes('Pending'));
                        if (hasPending) {
                            $(this.node()).find('td:eq(11)').html('<span class="badge badge-warning">Pending</span>');
                        } 
                    }
            }
                  // Corrected condition here to check equality
                  if (groupStatuses[group] && groupStatuses[group].length === 1) {
                  $(this.node()).find('td:eq(13)').html('<span style="display: none;"></span>');
                  }
            }
            });

            // Hide rows with the same group
            api.column(2, { page: 'current' }).data().each(function (group, i) {
                  if (last !== group && group !== null) {
                     last = group;
                  } else if (group !== null) {
                     $(rows).eq(i).hide();
                  }
            });
         }
      });

   function formatNumber(number, decimals) {
      if (typeof number !== 'number') {
         return number;
      }
      var formatted = number.toFixed(decimals);
      // Add leading zeros
      var parts = formatted.split('.');
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, "0");
      return parts.join('.');
   }


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

   $('#payoutdatatable').on('click', '.view-details', function() {
  var tr = $(this).closest('tr');
  var row = datatable.row(tr);
  var rowData = row.data();

  if (row.child.isShown()) {
    row.child.hide();
    tr.removeClass('shown');
  } else {
    var payoutGroupId = rowData.payout_group_id; 
    var user_id = rowData.user_id; 
    console.log(user_id);
    var payout_id = rowData.payout_id; 

    var url = "{{ route('getpayoutgroupwise',app()->getLocale()) }}";
    $.ajax({
      url: url,
      type: 'GET',
      data: { payout_group_id: payoutGroupId ,user_id:user_id , payout_id:payout_id},
      success: function(response) {
        console.log('Response:', response); 
        var updatedDetailsHtml = updateDetailsTable(response.payouts , response.payoutGroupId); 
        row.child(updatedDetailsHtml).show();
        tr.addClass('shown');
      },
      error: function(xhr, status, error) {
        console.error('AJAX error:', error);
      }
    });
  }
});

function updateDetailsTable(payouts, payoutGroupId) {
    var updatedDetailsHtml = '<span class="badge badge-danger">' + payoutGroupId + '</span>';
    updatedDetailsHtml += '<table class="table table-bordered" style="background: #efe9e9;">';
    updatedDetailsHtml += '<tr><th>payout Id</th><th>Currency Symbol</th><th>Transactionable Type</th><th>Gross</th><th>MDR</th><th>Net</th><th>Balance</th><th>Money Flow</th><th>Status</th><th>Created At</th></tr>';

    for (var i = 0; i < payouts.length; i++) {
        updatedDetailsHtml += '<tr>';
        updatedDetailsHtml += '<td>' + payouts[i].payout_id + '</td>';
        updatedDetailsHtml += '<td>' + payouts[i].currency_symbol + '</td>';
        updatedDetailsHtml += '<td>' + payouts[i].transactionable_type + '</td>';
        updatedDetailsHtml += '<td>' + payouts[i].gross + '</td>';
        updatedDetailsHtml += '<td>' + payouts[i].fee + '</td>';
        updatedDetailsHtml += '<td>' + payouts[i].net + '</td>';
        updatedDetailsHtml += '<td>' + payouts[i].balance + '</td>';

        // Add logic to create the money flow badge
        var moneyFlowBadge = '';
        if (payouts[i].money_flow != null) {
            if (payouts[i].money_flow === '+') {
                moneyFlowBadge = '<span class="badge badge-info">+</span>';
            } else {
                moneyFlowBadge = '<span class="badge badge-danger">-</span>';
            }
        }
        updatedDetailsHtml += '<td>' + moneyFlowBadge + '</td>';

        // Continue with status badge logic
        var statusBadge = '';
        if (payouts[i].transaction_state_id != null) {
            if (payouts[i].transaction_state_id == 1) {
                statusBadge = '<span class="badge badge-info">Completed</span>';
            } else if (payouts[i].transaction_state_id == 2) {
                statusBadge = '<span class="badge badge-danger">Canceled</span>';
            } else {
                statusBadge = '<span class="badge badge-warning">Pending</span>';
            }
        }
        updatedDetailsHtml += '<td>' + statusBadge + '</td>';

        var createdAt = '-';
        if (payouts[i].created_at != null) {
            var createdAtTimestamp = new Date(payouts[i].created_at);
            if (!isNaN(createdAtTimestamp.getTime())) {
                var options = { timeZone: 'Asia/Kolkata', year: 'numeric', month: 'short', day: '2-digit', hour: 'numeric', minute: 'numeric', hour12: true };
                createdAt = createdAtTimestamp.toLocaleString('en-US', options);
            }
        }
        updatedDetailsHtml += '<td>' + createdAt + '</td>';
        updatedDetailsHtml += '</tr>';
    }
    updatedDetailsHtml += '</table>';
    return updatedDetailsHtml;
}
    </script>
   

@endsection

