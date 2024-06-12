<nav class="md:left-0 md:block md:fixed md:top-0 md:bottom-0 md:overflow-y-auto md:flex-row md:flex-nowrap md:overflow-hidden shadow-xl bg-white flex flex-wrap items-center justify-between relative md:w-64 z-10 py-4 px-6">
  <div class="md:flex-col md:items-stretch md:min-h-full md:flex-nowrap px-0 flex flex-wrap items-center justify-between w-full mx-auto">

    <button class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent" type="button">
      <i class="fas fa-bars"></i>
    </button> 

   
    <div class="md:flex md:flex-col md:items-stretch md:opacity-100 md:relative md:mt-4 md:shadow-none shadow absolute top-0 left-0 right-0 z-40 overflow-y-auto overflow-x-hidden h-auto items-center flex-1 rounded hidden" id="example-collapse-sidebar">
  
      <h6 class="md:min-w-full text-blueGray-500 text-xs uppercase font-bold block pt-1 pb-4 no-underline">Minimal Dashboard</h6>
      <ul class="md:flex-col md:min-w-full flex flex-col list-none">
        <li class="items-center">
          <a href="{{url('/')}}/administrator/dashboard" class="text-xs uppercase py-3  {{ (Route::is('notus_dashboard') ? 'text-pink-500' : ' text-blueGray-700') }}  font-bold block  hover:text-pink-600">
          <i class="fas fa-tv mr-2 text-sm opacity-75"></i> Dashboard</a>
        </li>
        <li class="items-center">
          <a href="{{url('/')}}/administrator/users" class="text-xs uppercase py-3 font-bold block  {{ (Route::is('notus_users') ? 'text-pink-500' : ' text-blueGray-700') }} hover:text-blueGray-500"><i class="fas fa-tools mr-2 text-sm text-blueGray-300"></i> Users</a>
        </li>
        <li class="items-center">
          <a href="{{url('/')}}/administrator/currencies" class="text-xs uppercase py-3 font-bold block {{(Route::is('notus_currencies') ? 'text-pink-500' : ' text-blueGray-700') }} hover:text-blueGray-500"><i class="fas fa-table mr-2 text-sm text-blueGray-300"></i> Currencies</a>
        </li>
        <li class="items-center">
          <a href="{{url('/')}}administrator/paymentlinks" class="text-xs uppercase py-3 font-bold block text-blueGray-700 hover:text-blueGray-500"><i class="fas fa-map-marked mr-2 text-sm text-blueGray-300"></i> Payment Links</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
