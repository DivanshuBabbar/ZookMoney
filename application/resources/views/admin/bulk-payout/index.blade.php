@extends('admin.layouts.master')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{asset('assets/admin/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('assets/admin/newdash/css/bootstrap-datepicker.min.css')}}">
<style>
/* Custom Styles */
.dataTables_wrapper {
  padding: 20px;
}

.dataTables_length {
  margin-bottom: 20px;
}

.dataTables_filter input {
  border: 1px solid #ced4da;
  padding: 5px 10px;
  border-radius: 5px;
}

.table th,
.table td {
  border-top: 1px solid #dee2e6;
}

.table thead th {
  border-bottom: 2px solid #dee2e6;
}

.table-responsive {
  overflow-x: auto;
}

.dt-buttons .button {
  margin-right: 10px;
  border-radius: 5px;
}

.dt-button {
  padding: 8px 20px;
  border-radius: 5px;
  border: none;
  color: white;
}

.dt-button:hover {
  opacity: 0.8;
}

.buttons-csv {
  background-color: #17a2b8;
}

.buttons-pdf {
  background-color: #28a745;
}

.buttons-excel {
  background-color: #ffc107;
}

.buttons-csv:hover,
.buttons-pdf:hover,
.buttons-excel:hover {
  background-color: #138496;
}

.select2-selection {
  border: 1px solid #ced4da !important;
  border-radius: 5px !important;
}

.select2-selection__arrow {
  height: 38px !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 38px;
}

/* Form Styles */
#formContainer {
  display: none;
  padding: 20px;
  margin-top: 20px;
  border-radius: 10px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
  background-color: #f9f9f9;
  animation: slideDown 0.5s ease;
}

@keyframes slideDown {
  0% {
    opacity: 0;
    transform: translateY(-20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Add Item Button Styles */
#openFormBtn {
  padding: 10px 20px;
  border-radius: 5px;
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s;
}

#openFormBtn:hover {
  background-color: #0056b3;
}
/* Define different button colors */
.btn-complete,
.btn-partial,
.btn-reject {
    border: none;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 4px;
    transition-duration: 0.4s;
}

.btn-complete {
    background-color: #28a745; 
}

.btn-partial {
    background-color: #ffc107; 
}

.btn-reject {
    background-color: #dc3545; 
}

.btn-complete:hover,
.btn-partial:hover,
.btn-reject:hover {
    opacity: 0.8;
}

</style>
@endpush

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="row mt-3">
                <div class="col-lg-12 col-xs-12">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="datatable">
                      <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Request Id</th>
                        <th scope="col">Merchant Name</th>
                        <th scope="col">File Name</th>
                        <th scope="col">Number Of Payout</th>
                        <th scope="col">Total Payout</th>
                        <th scope="col">Status</th>
                        <th scope="col">Remarks</th>
                        <th scope="col">Response File</th>
                        <th scope="col">Schedule At</th>
                        <th scope="col">Uploaded At</th>
                        <th scope="col">Action</th>
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
<script>
$(document).ready(function () {
    var bulkfile = $('#datatable').DataTable({
        processing: true,
        searching: false,
        serverSide: true,
        dom: 'lfrtip',      
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            { data: 'request_id' },
            { data: 'user_name'},
            { data: 'file_name', orderable: false },
            { data: 'total_payout' },
            { data: 'total_amount' },
            { data: 'status' },
            { data: 'remarks' },
            { data: 'response_file' },
            {
                data: 'data_time_schedular',
                render: function (data) {
                    return formatDate(data);
                }
            },
            { data: 'created_at' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <div class="button-container" style= "width:380px;">
                            <button class="btn-complete" onclick="makeComplete(${row.id})">Complete</button>
                            <button class="btn-partial" onclick="partiallyCompleted(${row.id})">Partially Completed</button>
                            <button class="btn-reject" onclick="reject(${row.id})">Reject</button>
                        </div>
                    `;
                }
            }
        ],



      language: {
        "search": "",
        "lengthMenu": "_MENU_",
        "searchPlaceholder": "Search...",
        "paginate": {
          "previous": '<i class="fas fa-angle-left"></i>',
          "next": '<i class="fas fa-angle-right"></i>'
        },
        "processing": '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
        "infoEmpty": "Showing 0 to 0 of 0 entries",
        "infoFiltered": "(filtered from _MAX_ total entries)",
        "emptyTable": "No data available in table"
      }
    });

    $('.dataTables_filter input').attr("placeholder", "Search...");
  });
  function formatDate(dateTimeStr) {
    const months = [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    const [datePart, timePart] = dateTimeStr.split(' ');

    const [year, month, day] = datePart.split('-');
    const [hour, minute, second] = timePart.split(':');

    const monthName = months[parseInt(month, 10) - 1];
    const formattedDate = `${monthName} ${parseInt(day, 10)}, ${year}`;
    const formattedTime = `${parseInt(hour, 10) % 12 || 12}:${minute} ${parseInt(hour, 10) >= 12 ? 'PM' : 'AM'}`;

    return `${formattedDate}, ${formattedTime}`;
}

function makeComplete(requestId) {
    const htmlTemplate = `
    <div>
    <label for="remark">Remark:</label><span style="color:red;">*</span>
    <input type="text" id="remark" class="swal2-input" placeholder="Any remarks..." required>
    </div>
    <div>
    <label for="responseFile">Response File:</label><span style="color:red;">*</span>
    <input type="file" id="responseFile" class="swal2-file" accept=".xls" style="width:70%;">
    </div>
    `;

    Swal.fire({
        title: 'Mark Request as Complete',
        html: htmlTemplate,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Mark as Complete',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const remark = document.getElementById('remark').value;
            const responseFile = document.getElementById('responseFile').files[0];
            var status_type = 'completed';
            if (!remark || !responseFile) {
                Swal.showValidationMessage('Please fill the required field');
                return false; 
            }

            const formData = new FormData();
            formData.append('remark', remark);
            formData.append('responseFile', responseFile);
            formData.append('requestId', requestId);
            formData.append('status_type', status_type);
            
            return $.ajax({
                url: '{{ route("admin.getdata") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    console.log('Server Response:', data);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    Swal.showValidationMessage(`Request failed: ${error}`);
                }
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Marked!',
                'The request has been marked as Complete.',
                'success'
            );
            location.reload();
        }
    });
}


function partiallyCompleted(requestId) {
  const htmlTemplate = `
  <div>
    <label for="remark">Remark:</label><span style="color:red;">*</span>
    <input type="text" id="remark" class="swal2-input" placeholder="Any remarks..." required>
    </div>
    <div>
    <label for="responseFile">Response File:</label><span style="color:red;">*</span>
    <input type="file" id="responseFile" class="swal2-file" accept=".xls" style="width:70%;">
    </div>
    <div>
    <label for="failed_amount">Failed Amount:</label><span style="color:red;">*</span>
    <input type="number" id="failed_amount" class="swal2-file" placeholder="Enter Amount" style="width:70%;">
    </div>
    `;
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to mark this request as Partially Completed?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ffc107', 
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Yes, mark it as Partially Completed!',
        html: htmlTemplate, 
        preConfirm: () => {
          const remark = document.getElementById('remark').value;
          const responseFile = document.getElementById('responseFile').files[0];
          const failed_amount = document.getElementById('failed_amount').value;
          var status_type = 'partially completed';

          if (!remark || !responseFile || !failed_amount) {
              Swal.showValidationMessage('Please fill all required fields');
              return false;
          }

            const formData = new FormData();
            formData.append('remark', remark);
            formData.append('responseFile', responseFile);
            formData.append('requestId', requestId);
            formData.append('failed_amount',failed_amount );
            formData.append('status_type', status_type);
            
            return $.ajax({
                url: '{{ route("admin.getdata") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    console.log('Server Response:', data);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    Swal.showValidationMessage(`Request failed: ${error}`);
                }
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Marked!',
                'The request has been marked as Partially Completed.',
                'success'
            );
            location.reload();
        }
    });
}

function reject(requestId) {
    const htmlTemplate = `
    <div>
    <label for="remark">Remark:</label><span style="color:red;">*</span>
    <input type="text" id="remark" class="swal2-input" placeholder="Any remarks..." required>
    </div>
    `;
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to reject this request?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545', 
        cancelButtonColor: '#6c757d', 
        confirmButtonText: 'Yes, reject it!',
        html: htmlTemplate, 
        preConfirm: () => {
            const remark = document.getElementById('remark').value;
            var status_type = 'rejected';
            if (!remark) {
                Swal.showValidationMessage('Please fill the required field');
                return false; 
            }

            const formData = new FormData();
            formData.append('remark', remark);
            formData.append('requestId', requestId);
            formData.append('status_type', status_type);
            
            return $.ajax({
                url: '{{ route("admin.getdata") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    console.log('Server Response:', data);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    Swal.showValidationMessage(`Request failed: ${error}`);
                }
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Rejected!',
                'The request has been rejected.',
                'success'
            );
            location.reload();
        }
    });
}


</script>


@endpush
