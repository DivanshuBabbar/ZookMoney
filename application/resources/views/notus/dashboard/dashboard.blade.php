@extends('notus.layouts.app')
@section('content')
<div class="flex flex-wrap">
			
	@livewire('admin.statistic.transactions.transactions')
	
	@livewire('admin.statistic.vouchers.orders')
	
</div>

@endsection