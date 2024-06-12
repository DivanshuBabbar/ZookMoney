@extends('admin.layouts.master')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{asset('assets/admin/newdash/css/bootstrap-datepicker.min.css')}}">
<style>
.dataTables_length > label {
    float: right;
    padding-left: 20px;
}
button.dt-button.buttons-pdf.buttons-html5 {
    color: #fff;
    background: #f4bc4b;
    border: solid;
    border-radius: 8px;
}
button.dt-button.buttons-csv.buttons-html5 {
    color: #f8f9fa;
    background: #02bcd1;
    border: solid;
    border-radius: 8px
}

.btn-custom {
  border-radius: 20px; 
  box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); 
  transition: all 0.3s ease; /
}

.btn-custom:hover {
  background-color: #45a049;
  border-color: #45a049;
  box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5); 
}



</style>
@endpush

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{$page_title}}</h1>
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
                                        <table class="table align-items-center" id="adminpayoutdatatable">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
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
        </div>
    </section>
</div>

@endsection

@push('scripts')
<script src="{{asset('assets/admin/newdash/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/admin/newdash/js/bootstrap-datepicker.en-US.min.js')}}" type="text/javascript"></script>
<script>

const datatable = $('#adminpayoutdatatable').DataTable({
    searchDelay: 500,
    processing: true,
    serverSide: true,
    dom: 'Blfrtip',
    fixedHeader: true,
    buttons: [
        {
            extend: 'csv',
            text: '<i class="fa fa-file-csv"> Excel</i>',
            titleAttr: 'Excel',
            exportOptions: { orthogonal: "exportcsv" }
        },
        {
            extend: 'pdf',
            orientation: 'landscape',
            pageSize: 'A3',
            text: '<i class="fa fa-file-pdf-o"> PDF</i>',
            titleAttr: 'PDF',
            exportOptions: { orthogonal: "exportpdf" }
        }
    ],
    lengthMenu: [[50, 100, 150, -1], [50, 100, 150, "All"]],
    ajax: {
        url: '{{ route("admin.getpayoutlist") }}',
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
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'user_name' , name: 'User.name'},
        { data: 'payout_id',
            render: function (data, type, row) {
                if (type === "exportcsv" || type === "exportpdf" ) {
                    return data;
                }
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
        { data: 'balance'},
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
            orderable:false
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
                    groupSums[group] = { feeSum: 0, grossSum: 0 ,netSum: 0 };
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

        // Display the sum in corresponding rows
        api.rows({ page: 'current' }).every(function () {
        var data = this.data();
        var group = data.payout_group_id;
        if (group && groupSums[group]) {
            $(this.node()).find('td:eq(6)').html(groupSums[group].grossSum);
            $(this.node()).find('td:eq(7)').html(groupSums[group].feeSum);
            $(this.node()).find('td:eq(8)').html(groupSums[group].netSum);
            $(this.node()).find('td:eq(9)').html(groupBalances[group]);

            // Check if all statuses in the group are 'Completed'
            if (groupStatuses[group] && groupStatuses[group].length > 0) {
                var allCompleted = groupStatuses[group].every(status => status.includes('Completed'));
            if (allCompleted) {
                $(this.node()).find('td:eq(12)').html('<span class="badge badge-info">Completed</span>');
            } else {
                $(this.node()).find('td:eq(12)').html('<span class="badge badge-info">Partially Completed</span>');
            }
                var hasCanceled = groupStatuses[group].every(status => status.includes('Canceled'));
                if (hasCanceled) {
                $(this.node()).find('td:eq(12)').html('<span class="badge badge-danger">Canceled</span>');
                } else {
                // Check if any status in the group is 'Pending'
                var hasPending = groupStatuses[group].every(status => status.includes('Pending'));
                if (hasPending) {
                    $(this.node()).find('td:eq(12)').html('<span class="badge badge-warning">Pending</span>');
                } 
                }
            }
            
            // Corrected condition here to check equality
            if (groupStatuses[group] && groupStatuses[group].length === 1) {
            $(this.node()).find('td:eq(14)').html('<span style="display: none;"></span>');
            }
        }
        });

        // Hide rows with the same group
        api.column(3, { page: 'current' }).data().each(function (group, i) {
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
        if (!validateEmail(multiple_email[i])) {
            $('.errorEmail').html('');
            $('.errorEmail').html('incorrect email address');
            return false;
        }
    }

    if ($('#emailVerify').val() == '' || $('#emailVerify').val() == null ) {
        $('.errorEmail').html('');
        $('.errorEmail').html('field required');
        return false;
    } else {
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

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}

// Handle click event for "View Details" button
$('#adminpayoutdatatable').on('click', '.view-details', function() {
  var tr = $(this).closest('tr');
  var row = datatable.row(tr);
  var rowData = row.data();

  if (row.child.isShown()) {
    row.child.hide();
    tr.removeClass('shown');
  } else {
    var payoutGroupId = rowData.payout_group_id; 
    var user_id = rowData.user_id; 
    var payout_id = rowData.payout_id; 

    var url = "{{ route('admin.getlistbypayoutgroup') }}";
    $.ajax({
      url: url,
      type: 'GET',
      data: { payout_group_id: payoutGroupId ,user_id:user_id , payout_id:payout_id},
      success: function(response) {
        
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
    updatedDetailsHtml += '<table class="table table-bordered" style="background: lightgrey;">';
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
@endpush
