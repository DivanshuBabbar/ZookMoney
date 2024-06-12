@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Currency Exchange </h1>
                    </div>
                    <div class="text-right col-sm-6">
                        <a href="{{ route('admin.exchange-rate.create') }}" class="btn btn-primary btn-sm">Add New</a>
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
                                            <table class="table align-items-center">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"> Id </th>
                                                        <th scope="col"> First Currency </th>
                                                        <th scope="col">Second Currency </th>
                                                        <th scope="col">Exchanges To Second Currency Value</th>
                                                        <th scope="col"> Created At </th>
                                                        <th scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty('$exchange'))
                                                        @foreach ($exchange as $key => $row)
                                                            <tr>
                                                                <td>{{ $row->id }}</td>
                                                                <td>{{ $row->firstCurrency->name }}</td>
                                                                <td>{{ $row->secondCurrency->name }}</td>
                                                                <td>{{ $row->exchanges_to_second_currency_value }}</td>
                                                                <td>{{ $row->created_at }}</td>
                                                                <td>
                                                                    <a href="{{ route('admin.exchange-rate.edit', $row->id) }}"
                                                                        class="btn btn-primary btn-sm">Edit</a>
                                                              @if(Auth::user()->role_id == 1)       <a href="javascript:void(0)"
                                                                        data-id="{{ $row->id }}"
                                                                        class="btn btn-danger btn-sm delete">
                                                                        Delete</a> @endif
                                                                        
                                                                    @if(Auth::user()->role_id == 4)     <a href="javascript:void(0)""#"
                                                                        class="btn btn-danger btn-sm delete">
                                                                        You can't Delete</a> @endif
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
                        {{ $exchange->links() }}
                    </div>
                </div>
            </div>
    </div>
    </div>
    </section>
    </div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
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
                            window.location.href = "{{ route('admin.exchange-rate.delete') }}" +
                                '/' + id;
                        }
                    });
                }

            });
        });
    </script>
@endsection
