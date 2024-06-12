@extends('admin.layouts.master')
@section('content')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.9/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
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
</style>
@endpush

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <button id="openFormBtn">Add tools</button>
              <div id="formContainer" style="display: none;">
                <form id="addItemForm" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" id="item_id" name="item_id" value="">
                  <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                  </div>
                  <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                  </div>
                  <div class="form-group">
                    <label for="file">Upload File:</label>
                    <input type="file" class="form-control-file" id="file" name="file" required>
                  </div>
                  <button type="submit" class="btn btn-primary" id="addItemBtn">Submit</button>
                </form>
              </div>
              <div class="row mt-3">
                <div class="col-lg-12 col-xs-12">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="datatable">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Title</th>
                          <th scope="col">URL</th>
                          <th scope="col">Created At</th>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.9/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.9/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.9/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.9/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
  $(document).ready(function () {
    // Initialize DataTable
    $('#datatable').DataTable({
      processing: true,
      serverSide: true,
      dom: 'lBfrtip',
      buttons: [
        {
          extend: 'csv',
          className: 'buttons-csv',
          text: 'Export CSV',
          exportOptions: {
            columns: [1, 2, 3]
          }
        },
        {
          extend: 'pdf',
          className: 'buttons-pdf',
          text: 'Export PDF',
          exportOptions: {
            columns: [1, 2, 3]
          }
        },
        {
          extend: 'excel',
          className: 'buttons-excel',
          text: 'Export Excel',
          exportOptions: {
            columns: [1, 2, 3]
          }
        },
        {
          extend: 'print',
          text: 'Print',
          exportOptions: {
            columns: [1, 2, 3]
          }
        }
      ],
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'title', name: 'title' },
        { data: 'file_uploaded', name: 'URL', orderable: false },
        { data: 'created_at', name: 'created_at' },
        {
          data: null,
          name: 'action',
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
            return `
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="actionDropdown">
                        <a class="dropdown-item" href="#" onclick="editItem(${data.id})"><i class="fas fa-edit"></i> Edit</a>
                        <a class="dropdown-item" href="#" onclick="deleteItem(${data.id})"><i class="fas fa-trash-alt"></i> Delete</a>
                    </div>
                </div>
            `;
          }
        },
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

  function editItem(itemId) {
    $('#openFormBtn').hide();
    $.ajax({
        url: '{{ route("admin.editItem") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id: itemId
        },
        success: function(response) {
            $('#title').val(response.item.title);
            $('#description').val(response.item.description);
            $('#item_id').val(response.item.id);

            // Set form action to the update route
            $('#addItemForm').attr('action', '{{ route("admin.updateItem") }}');
            $('#addItemBtn').text('Update tools');

            $('#fileLink').remove();
            if (response.item.file_uploaded) {
                let fileLink = `<a id="fileLink" href="${response.item.file_uploaded}" target="_blank">View File</a>`;
                $('#file').after(fileLink);
            }

            $('#formContainer').slideDown();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('An error occurred while fetching item data.');
        }
    });

    // Add click event listener to the update button
    $('#addItemBtn').off('click').on('click', function() {
        location.reload();
    });
}


  function deleteItem(itemId) {
    if (confirm("Are you sure you want to delete this item?")) {
      $.ajax({
        url: '{{ route("admin.deleteItem") }}',
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          id: itemId
        },
        success: function(response) {
          if (response.success) {
            alert('Item deleted successfully!');
            location.reload();
            $('#datatable').DataTable().ajax.reload();
          } else {
            alert('Failed to delete item.');
          }
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
          alert('An error occurred while processing your request.');
        }
      });
    }
  }

  $(document).ready(function() {
  // Toggle the form when clicking the "Add Item" button
  $('#openFormBtn').click(function() {
    $('#addItemForm').attr('action', '{{ route("admin.developertoolstore") }}');
    $('#addItemBtn').text('Add'); 
    $('#formContainer').slideToggle();
  });

  // Handle form submission
  $('#addItemForm').submit(function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    $.ajax({
      url: $(this).attr('action'), 
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        $('#formContainer').slideUp();
        location.reload();
        $('#datatable').DataTable().ajax.reload();
        
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
        toastr.error('An error occurred while processing your request.');
      }
    });
  });
});


</script>


@endpush
