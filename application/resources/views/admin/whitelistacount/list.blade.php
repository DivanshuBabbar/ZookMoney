@extends('admin.layouts.master')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
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
    .custom-dropdown {
      position: relative;
      display: inline-block;
      width: 120px; /* Adjust width as needed */
    }

    .custom-dropdown select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background-color: #f8f8f8;
      cursor: pointer;
    }

    .custom-dropdown::before {
      content: '\25BC';
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      pointer-events: none;
    }
    div#datatable_length {
       float: inline-start;
   }
</style>
@endpush
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">{{$page_title}} </h1>
            </div>
            <div class="col-sm-12">
                  @include('flash')
            </div>
         </div>
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
                              <label>User Name</label>
                              <select class="select2 form-control" name="roles" id="userRole" style="width: 100%;">
                                 <option value=''>-- Select All --</option>
                                 @foreach($user_name as $id => $name)
                                 <option value="{{$name}}">{{$id}}</option>
                                 @endforeach   
                                
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Status</label>
                              <select class="select2 form-control" id="status" style="width: 100%;">
                                 <option value=''>-- Select All --</option>
                                 <option value="pending">Pending</option>
                                 <option value="approved">Approved</option>
                                 <option value="rejected">Rejected</option>
                              </select>
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
                                       <th>#</th>
                                       <th>UserName</th>
                                       <th>Email</th>
                                       <th>Account Holder Name</th>
                                       <th>BankName</th>
                                       <th>Account Number</th>
                                       <th>IFSC</th>
                                       <th>Status</th>
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
      </div>
   </section>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>

   const datatable = $('#datatable').DataTable({
      searchDelay: 500,
      processing: true,
      serverSide: true,
      fixedHeader: true,
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
      ajax: {
            url: '{{ route("admin.whitelistaccount.list") }}',
            type: 'GET',
            data: function (d) {
                  d.user_role = $("#userRole").val();
                  d.status = $("#status").val();
            }
      },
      columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'user_name' , name: 'User.name', orderable: false},
            { data: 'user_email' , name: 'User.email', orderable: false },
            { data: 'account_holder_name', name: 'account_holder_name' },
            { data: 'name', name: 'name' },
            { data: 'account_number', name: 'account_number'},
            { data: 'ifsc_number', name: 'ifsc_number'},
            { data: 'status', name: 'status'},
            { data: 'created_at', name: 'created_at' , orderable: false},

          ],

      columnDefs: [
         { 
            targets: '_all',
            defaultContent: 'N/A'
         },
      ],
      "language": 
      {     
         processing: '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
      }
   });

    $('#status').select2();
    $('#userRole').select2();

    $("#userRole").on('select2:select', function (e) {
      datatable.draw();
   });


    $("#status").on('select2:select', function (e) {
       datatable.draw();
    });


    function sendDropdownValue(selectedValue, id) {
    $.ajax({
        url: '{{ route('admin.updatestatus') }}',
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            id: id,
            selectedValue: selectedValue
        },
        success: function(response) {
            $('[data-id="' + id + '"] select').val(selectedValue);
            toastr.success('Status changed successfully'); // Display success toast
        },
        error: function(xhr, status, error) {
            // Handle error if needed
        }
    });
}

</script>
@endpush