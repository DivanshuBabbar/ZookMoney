@if($transaction->activity_title == 'Money Sent')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('To')}} {{$transaction->entity_name}}</span>
@elseif($transaction->activity_title == 'Auto Deposit')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('To')}} {{$transaction->entity_name}}</span>
@elseif($transaction->activity_title == 'Manual Deposit')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('To')}} {{$transaction->entity_name}}</span>
@elseif($transaction->activity_title == 'Manual Withdraw')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('To')}} {{$transaction->entity_name}}</span>
@elseif($transaction->activity_title == 'Transfer')
	{{$transaction->activity_title}} <br><span class="text-primary">{{$transaction->entity_name}}</span>
@elseif($transaction->activity_title == 'Loan')
	{{$transaction->activity_title}} <br><span class="text-primary">{{$transaction->entity_name}}</span>
@elseif($transaction->activity_title == 'Bank transfer')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('To')}} {{$transaction->entity_name}}</span>
@elseif($transaction->activity_title == 'Withdrawal')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('To')}} {{$transaction->entity_name}}</span>
@elseif($transaction->activity_title == 'Money Received')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('From')}} {{$transaction->entity_name}}</span>
	
@elseif($transaction->activity_title == 'Payment Received')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('From')}} {{$transaction->entity_name}}</span>
@elseif($transaction->activity_title == 'Payment Received')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('From')}} {{$transaction->entity_name}}</span>
@elseif($transaction->activity_title == 'Voucher Load')
	{{$transaction->entity_name}} <br><span class="text-primary"> {{__('Voucher Load')}}</span>
@elseif($transaction->activity_title == 'Voucher Generation')
	{{$transaction->entity_name}} <br><span class="text-primary"> {{__('Voucher Generation')}}</span>
@elseif($transaction->activity_title == 'Added Voucher to system')
	{{$transaction->entity_name}} <br><span class="text-primary"> {{__('Added Voucher to system')}}</span>
@elseif($transaction->activity_title == 'Purchase')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('From')}} {{$transaction->entity_name}}</span>
@elseif($transaction->activity_title == 'Deposit')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('From')}} {{$transaction->entity_name}}</span>
@elseif($transaction->activity_title == 'Sale')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('In')}} {{$transaction->entity_name}} </span>
@elseif($transaction->activity_title == 'Investment')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('On')}} {{$transaction->entity_name}} </span>
@elseif($transaction->activity_title == 'Investment profits')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('On')}} {{$transaction->entity_name}} </span>
@elseif($transaction->activity_title == 'Sell Trade')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('On')}} {{$transaction->entity_name}} </span>
@elseif($transaction->activity_title == 'Buy Trade')
{{$transaction->activity_title}} <br><span class="text-primary">{{__('On')}} {{$transaction->entity_name}} </span>
@elseif($transaction->activity_title == 'Payment Link')
	{{$transaction->activity_title}} <br><span class="text-primary">{{__('From')}} {{$transaction->entity_name}} </span>
@elseif($transaction->activity_title == 'Currency Exchange')
	{{$transaction->activity_title}} <br><span class="text-primary"> @if($transaction->money_flow == '+') {{__('Exchanged To')}} 	
@else {{__('Exchanged From')}} @endif {{$transaction->entity_name}}</span>
@endif
