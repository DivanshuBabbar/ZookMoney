@extends('layouts.app')
@push('styles')
<style>
    a#toolsdatatable_next {
        float: right;
    }

</style>
@endpush

@section('content')
<div class="container" style="width:100%;">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="toolsdatatable" >
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">URL</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Description</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
