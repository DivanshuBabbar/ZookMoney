@extends('layouts.app')
@push('styles')
<style>
  
    a#toolsdatatable_next {
    float: right;
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <button id="openwhiteBtn">Add Account</button>
            <div id="formContainer" style="display: none; margin-top: 10px;">
                <form id="addItemListForm">
                  @csrf
                  <div class="form-group">
                    <label for="title">Account Holder Name:</label>
                    <input type="text" class="form-control" id="account_holder_name" name="account_holder_name" required>
                  </div>
                  <div class="form-group">
                    <label for="title">Bank Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                  </div>
                  <div class="form-group">
                    <label for="title">Account Number:</label>
                    <input type="text" class="form-control" id="account_number" name="account_number" required>
                  </div>
                   <div class="form-group">
                    <label for="title">IFSC Number:</label>
                    <input type="text" class="form-control" id="ifsc_number" name="ifsc_number" required>
                  </div>
                 
                  <button type="submit" class="btn btn-primary" id="addListBtn">Submit</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="whitelistaccounttable" style="margin-top: 16px;">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Account Holder Name</th>
                            <th scope="col">Bank Name</th>
                            <th scope="col">Account Number</th>
                            <th scope="col">IFSC</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created At</th>
                        </tr>
                    </thead>
                    @foreach($data as $value)
                    <tbody>
                        <td>{{$value->id}}</td>
                        <td>{{$value->account_holder_name}}</td>
                        <td>{{$value->name}}</td>
                        <td>{{$value->account_number}}</td>
                        <td>{{$value->ifsc_number}}</td>
                        <td>{{$value->status}}</td>
                        <td>{{$value->created_at}}</td>
                    </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>
    $('#openwhiteBtn').click(function() {
        $('#addItemListForm').attr('action', '{{ route("whitelistaccountstore",app()->getLocale()) }}');
        $('#formContainer').slideToggle();
    });

    $('#addItemListForm').submit(function(e) {
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
            $('#whitelistaccounttable').DataTable().ajax.reload();
            
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
            toastr.error('An error occurred while processing your request.');
          }
        });
  });


</script>
@endsection
