@extends('admin.layouts.master')
@push('styles')
<link rel="stylesheet" href="{{asset('assets/admin/newdash/css/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.bootstrap.css">

<style>
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

th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
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
                                 <label>Currencies</label>
                                 <select class="form-control select2" style="width: 100%;" id="currency">
                                       <option value=''>-- Select All --</option>
                                       @foreach($currencies as $id => $name)
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
                                       <button type="button" id="openEmailModal" class="btn btn-primary" value="" style="background-color:#00a49e; color: white; font-weight: bold; border: 2px solid white;">Email</button>
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
                  <div id="datatable-badge-container"></div>
                     <div class="row">
                        <div class="col-lg-12 col-xs-12">
                           <div class="table-responsive">
                           <table class="table align-items-center datatable stripe row-border order-column" style="width: 100%;">
                                 <thead>
                                    <tr>
                                       <th scope="col">#</th>
                                       <th scope="col">Name</th>
                                       <th scope="col">User</th>
                                       <th scope="col">Email</th>
                                       <th scope="col">Mobile Number</th>
                                       <th scope="col">Logo</th>
                                       <th scope="col">Site Url</th>
                                       <th scope="col">Merchant Key</th>
                                       <th scope="col">Status</th>
                                       <th scope="col">Fixed Fee</th>
                                       <th scope="col">Percentage Fee</th>
                                       <th scope="col">Payout Fixed Fee</th>
                                       <th scope="col">Payout Percentage Fee</th>
                                       <th scope="col">Wire Transfer Fixed Fee</th>
                                       <th scope="col">Wire Transfer Percentage Fee</th>
                                       <th scope="col">Minimum Payin</th>
                                       <th scope="col">Maximum Payin</th>
                                       <th scope="col">Minimum Payout</th>
                                       <th scope="col">Maximum Payout</th>
                                       <th scope="col">Action</th>
                                       <th scope="col">Bulk Payout</th>
                                       <th scope="col">White Label</th>
                                       <th scope="col">Wire Transfer</th>
                                       <th scope="col">Payout Status</th>
                                       <th scope="col">Payin Status</th>
                                       <th scope="col">FTD Status</th>
                                       <th scope="col">Created At</th>
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
</section>
</div>

@endsection
@push('scripts')
   <script src="{{asset('assets/admin/newdash/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
   <script src="{{asset('assets/admin/newdash/js/bootstrap-datepicker.en-US.min.js')}}" type="text/javascript"></script>
   <script src="https://cdn.datatables.net/fixedcolumns/3.2.1/js/dataTables.fixedColumns.min.js"></script>
   <script>
    
      $(document).on("change", ".merchant_data_toggle", function () {
         var merchant_id = $(this).attr('data-id');
         var type = $(this).attr('data-type');
       
         if (this.checked) {
            var checked = 1;
         }else{
            checked = 0;
         }

         $.ajax({
            url: "{{route('admin.bulkfileupload')}}",
            type: "GET",
            data:{
               merchant_id:merchant_id,
               checked:checked,
               type:type
            },
            success: function (response) {
               
            }
         });
      });

     

   </script>
   <script>

       // Initialize the DataTable
