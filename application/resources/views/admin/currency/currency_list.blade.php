@extends('admin.layouts.master')
@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Currencies</h1>
            </div>
            <div class="col-sm-6 text-right">
              <!--  <ol class="breadcrumb float-sm-right">

               </ol> -->
               <a href="{{route('admin.add.currency')}}" class="btn btn-primary btn-sm">Add Currency</a>
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
                  <!-- <div class="card-header"> -->
                     <h3 class="card-title mx-4 mt-3"></h3>
                  <!-- </div> -->
                  
                  <div class="card-body">

                     <div class="row">
                        <div class="col-lg-12 col-xs-10">
                           
                           <div class="table-responsive">
                              <table class="table align-items-center">
                                 <thead>
                                    <tr>
                                       <th scope="col">Id</th>
                                       <th scope="col">Name</th>
                                       <th scope="col">Symbol</th>
                                       <th scope="col">Code</th>
                                       <th scope="col">Is Crypto</th>
                                       <th scope="col">Thumb</th>
                                       <th scope="col">Created At</th>
                                       <th scope="col">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @if(!empty($currency))
                                    @foreach($currency as$key =>$row)
                                    <tr>
                                       <td>{{$row->id}}</td>
                                       <td>{{$row->name}}</td>
                                       <td>{{$row->symbol}}</td>
                                       <td>{{$row->code}}</td>
                                       <td>{{$row->is_cripto}}</td>                                    
                                       <td>
                                          <img src="{{$row->thumb}}" height="50px" width="50px">
                                       </td>
                                       <td>{{$row->created_at->format('d M y')}}</td>
                                       <td>
                                          <a href="{{route('admin.edit.currency',['id'=>$row->id])}}" class="btn btn-primary btn-sm">
                                          <span class="hidden-xs hidden-sm">Edit</span>
                                          </a>
                                         @if(Auth::user()->role_id == 1)  <a href="javascript:void(0)" data-id = "{{$row->id}}"class="btn btn-danger btn-sm delete"> Delete </a> @endif
                                          @if(Auth::user()->role_id == 4) <a href="javascript:void(0)" class="btn btn-danger btn-sm"> You can't Delete </a> @endif
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
                {{ $currency->links() }}
            </div>
         </div>
      </div>
</div>
</div>
</section>
</div>
<script type="text/javascript">
   document.addEventListener('DOMContentLoaded',function(){
      $('body').on("click",".delete",function (event){
        event.preventDefault();
        var href = $(this).attr('data-id');
        swal.fire({
            title: "Are you sure?",
            text: "You will not be able to recover this record!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }).then((result) => {
            if(result.value){
                window.location.href = "{{route('admin.delete.currency')}}"+'/'+href;
            }
        });
    });
 });
</script>
@endsection