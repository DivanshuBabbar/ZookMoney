@extends('admin.layouts.master')
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
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-xs-12">
                                        <form action="{{ route('admin.countries.store') }}" method="POST">
                                            @csrf
                                            <div class="form-group  col-md-12">

                                                <label for="name">Code</label>
                                                <input required type="text" class="form-control" name="code"
                                                    placeholder="Code" value="">
                                            </div>
                                            <!-- GET THE DISPLAY OPTIONS -->
                                            <div class="form-group  col-md-12">

                                                <label for="name">Name</label>
                                                <input required type="text" class="form-control" name="name"
                                                    placeholder="Name" value="">


                                            </div>
                                            <!-- GET THE DISPLAY OPTIONS -->
                                            <div class="form-group  col-md-12">

                                                <label for="name">Nicename</label>
                                                <input required type="text" class="form-control" name="nicename"
                                                    placeholder="Nicename" value="">


                                            </div>
                                            <!-- GET THE DISPLAY OPTIONS -->
                                            <div class="form-group  col-md-12">

                                                <label for="name">Iso3</label>
                                                <input type="text" class="form-control" name="iso3" placeholder="Iso3"
                                                    value="">


                                            </div>
                                            <!-- GET THE DISPLAY OPTIONS -->
                                            <div class="form-group  col-md-12">

                                                <label for="name">Numcode</label>
                                                <input type="text" class="form-control" name="numcode"
                                                    placeholder="Numcode" value="">
                                            </div>
                                            <!-- GET THE DISPLAY OPTIONS -->
                                            <div class="form-group  col-md-12">

                                                <label for="name">Prefix</label>
                                                <input required type="text" class="form-control" name="prefix"
                                                    placeholder="Prefix" value="">
                                            </div>
                                          @if(Auth::user()->role_id == 1)   <div class="">
                                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                                            </div> @endif
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
