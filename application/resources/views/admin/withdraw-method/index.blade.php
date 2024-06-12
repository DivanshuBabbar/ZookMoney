@extends('admin.layouts.master')
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css">
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ $page_title }}</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.withdraw.method.create') }}" class="btn btn-primary btn-sm">Add Withdraw</a>
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
                                        <label>Status</label>
                                        <select class="form-control select2" style="width: 100%;" id="status">
                                            <option value=''>-- Select All --</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Is eligible ?</label>
                                        <select class="form-control select2" style="width: 100%;" id="isEligible">
                                            <option value=''>-- Select All --</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
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
                            <h3 class="card-title mx-4 mt-3"></h3>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-xs-10">
                                        <div class="table-responsive">
                                            <table class="table align-items-center datatable">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Sequence</th>
                                                        <th scope="col">Payment Method</th>
                                                        <th scope="col">Currency</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Eligible</th>
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <h3 class="card-title mx-4 mt-3"></h3>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <form action="{{route('admin.withdraw.method.store.terms')}}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Terms and Conditions for Withdrawal</label>
                                                    <textarea type="text" name="setting[withdrawal_terms_and_conditions]" class="form-control m-1">
                                                        {{ general_setting('withdrawal_terms_and_conditions') }}
                                                    </textarea>
                                                </div>
                                            </div>
                                            @if(Auth::user()->role_id == 1)  
                                                <div class="">
                                                    <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                                                </div> 
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <section class="content">
    </div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js" 
    integrity="sha512-ZESy0bnJYbtgTNGlAD+C2hIZCt4jKGF41T5jZnIXy4oP8CQqcrBGWyxNP16z70z/5Xy6TS/nUZ026WmvOcjNIQ==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

    $("textarea[name='setting[withdrawal_terms_and_conditions]']").summernote({
        tabsize: 2,
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['color', ['color']],
            ['font', ['fontsize', 'bold', 'italic', 'underline', 'clear', 'strikethrough', 'superscript', 'subscript']],
            ['para', ['ul', 'ol', 'paragraph', 'height']],
            ['insert', ['table', 'link', 'picture', 'hr']],
            ['view', ['fullscreen']]
        ]
    });

    const datatable = $('.datatable').DataTable({
        searchDelay: 500,
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.withdraw.method.list") }}',
            type: 'GET',
            data: function (d) {
                d.status = $("#status").val();
                d.is_eligible = $("#isEligible").val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'sequence_no' },
            { data: 'name' },
            { data: 'currencies.name' },
            { data: 'status' },
            { data: 'is_eligible' },
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
        "language": 
        {     
            processing: '<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
        }
    });

    $('#isEligible').select2();
    $('#status').select2();

    $("#isEligible").on('select2:select', function (e) {
        datatable.draw();
    });
    $("#status").on('select2:select', function (e) {
        datatable.draw();
    });
        
    $('body').on("click", ".delete", function(event) {
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
                        url: "{{ route('admin.withdraw.method.delete') }}" + '/' + id,
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
</script>
@endpush
