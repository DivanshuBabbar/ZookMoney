

<div class="relative md:ml-64 bg-blueGray-50">
	
	@include('notus.partials.topnav')
	
	@include('notus.partials.total')

	<div class="px-4 md:px-10 mx-auto w-full -m-24">
		@yield('content')
		
		@include('notus.layouts.footer')

	</div>
</div>
