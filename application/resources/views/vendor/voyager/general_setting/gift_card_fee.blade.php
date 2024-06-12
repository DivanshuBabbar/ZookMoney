@extends('voyager::master')
@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-person"></i> General Settings
        </h1>
        @include('voyager::multilingual.language-selector')
    </div>
    <style>
        .mail-content{
            word-wrap: break-word;
        }
    </style>
@stop
@section('content')
<div class="page-content browse container-fluid">
    @include('voyager::alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <form action="{{route('admin.saveGiftCardFee')}}" method="POST">
                        @csrf
                        <input type="hidden" value="@isset($general_setting->id){{$general_setting->id}}@endisset" name="id">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Gift Card Fee</label>
                                    <input type="text" class="form-control" value="@isset($general_setting->gift_card_fee){{$general_setting->gift_card_fee}}@endisset" name="gift_card_fee">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Admin Email</label>
                                    <input type="text" class="form-control" value="@isset($general_setting->support_email){{$general_setting->support_email}}@endisset" name="support_email">
                                </div>
                            </div>
                            <div class="col-sm-4" style="margin-top: 18px">
                                <button class="btn btn-info" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                 </div>
            </div>
        </div>
    </div>
</div>
@endsection