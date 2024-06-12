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
                              <label>User Type</label>
                              <select class="select2 form-control" name="roles" id="userRole" style="width: 100%;">
                                 <option value=''>-- Select All --</option>
                                 @foreach($user_types as $id => $name)
                                 <option value="{{$id}}">{{$name}}</option>
                                 @endforeach   
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label>Status</label>
                              <select class="select2 form-control" id="status" style="width: 100%;">
                                 <option value=''>-- Select All --</option>
                                 <option value="spam">Spam</option>
                                 <option value="inactive">Inactive</option>
                                 <option value="suspicious">Suspicious</option>
                              </select>
                           </div>
                        </div>
                         <div class="col-md-2">
                          <label>Email Report</label>
                           <div class="form-group row">
                              <div class="col-md-12">
                                 <div class="form-group d-flex flex-row justify-content-between gap-3" style="padding-left: 23px;">
                                    <button type="button" id="openUserModal" class="btn btn-primary" value="">Email</button>
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
                                       <th>#</th>
                                       <th>Avatar</th>
                                       <th>Id</th>
                                       <th>name</th>
                                       <th>type</th>
                                       <th>email</th>
                                       <th>Phonenumber</th>
                                       <th>Balance</th>
                                       {{--<th>Main Hold Balance</th>
                                       <th>Payout Hold Balance</th>--}}
                                       <th>Payout Balance</th>
                                       <th>Action</th>
                                       <th>Block User</th>
                                       <th>Status</th>
                                        {{--<th>Tranfer<th>--}}
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
<div class="modal" tabindex="-1" role="dialog" id="myUserModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Send User List</h5>
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
<div class="modal fade" id="mainbalanceModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Main Balance to Hold</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
            <div class="form-group">
                <label for="mainbalance-name" class="col-form-label">Transfer Amount From Main Balance To Hold Balance</label>
                <input type="text" class="form-control" id="mainbalance-name" step="0.01">
            </div>
            <span class="error_mainbalance" style="color: red;" ></span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="mainbalance_amount">Transfer</button>
          </div>
          </form>
        </div>
    </div>
</div>

<div class="modal fade" id="holdbalanceModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Hold Balance to Main</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
            <div class="form-group">
                <label for="holdbalance-name" class="col-form-label">Transfer Amount From Hold Balance To Main Balance</label>
                <input type="text" class="form-control" id="holdbalance-name" step="0.01">
            </div>
            <span class="error_holdbalance" style="color: red;" ></span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="holdbalance_amount">Transfer</button>
          </div>
          </form>
        </div>
    </div>
</div>

<div class="modal fade" id="payoutholdbalanceModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Payout Balance to Hold</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
            <div class="form-group">
                <label for="payoutholdbalance-name" class="col-form-label">Transfer Amount From Payout Balance To Hold Balance</label>
                <input type="text" class="form-control" id="payoutholdbalance-name" step="0.01">
            </div>
            <span class="error_payoutholdbalance" style="color: red;" ></span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="payoutholdbalance_amount">Transfer</button>
          </div>
          </form>
        </div>
    </div>
</div>

<div class="modal fade" id="holdpayoutbalanceModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Hold Balance to Payout</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
            <div class="form-group">
                <label for="holdpayoutbalance-name" class="col-form-label">Transfer Amount From Hold Balance To Payout Balance</label>
                <input type="text" class="form-control" id="holdpayoutbalance-name" step="0.01">
            </div>
            <span class="error_holdpayoutbalance" style="color: red;" ></span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="holdpayoutbalance_amount">Transfer</button>
          </div>
          </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
   <script>
    
      $(document).on("change", ".user_data_toggle", function () {
         var user_id = $(this).attr('data-id');
         if (this.checked) {
            var checked = 1;
         }else{
            checked = 0;
         }

         $.ajax({
            url: "{{route('admin.blockuser')}}",
            type: "GET",
            data:{
               user_id:user_id,
               checked:checked
            },
            success: function (response) {
               
            }
         });
      });

   </script>
