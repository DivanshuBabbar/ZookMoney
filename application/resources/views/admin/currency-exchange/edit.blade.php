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
                                        <form action="{{ route('admin.exchange-rate.update', $exchangerate->id) }}"
                                            method="POST">
                                            @csrf

                                            <div class="row">
                                                <div class="form-group  col-md-12">

                                                    <label for="name">First Currency</label>
                                                    <select class="form-control select2" name="first_currency_id">
                                                        @foreach ($currency as $currencies)
                                                            <option value="{{ $currencies->id }}"
                                                                {{ $exchangerate->first_currency_id == $currencies->id ? 'selected' : '' }}>
                                                                {{ $currencies->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group  col-md-12">

                                                    <label for="name">Second Currency</label>
                                                    <select class="form-control select2" name="second_currency_id">
                                                        @foreach ($currency as $currencies)
                                                            <option value="{{ $currencies->id }}" <?php echo isset($exchangerate->second_currency_id) && $exchangerate->second_currency_id == $currencies->id ? 'selected' : ''; ?>>
                                                                {{ $currencies->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <!-- GET THE DISPLAY OPTIONS -->
                                                <div class="form-group  col-md-12">
                                                    <label for="name">Exchanges To Second Currency Value</label>
                                                    <input type="text" class="form-control"
                                                        name="exchanges_to_second_currency_value"
                                                        placeholder="Exchanges To Second Currency Value"
                                                        value="{{ $exchangerate->exchanges_to_second_currency_value }}">
                                                </div>
                                            </div>
                                         @if(Auth::user()->role_id == 1)   <div class="">
                                                <button type="submit" class="btn btn-primary mt-1">{{ __('Submit') }}</button>
                                            </div> @endif
                                        </form>
                                    </div>
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
