@extends('notus.layouts.app')
@section('content')
<div class="flex flex-wrap">
<div class="w-full mb-12 px-4">
  <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
    <div class="rounded-t bg-white mb-0 px-6 py-6">
      <div class="text-center flex justify-between">
        <h6 class="text-blueGray-700 text-xl font-bold">
          All Users
        </h6>
        <button class="bg-pink-500 text-white active:bg-pink-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150" type="button">
          <i class="fas fa-plus mr-2 text-lg text-white"></i>
          add user
        </button>
      </div>
    </div>
    <div class="flex-auto  py-10 pt-0">
       <!-- Projects table -->
        <table class="items-center w-full bg-transparent border-collapse">
          <thead>
            <tr>
              <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                Avatar
              </th>
              <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                email
              </th>
              <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                email verified
              </th>
              <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                username
              </th>
             
              <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"></th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
            <tr>
              <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center">
                 <img src="{{$user->avatar}}" alt="..." class="w-10 h-10 rounded-full border-2 border-blueGray-50 shadow">
                
              </th>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                {{$user->email}}
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                @if($user->verified == 1)
                   <i class="fas fa-circle text-emerald-500 mr-2"></i> Verified
                @elseif($user->verified == 0)
                  <i class="fas fa-circle text-orange-500 mr-2"></i> Pending 
                @endif
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                <div class="flex">
                 <span class="ml-3 font-bold text-blueGray-600">
                  {{$user->name}}
                </span>
                </div>
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-right">
                <a href="#pablo" class="text-blueGray-500 block py-1 px-3" onclick="openDropdown(event,'table-light-1-dropdown')">
                  <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="hidden bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48" id="table-light-1-dropdown">
                  <a href="#pablo" class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700"> <i class="fas fa-edit mr-2 text-lg text-pink-500"></i>Edit</a><a href="#pablo" class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700"> <i class="fas fa-trash mr-2 text-lg text-pink-500"></i>Delete</a>
                  <div class="h-0 my-2 border border-solid border-blueGray-100"></div>
                  <a href="#pablo" class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700"> <i class="fas fa-user mr-2 text-lg text-pink-500"></i> Impersonate</a>
                </div>
              </td>
            </tr>
            @empty

            @endforelse

          </tbody>
        </table>
        @if($users->LastPage() != 1)
              
              {{$users->links()}}
            @endif
    </div>
  </div>
</div>




  
</div>

@endsection