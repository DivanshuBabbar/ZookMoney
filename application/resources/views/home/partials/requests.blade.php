@if($merchantWebsite)

	<div class="card">
		<div class="header">
	        <h2><strong>#Merchant :</strong>&nbsp;&nbsp;{{ Auth::user()->name}}</h2>
	       
	        
	    </div>
	   <div class="body">
              <div class="table-responsive">
                  <table class="table m-b-0">
                      <thead>
                          <tr>
                              <th>{{__('Merchant Key')}}</th>
                              <th>{{__('Website')}}</th>
                              <th>{{__('Status')}}</th>
                             
                          </tr>
                      </thead>
                      @forelse($merchantWebsite as $website)
                        <tr>
                         
                          <td >{{$website->merchant_key}}</td>
                          <td>{{$website->site_url}}</td>
                          <td>{{$website->status}}</td>
                        </tr>
                    @empty
               
                    @endforelse
                  </table>
              </div>
          </div>
	</div>

@endif
<!-- @if($myRequests)
@foreach($myRequests as $request)
	<div class="card">
	    <div class="header">
	        <h2><strong># {{$request->id}} :: Pending</strong> Money Request</h2>
	        <ul class="header-dropdown">
                <li class="remove">
                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                </li>
            </ul>
	        
	    </div>
	    <div class="body block-header">
	        <div class="row">
	            <div class="col">
	                <h2>To {{$request->from->name}} </h2>
	                <ul class="breadcrumb p-l-0 p-b-0 ">
	                    <li class="breadcrumb-item ">
	                        <span class="text-primary">{{$request->currency_symbol}}</span>
	                    </li>
	                    <li> <h2> {{$request->net}} </h2> </li>
	                </ul>
	            </div>            
	            {{-- 
	            <div class="col text-right">
	               <a href="https://devv2.bitmetical.com/deposit" class="btn btn-warning btn-round  float-right  m-l-10">Cancel</a>
	            </div>
	            --}}
	        </div>
	    </div>
	</div>
@endforeach
@endif -->
