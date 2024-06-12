@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ $page_title }} </h1>
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
                                                        <th> User </th>
                                                        <th> Full Name </th>
                                                        <th> ID Front Side </th>
                                                        <th> ID Back Side </th>
                                                        <th> Proof of Address </th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty('$user_kyc'))
                                                        @foreach ($user_kyc as $key => $row)
                                                            <tr>
                                                               <td>{{$row->name}}</td> 
                                                                <td>{{$row->first_name}}   {{$row->last_name}}</td>
                                                                <td>
                                                                    @if($row->govt_id_card_front)
                                                                     <img src="{{url('uploads/',$row->govt_id_card_front)}}" height="40px" width="40px">
                                                                     <a href="{{$row->govt_id_card_front}}" download=""><i class="fa fa-download" aria-hidden="true"></i></a>
                                                                     @else
                                                                        <span class="text-danger">Not Uploaded yet</span>
                                                                     @endif 
                                                                </td>
                                                                <td>
                                                                   @if($row->govt_id_card_back)
                                                                    <img src="{{url('uploads/',$row->govt_id_card_back)}}">
                                                                    <a href="{{$row->govt_id_card_back}}" download=""><i class="fa fa-download" aria-hidden="true"></i></a>
                                                                    @else
                                                                    <span class="text-danger">Not Uploaded yet</span>
                                                                    @endif 
                                                                </td>
                                                                <td>
                                                                    @if($row->selfi)
                                                                    <img src="{{url('uploads/',$row->selfi)}}" height="40px" width="40px">
                                                                    @else
                                                                    <span class="text-danger">Not Added yet</span>
                                                                    @endif
                                                                    
                                                                </td>
                                                                <td>
                                                                    @if($row->kyc_approved == 1)
                                                                    {{__('Approved')}}
                                                                    @endif
                                                                    @if($row->kyc_approved == 0)
                                                                    {{__('Pending for Approval')}}
                                                                    @endif
                                                                    @if($row->kyc_approved == 2)
                                                                    {{__('Rejected')}}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <select class="form-control status" name="account_status" style="width: px;">
                                                                        <option value="1" data-id="{{$row->id}}"@isset($row->kyc_approved){{$row->kyc_approved == 1 ? 'selected':''}}@endisset>Approved</option>
                                                                        <option value="0" data-id="{{$row->id}}" @isset($row->kyc_approved){{$row->kyc_approved == 0 ? 'selected':''}}@endisset>Pending for Approval</option>
                                                                        <option value="2" data-id="{{$row->id}}" @isset($row->kyc_approved){{$row->kyc_approved == 2 ? 'selected':''}}@endisset>Rejected</option>
                                                                    </select>
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
                        {{ $user_kyc->links() }}
                    </div>
                </div>
            </div>
    </div>
    </div>
</section>
</div>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded',function(){
        $('body').on('change','.status',function(){
            let val = $('body').find(this).find(':selected').val();
            let id = $('body').find(this).find(':selected').attr('data-id');
            if(id && val)
            {
                window.location.href="{{route('admin.change.kyc.status')}}"+'/'+id+'/'+val;
            }
        });
    })
</script>

@endsection
