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
                        <h1 class="m-0 text-dark"> {{ $page_title }}</h1>
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
                                    <div class="col-lg-12 col-xs-12">
                                        <form action="{{ route('admin.withdraw.method.store') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <input type="hidden" name="id"
                                                    value="@isset($withdraw->id){{ $withdraw->id }}@endisset">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Payment method name</label>
                                                        <input type="text" name="name" class="form-control" required
                                                            value="@isset($withdraw->name){{ $withdraw->name }}@endisset">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Currency</label>
                                                        <select class="form-control " name="currency_id" required>
                                                            @foreach ($currency as $currencies)
                                                                <option value="{{ $currencies->id }}"
                                                                    @isset($withdraw->currency_id){{ $withdraw->currency_id == $currencies->id ? 'selected' : '' }}@endisset>
                                                                    {{ $currencies->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select class="form-control" name="status" required>
                                                            <option value="">--select</option>
                                                            <option
                                                                value="1"@isset($withdraw->status){{ $withdraw->status == 1 ? 'selected' : '' }}@endisset>
                                                                Active</option>
                                                            <option
                                                                value="0"@isset($withdraw->status){{ $withdraw->status == 0 ? 'selected' : '' }}@endisset>
                                                                Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>List Sequence</label>
                                                        <input type="number" name="sequence_no" class="form-control" 
                                                            required value="@isset($withdraw->sequence_no){{$withdraw->sequence_no}}@endisset">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Is Eligible ?</label>
                                                        <select class="form-control" name="is_eligible" required>
                                                                <option value="">-- select --</option>
                                                                <option value="1"@isset($withdraw->is_eligible){{$withdraw->is_eligible ==1 ? 'selected':''}}@endisset>Yes</option>
                                                                <option value="0"@isset($withdraw->is_eligible){{$withdraw->is_eligible == 0 ? 'selected':''}}@endisset>No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(Auth::user()->role_id == 1)   
                                                <div class="">
                                                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                                                </div> 
                                            @endif
                                        </form>
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