<script>
   $('#userRole').select2();

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
            titleAttr : 'Excel',
            exportOptions: {
               columns: [ 2, 3, 4, 5, 6, 7, 8 ]
            }
        },

         {
            extend : 'pdf',
            orientation : 'landscape',
            pageSize : 'A4', // You can also use "A1","A2" or "A3", most of the time "A3" works the best.
            text : '<i class="fa fa-file-pdf-o"> PDF</i>',
            titleAttr : 'PDF',
            exportOptions: {
               columns: [ 2, 3, 4, 5, 6, 7, 8 ]
            }
        }
          
      ],
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
      ajax: {
            url: '{{ route("admin.user.list") }}',
            type: 'GET',
            data: function (d) {
                  d.user_role = $("#userRole").val();
                  d.multiple_email = $('#emailVerify').val();
                  d.openUserModal = $('#openUserModal').val();
                  d.status = $("#status").val();
            }
      },
      columns: [
    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
    { data: 'avatar' },
    { data: 'id' },
    {
    data: 'name',
        render: function(data, type, full, meta) {
    if (full.merchants_count > 0) {
        return '<a href="{{ route('admin.merchant.list') }}?name=' + encodeURIComponent(data) + '">' + data + '</a>';
    } else {
            return data;
        }
    }
    },
    { data: 'role',
        render: function(data, type, full, meta) {
            var name = full.name; // Assuming "name" is a field in your data object
            if (full.merchants_count > 0) {
                return '<a href="{{ route('admin.merchant.list') }}?name=' + encodeURIComponent(name) + '">' + data + '</a>';
            } else {
                return data;
            }
        }
    },

    {
    data: 'email',
        render: function(data, type, full, meta) {
            var id = full.id;
            var route = "{{ route('impersonateUser',['language'=>app()->getLocale(),'user_id'=>'']) }}"
           return '<a target="_blank" href="'+ route +'/'+ id + '"> ' + data + '</a>';
        }
    },

    { data: 'phonenumber' },
    { data: 'balance' },
    // { data: 'main_hold_balance' },
    // { data: 'payout_hold_balance'},
    { data: 'payout_balance' },
    { data: 'action' ,orderable:false,searchable:false},
    { data: 'account_status',orderable: false,searchable: false},
    {
        data: 'status',orderable: false,searchable: false
    }

    // {
    //   data: null,
    //   name: 'transfer',
    //   orderable: false,
    //   searchable: false,
    //   render: function(data, type, row) {
    //     return `
    //       <div class="dropdown">
    //         <button class="btn btn-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    //           <i class="fas fa-ellipsis-v"></i>
    //         </button>
    //         <div class="dropdown-menu" aria-labelledby="actionDropdown">
    //           <a class="dropdown-item mainbalance_model" href="#" data-id="${data.id }" data-currency-id="${data.currency_id}">
    //             <i class="fas fa-exchange-alt"></i>Main balance to hold balance
    //           </a>
    //           <a class="dropdown-item holdbalance_model" href="#" data-id="${data.id}" data-currency-id="${data.currency_id}">
    //             <i class="fas fa-exchange-alt"></i> Hold balance to main balance
    //           </a>
    //           <a class="dropdown-item payoutholdbalance_model" href="#" data-id="${data.id}" data-currency-id="${data.currency_id}">
    //             <i class="fas fa-exchange-alt"></i> Payout balance to hold balance
    //           </a>
    //           <a class="dropdown-item holdpayoutbalance_model" href="#" data-id="${data.id}" data-currency-id="${data.currency_id}">
    //             <i class="fas fa-exchange-alt"></i> Hold balance to payout balance
    //           </a>
    //         </div>
    //       </div>
    //     `;
    //   }
    // }
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

   var nameParameter = "{{ request()->input('name') }}";
    if (nameParameter) {
    datatable.search(nameParameter).draw();
    }
   $('.dt-buttons').before('<b><p>Export To </p></b>');

   $("#userRole").on('select2:select', function (e) {
      datatable.draw();
   });

   $('#status').select2();

      $("#status").on('select2:select', function (e) {
         datatable.draw();
      });

   $('#openUserModal').on('click', function(e) {
     $('#myUserModal').modal('show');
   });

   $('#sendEmail').on('click', function(e) {
      $('#openUserModal').val('1');
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
         $('#myUserModal').modal('hide');
      }
     
   });

   $('#myUserModal').on('hidden.bs.modal', function (e) {
      $('#openUserModal').val('');
     $(this).find("input").val('').end();
   });

   function validateEmail($email) {
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      return emailReg.test( $email );
   }

   //main to hold balance
   $(document).on('click', '.mainbalance_model', function(e) {
    e.preventDefault();
    var userId = $(this).data('id');
    var currencyId = $(this).data('currency-id');
    $('#mainbalanceModel').modal('show');

    $('#mainbalance_amount').data('userId', userId);
    $('#mainbalance_amount').data('currencyId', currencyId); 
    $('#mainbalance-name').val('');
});

