
{{-- manual initialization of any alert --}}
@if(session()->has('flash_message'))

<div class="alert alert-{{session()->get('flash_message_level')}} alert-dismissible fade show mt-2">
    
    @if(session()->get('flash_message_level') == 'danger')
    <a href="javascript:void(0);" class="alert-link"><strong>Error! </strong></a>  
    @elseif(session()->get('flash_message_level') == 'info')
    <a href="javascript:void(0);" class="alert-link"><strong>Info!</strong></a> 
    @elseif(session()->get('flash_message_level') == 'success')
    <a href="javascript:void(0);" class="alert-link"><strong>Success!</strong></a> 
    @elseif(session()->get('flash_message_level') == 'warning')
    <a href="javascript:void(0);" class="alert-link"><strong>Warning!</strong></a> 
    @endif
    <span> &nbsp;&nbsp;&nbsp;{!! session()->get('flash_message') !!} </span>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

</div>
{{--
<div class="card bg-{{session()->get('flash_message_level')}}">
    <div class="header">
    	<h2><i class="zmdi zmdi-alert-circle-o text-white"></i> <strong class="text-white">{{__('Info')}}</strong></h2>
        <ul class="header-dropdown">  
            <li class="remove">
                <a role="button" class="boxs-close "><i class="zmdi zmdi-close text-white" ></i></a>
            </li>
        </ul>
    </div>
    <div class="body block-header">
        <div class="row">
            <div class="col">
                <p class="text-white"> {!! session()->get('flash_message') !!} </p>
            </div>   
        </div>
    </div>
</div>
--}}

@endif

{{-- laravel validation error bag initialization of any validation error --}}
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible fade show mt-2">
            <a href="javascript:void(0);" class="alert-link"><strong>Error! </strong></a>
            <span> {{ $error }} </span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endforeach
@endif