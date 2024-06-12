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
                                        <form action="{{route('admin.user.update',$user->id)}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group  col-md-6">
                                                    <label for="name">First Name</label>
                                                    <input required type="text" class="form-control" name="first_name"
                                                    placeholder="Name" value="@isset($user->first_name){{$user->first_name}}@endisset">
                                                </div>
                                                 
                                                 
                                                 <div class="form-group  col-md-6">
                                                    <label for="name">Last Name</label>
                                                    <input required type="text" class="form-control" name="last_name"
                                                    placeholder="Name" value="@isset($user->last_name){{$user->last_name}}@endisset">
                                                </div>
                                                 </div>
                                                 
                                                  <div class="row">
                                                  <div class="form-group  col-md-6">
                                                    <label for="name">Email</label>
                                                    <input required type="text" class="form-control" name="email"
                                                    placeholder="Email" value="@isset($user->email){{$user->email}}@endisset">
                                                </div>
                                            
                                             <div class="form-group  col-md-6">
                                                    <label for="name">Email Verified</label>
                                                    <input required type="text" class="form-control" name="verified"
                                                    placeholder="Name" value="@isset($user->verified){{$user->verified}}@endisset">
                                                </div>
                                            </div>
                                                 <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label>Is Merchant ?</label>
                                                        <select class="form-control" name="is_merchant" required>
                                                                <option value="1"@isset($user->is_merchant){{$user->is_merchant ==1 ? 'selected':''}}@endisset>Yes</option>
                                                                <option value="0"@isset($user->is_merchant){{$user->is_merchant == 0 ? 'selected':''}}@endisset>No</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group  col-md-4">
                                                        <label for="name">User Role</label>
                                                        <input required type="text" class="form-control" name="role_id"
                                                        placeholder="Name" value="@isset($user->role_id){{$user->role_id}}@endisset">
                                                    </div>
                                                    <div class="form-group  col-md-4">
                                                        <label for="name">Block/Unblock</label>
                                                        <input required type="text" class="form-control" name="account_status"
                                                        placeholder="Name" value="@isset($user->account_status){{$user->account_status}}@endisset">
                                                    </div>
                                                 </div>
                                                   <div class="row">
                                                 <div class="form-group  col-md-4">
                                                    <label for="name">Currency ID</label>
                                                    <input required type="text" class="form-control" name="currency_id"
                                                    placeholder="Name" value="@isset($user->currency_id){{$user->currency_id}}@endisset">
                                                </div>
                                                 
                                                 <div class="form-group  col-md-4">
                                                    <label for="name">Balance </label>
                                                    <input required type="text" class="form-control" name="balance"
                                                    placeholder="Name" value="@isset($user->balance){{$user->balance}}@endisset">
                                                </div>
                                                 <div class="form-group  col-md-4">
                                                    <label for="name">Payout Balance </label>
                                                    <input required type="text" class="form-control" name="payout_balance"
                                                    placeholder="payout_balance" value="@isset($user->payout_balance){{$user->payout_balance}}@endisset">
                                                </div>
                                                 </div>
                                               
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="name">Phonenumber</label>
                                                    <input required type="text" class="form-control" name="phonenumber"
                                                    placeholder="Phonenumber" value="@isset($user->phonenumber){{$user->phonenumber}}@endisset">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="name">Card customer_id</label>
                                                    <input required type="text" class="form-control" name="customer_id"
                                                    placeholder="customer_id" value="@isset($user->customer_id){{$user->customer_id}}@endisset">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Avatar</label>
                                                    <input type="file" name="avatar" class="form-control">
                                                </div>
                                                
                                            </div>
                                            @if(Auth::user()->role_id == 1) 
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                                </div>
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
