@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            @if($user->kyc == 2 || $user->kyc == 1)
            <div class="card-header">
                @if($user->kyc == 2)
                <div class="float-right">
                    <a href="{{ route('admin.users.kyc.verify', ['id' => $user->id, 'kyc' => 1]) }}" class="btn btn-success">Approved</a>
                    <a href="javascript:void(0)" data-reject_id="{{$user->id}}" class="btn btn-warning kyc_reject_model">Rejected</a>
                    <!-- <a href="{{ route('admin.users.kyc.verify', ['id' => $user->id, 'kyc' => 0]) }}" class="btn btn-warning">Rejected</a> -->
                </div>
                @endif
            </div>
            <div class="card-body">
                <div class="row p-4">
                    <h3>ID Card</h3>
                    @if(!empty($user->kyc_id))
                        <iframe src="{{get_image($user->kyc_id)}}" style="width:100%;min-height:400px;"></iframe>
                    @else
                        <h3> - Not Uploaded</h3>
                    @endif
                </div>
                <div class="row p-4">
                    <h3>Address Proof</h3>
                    @if(!empty($user->kyc_address))
                        <iframe src="{{get_image($user->kyc_address)}}" style="width:100%;min-height:400px;"></iframe>
                    @else
                        <h3> - Not Uploaded</h3>
                    @endif
                </div>
                <div class="row p-4">
                    <h3>Self Proof</h3>
                    @if(!empty($user->kyc_self))
                        <iframe src="{{get_image($user->kyc_self)}}" style="width:100%;min-height:400px;"></iframe>
                    @else
                        <h3> - Not Uploaded</h3>
                    @endif
                </div>  
            </div>
            @else
                <div class="row p-4">
                    <h4>Not applied for KYC verification. </h4>
                </div>
            @endif
        </div>
    </div>
</div>
<div id="kycRejectModel" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rejection of kyc</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.users.kyc_rejection') }}" method="POST">
                @csrf
                <input type="hidden" name="id" class="form-control reject_id" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" name="reject_reason" class="form-control" required="">
                        </div>
                    </div>
                    <!-- <p>Are you sure to reject <span class="font-weight-bold method-name"></span> method?</p> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .user-image {
        width: 200px;
        height: 200px;
    }
</style>
@endpush
@push('script')
<script>
    $('.kyc_reject_model').on('click', function() {
        var modal = $('body').find('#kycRejectModel');
        var reject_id  = $(this).attr('data-reject_id');
        modal.find('.reject_id').val(reject_id);
        modal.modal('show');
    });
</script>
@endpush