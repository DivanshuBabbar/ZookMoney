@extends('layouts.app')
@section('content')
<div class="row">
  @include('partials.sidebar')
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col"  id="#sendMoney">
        @include('flash')
        <div class="card">
          <div class="header">
            <h2><strong>{{__('Loan List')}}</strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-2">
                      <a href="{{route('loan',app()->getLocale())}}" class="btn btn-primary btn-xs">Apply for loan</a>
                  </div>
                  <div class="col-sm-2">
                      <a href="{{route('loan.pending',app()->getLocale())}}" class="btn btn-primary btn-xs">Pending loan</a>
                  </div>
                  <div class="col-sm-2">
                      <a href="{{route('loan.approved',app()->getLocale())}}" class="btn btn-primary btn-xs">Approved loan</a>
                  </div>
                  <div class="col-sm-2">
                      <a href="{{route('loan.rejected',app()->getLocale())}}" class="btn btn-primary btn-xs">Rejected loan</a>
                  </div>
                  <div class="col-sm-2">
                      <a href="{{route('loan.accepted',app()->getLocale())}}" class="btn btn-primary btn-xs">Accepted loan</a>
                  </div>
                  <div class="col-sm-2">
                      <a href="{{route('loan.declined',app()->getLocale())}}" class="btn btn-primary btn-xs">Declined loan</a>
                  </div>
                </div>
                <h5 class="card-header">@isset($title){{$title}}@endisset</h5>
                <div class="table-responsive">
                  <table class="table mb-0">
                    <thead>
                      <tr>
                        <th>@lang('Sr.No')</th>
                        <th>@lang('Loan Amount')</th>
                        <th>@lang('Loan Tenuer')</th>
                        <th>@lang('Payback')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Action')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($loans as $key=>$data)
                      <tr>
                          <td>{{$key+1}}</td>
                          <td>
                              @isset($data->loan_amount){{$data->loan_amount}}@endisset
                          </td>
                          <td>
                              @isset($data->loan_tenure){{$data->loan_tenure}}@endisset
                              @isset($data->tenure_type){{$data->tenure_type}}@endisset
                          </td>
                          <td> 
                              @isset($data->payback){{ucfirst($data->payback)}}@endisset
                          </td>
                          <td> 
                              @isset($data->created_at){{date('M d,Y',strtotime($data->created_at))}}@endisset
                          </td>
                          <td>
                             <a href="{{route('loan.detail',[app()->getLocale(),$data->id])}}" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i>Detail</a>
                             @if(isset($data->status) && $data->status == 3)
                                <a href="{{route('loan.user_payment_detail',[app()->getLocale(),$data->id])}}" class="btn btn-primary btn-xs">
                                    Payment Detail
                                </a>
                             @endif
                          </td>
                        </tr>
                        @empty
                          <tr>
                              <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                          </tr>
                        @endforelse
                    </tbody>
                  </table>
                </div>
                @if($loans->LastPage() != 1)
                  <div class="card-footer py-4">
                      {{$loans->links()}}
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script>
$( "#currency" )
  .change(function () {
    $( "#currency option:selected").each(function() {
      window.location.replace("{{url('/')}}/{{app()->getLocale()}}/wallet/"+$(this).val());
  });
})
</script>
@endsection
@section('footer')
  @include('partials.footer')
@endsection
