@extends('admin.layouts.master')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Countries </h1>
                </div>
                {{-- <ol class="breadcrumb float-sm-right">
                </ol> --}}
                <div class="text-right col-sm-6">
                    <a href="{{ route('admin.countries.create') }}" class="btn btn-primary btn-sm">Add New</a>
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
                                <div class="col-lg-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table align-items-center datatable">
                                            <thead>
                                                <tr>

                                                    <th> Id </th>
                                                    <th> Code </th>
                                                    <th> Name </th>
                                                    <th> Nicename </th>
                                                    <th> Iso3 </th>
                                                    <th> Numcode </th>
                                                    <th> Prefix </th>
                                                    <th class="actions">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty('$country'))
                                                    @foreach ($country as $key => $row)
                                                        <tr>
                                                            <td>{{ $row->id }}</td>
                                                            <td>{{ $row->code }}</td>
                                                            <td>{{ $row->name }}</td>
                                                            <td>{{ $row->nicename }}</td>
                                                            <td>{{ $row->iso3 }}</td>
                                                            <td>{{ $row->numcode }}</td>
                                                            <td>{{ $row->prefix }}</td>
                                                            <td>
                                                                <div class="btn-group">
                                                                <a href="{{ route('admin.countries.edit', $row->id) }}"
                                                                    class="btn btn-primary btn-sm ">Edit</a>
                                                                @if(Auth::user()->role_id == 1)      
                                                                <a href="javascript:void(0)" data-id="{{ $row->id }}" 
                                                                        class="btn btn-danger btn-sm delete">Delete</a> 
                                                                @endif
                                                                @if(Auth::user()->role_id == 4)     
                                                                <a href="javascript:void(0)" 
                                                                    class="btn btn-danger btn-sm delete">You can't Delete</a> 
                                                                @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
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
    const datatable = $('.datatable').DataTable();

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
                    window.location.href = "{{ route('admin.countries.delete') }}" + '/' + id;
                }
            });
        }
    });
</script>
@endpush