// Click event for #mainbalance_amount
$('#mainbalance_amount').on('click', function(e) {
    e.preventDefault();
    var userId = $(this).data('userId');
    var currencyId = $(this).data('currencyId'); 
    var amount = $('#mainbalance-name').val();

    if (!amount || amount == '') {
        $('.error_mainbalance').html('Amount is required');
        return false;
    } else if (amount <= 0) {
        $('.error_mainbalance').html('Amount should be greater than zero');
        return false;
    }

    $.ajax({
        type: "GET",
        url: '{{ route("admin.check-main-balance") }}',
        data: {
            id: userId,
            currency_id: currencyId, 
            amount: amount
        },
        cache: false,
        success: function(data) {
            $('.error_mainbalance').html('');
            console.log(data);
            if (data.status == 0) {
                $('.error_mainbalance').html(data.message);
            }
            if (data.status == 1) {
                $('#mainbalanceModel').modal('hide');
                swal.fire({
                    title: "Are you sure?",
                    text: "Main To Hold Balance Transfer!",
                    icon: "warning",
                    buttons: [
                        'No, cancel it!',
                        'Yes, I am sure!'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        swal.fire({
                            title: 'Transferred!',
                            text: 'Amount Transferred Successfully!',
                            icon: 'success'
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        swal.fire("Cancelled", "Transfer Cancelled :)", "error");
                    }
                });
            }
        },
        error: function(data) {

        }
    });
});


 //hold to main
 $(document).ready(function() {
    $(document).on('click', '.holdbalance_model', function(e) {
        e.preventDefault();
        var userId = $(this).data('id'); 
        var currencyId = $(this).data('currencyId'); 
        $('#holdbalanceModel').modal('show');
        
        $('#holdbalance_amount').data('userId', userId);
        $('#holdbalance_amount').data('currencyId', currencyId); 

        $('#holdbalance-name').val('');
    });

    $('#holdbalance_amount').on('click', function(e) {
        e.preventDefault();
        var userId = $(this).data('userId'); 
        var currencyId = $(this).data('currencyId'); 

        var amount = $('#holdbalance-name').val();

        if (!amount || amount == '') {
            $('.error_holdbalance').html('Amount is required');
            return false;
        } else if (amount <= 0) {
            $('.error_holdbalance').html('Amount should be greater than zero');
            return false;
        }

        $.ajax({
            type: "GET",
            url: '{{ route("admin.check-hold-balance") }}',
            data: {
                id: userId, 
                currency_id: currencyId, 
                amount: amount
            },
            cache: false,
            success: function(data) {
                $('.error_holdbalance').html('');
                console.log(data);
                if (data.status == 0) {
                    $('.error_holdbalance').html(data.message);
                }
                if (data.status == 1) {
                    $('#holdbalanceModel').modal('hide');
                    swal.fire({
                        title: "Are you sure?",
                        text: "Main To Hold Balance Transfer!",
                        icon: "warning",
                        buttons: [
                            'No, cancel it!',
                            'Yes, I am sure!'
                        ],
                        dangerMode: true,
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            swal.fire({
                                title: 'Transferred!',
                                text: 'Amount Transferred Successfully!',
                                icon: 'success'
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            swal.fire("Cancelled", "Transfer Cancelled :)", "error");
                        }
                    });
                }
            },
            error: function(data) {

            }
        });
    });
});

//payout to hold
$(document).ready(function() {
    $(document).on('click', '.payoutholdbalance_model', function(e) {
        e.preventDefault();
        var userId = $(this).data('id'); 
        var currencyId = $(this).data('currency-id'); 
        console.log("Clicked user ID: ", userId); 
        $('#payoutholdbalanceModel').modal('show');
        
        $('#payoutholdbalance_amount').data('userId', userId);
        $('#payoutholdbalance_amount').data('currencyId', currencyId);

        $('#payoutholdbalance-name').val('');
    });

    $('#payoutholdbalance_amount').on('click', function(e) {
        e.preventDefault();
        var userId = $(this).data('userId'); 
        var currencyId = $(this).data('currencyId');
        var amount = $('#payoutholdbalance-name').val();

        if (!amount || amount == '') {
            $('.error_payoutholdbalance').html('Amount is required');
            return false;
        } else if (amount <= 0) {
            $('.error_payoutholdbalance').html('Amount should be greater than zero');
            return false;
        }

        $.ajax({
            type: "GET",
            url: '{{ route("admin.check-payouthold-balance") }}',
            data: {
                id: userId, 
                currency_id: currencyId, 
                amount: amount
            },
            cache: false,
            success: function(data) {
                $('.error_payoutholdbalance').html('');
                console.log(data);
                if (data.status == 0) {
                    $('.error_payoutholdbalance').html(data.message);
                }
                if (data.status == 1) {
                    $('#payoutholdbalanceModel').modal('hide');
                    swal.fire({
                        title: "Are you sure?",
                        text: "Payout To Hold Balance Transfer!",
                        icon: "warning",
                        buttons: [
                            'No, cancel it!',
                            'Yes, I am sure!'
                        ],
                        dangerMode: true,
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            swal.fire({
                                title: 'Transferred!',
                                text: 'Amount Transferred Successfully!',
                                icon: 'success'
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            swal.fire("Cancelled", "Transfer Cancelled :)", "error");
                        }
                    });
                }
            },
            error: function(data) {

            }
        });
    });
});

function sendDropdownValue(selectedValue, id) {
    $.ajax({
        url: '{{ route('admin.changestatus') }}',
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


//hold to payout
$(document).ready(function() {
    $(document).on('click', '.holdpayoutbalance_model', function(e) {
        e.preventDefault();
        var userId = $(this).data('id'); 
        var currencyId = $(this).data('currency-id'); 
        $('#holdpayoutbalanceModel').modal('show');
        
        $('#holdpayoutbalance_amount').data('userId', userId);
        $('#holdpayoutbalance_amount').data('currencyId', currencyId); 

        $('#holdpayoutbalance-name').val('');
    });

    $('#holdpayoutbalance_amount').on('click', function(e) {
        e.preventDefault();
        var userId = $(this).data('userId'); 
        var currencyId = $(this).data('currencyId'); 
        var amount = $('#holdpayoutbalance-name').val();

        if (!amount || amount == '') {
            $('.error_holdpayoutbalance').html('Amount is required');
            return false;
        } else if (amount <= 0) {
            $('.error_holdpayoutbalance').html('Amount should be greater than zero');
            return false;
        }

        $.ajax({
            type: "GET",
            url: '{{ route("admin.check-holdpayout-balance") }}',
            data: {
                id: userId, 
                currency_id: currencyId,
                amount: amount
            },
            cache: false,
            success: function(data) {
                $('.error_holdpayoutbalance').html('');
                console.log(data);
                if (data.status == 0) {
                    $('.error_holdpayoutbalance').html(data.message);
                }
                if (data.status == 1) {
                    $('#holdpayoutbalanceModel').modal('hide');
                    swal.fire({
                        title: "Are you sure?",
                        text: "Hold to Payout Balance Transfer!",
                        icon: "warning",
                        buttons: [
                            'No, cancel it!',
                            'Yes, I am sure!'
                        ],
                        dangerMode: true,
                    }).then(function(isConfirm) {
                        if (isConfirm) {
                            swal.fire({
                                title: 'Transferred!',
                                text: 'Amount Transferred Successfully!',
                                icon: 'success'
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            swal.fire("Cancelled", "Transfer Cancelled :)", "error");
                        }
                    });
                }
            },
            error: function(data) {

            }
        });
    });
});






</script>
@endpush