const datatable = $('.datatable').DataTable({
    searchDelay: 500,
    processing: true,
    fixedColumns: {
      leftColumns: 2
    },
    paging: true,
    scrollCollapse: true,
    scrollX: true,
    serverSide: true,
    ajax: {
        url: '{{ route("admin.merchant.list") }}',
        type: 'GET',
        data: function (d) {
            d.currency_id = $("#currency").val();
            d.created_at_from = $('#createdAtFrom').val();
            d.created_at_to = $('#createdAtTo').val();
            d.multiple_email = $('#emailVerify').val();
            d.openEmailModal = $('#openEmailModal').val();
        }
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'name' },
        { data: 'user_id' },
        { data: 'user_email', orderable: false, searchable: false},
        { data: 'user_number', orderable: false, searchable: false},
        { data: 'logo' },
        { data: 'site_url' },
        { data: 'merchant_key' },
        { data: 'status' },
        { data: 'merchant_fixed_fee'},
        { data: 'merchant_percentage_fee'},
        { data: 'payout_fixed_fee'},
        { data: 'payout_percentage_fee'},
        { data: 'wire_transfer_fixed_fee'},
        { data: 'wire_transfer_percentage_fee'},
        { data: 'min_payin'},
        { data: 'max_payin'},
        { data: 'min_payout'},
        { data: 'max_payout'},
        { data: 'action', orderable: false, searchable: false },
        { data: 'file_upload', orderable: false, searchable: false },
        { data: 'white_label', orderable: false, searchable: false },
        { data: 'wire_transfer', orderable: false, searchable: false },
        { data: 'payout_status', orderable: false, searchable: false },
        { data: 'payin_status' , orderable: false, searchable: false},
        { data: 'ftd_status' , orderable: false, searchable: false},
      //   { data: 'perform_actions' },
        { data: 'created_at' },
    ],
    columnDefs: [
        {
            targets: '_all',
            defaultContent: 'N/A'
        },
    ],
    order: [[16, 'desc']],
    language: {
        processing: '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
    }
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

   // Add buttons for filtering
   $('.dataTables_filter').prepend(`
      <button id="all" style="background-color:#00a49e; color: white; font-weight: bold; border: 2px solid white;">All</button>
      <button id="fees" style="background-color:#00a49e; color: white; font-weight: bold; border: 2px solid white;">Fees Only</button>
      <button id="features" style="background-color:#00a49e; color: white; font-weight: bold; border: 2px solid white;">Features Only</button>
      <button id="fees-features" style="background-color:#00a49e; color: white; font-weight: bold; border: 2px solid white;">Fees and Features Only</button>
   `);

   // Implement filtering functionality
   $('#all').on('click', function() {
      datatable.columns().visible(true);
      datatable.columns().search('').draw();
   });

   $('#fees').on('click', function() {
      datatable.columns().visible(false);
      datatable.columns([0, 1, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18 ,19, 25 ]).visible(true);
      datatable.columns([0, 1]).visible(true); // Fix columns
      datatable.columns().search('').draw();
   });

   $('#features').on('click', function() {
      datatable.columns().visible(false);
      datatable.columns([0, 1, 19, 20, 21, 22, 23 , 24 , 25]).visible(true);
      datatable.columns([0, 1]).visible(true); // Fix columns
      datatable.columns().search('').draw();
   });

   $('#fees-features').on('click', function() {
      datatable.columns().visible(false);
      datatable.columns([0, 1, 9, 10, 11, 12, 13, 14, 15, 16, 17 , 18 , 19 , 20, 21, 22, 23 , 24 ,25]).visible(true);
      datatable.columns([0, 1, 17]).visible(true); // Fix columns
      datatable.columns().search('').draw();
   });

   var nameParameter = "{{ request()->input('name') }}";
   if (nameParameter) {
      datatable.search(nameParameter).draw();

      var badgeHTML = '<a href="{{ route('admin.user.list') }}?name=' + encodeURIComponent(nameParameter) + '" id="merchant-link" class="badge badge-info">Merchant Name: ' + nameParameter + '</a>';
      $('#datatable-badge-container').html(badgeHTML);
   }

   $(document).on('click', '#merchant-link', function(e) {
      e.preventDefault();
      var url = $(this).attr('href');
      window.location.href = url;
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

      $('#currency').select2();

      $("#currency").on('select2:select', function (e) {
         datatable.draw();
      });

      $('body').on("click", ".delete", function (event) {
         event.preventDefault();
         var id = $(this).attr('data-id');
         if (id != null) {
            swal.fire({
               title: "Are you sure?",
               text: "You will not be able to recover this record!",
               type: "warning",
               showCancelButton: true,
               confirmButtonColor: "#DD6B55",
               confirmButtonText: "Yes, delete it!",
               closeOnConfirm: false
            }).then((result) => {
               if (result.value) {
                  $.ajax({
                     url: "{{route('admin.delete.merchant')}}"+'/'+id,
                     type: "GET",
                     success: function (response) {
                        datatable.draw();
                        Toast.fire({
                           icon: 'success',
                           title: 'Record deleted successfully'
                        })
                     },
                     error: function (response) {
                        Toast.fire({
                           icon: 'error',
                           title: 'Failed to delete the record'
                        })
                     }
                  })
               }
            });
         }
      });
       $(document).on("click", ".dropdown-menu", function (event) {
         event.stopPropogation();
       });

   function validateEmail($email) {
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      return emailReg.test( $email );
   }
   </script>

@endpush