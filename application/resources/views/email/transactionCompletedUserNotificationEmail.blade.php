<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Completed</title>
</head>
<body>
    @php
    $dt = new DateTime("now", new DateTimeZone('Asia/Kolkata'));
    @endphp
    
	<img src="{{ asset('assets/frontend/images/zookpe-logo.png') }}" height="141px" width="141px" alt="logo" />
    <p>
        Your payment on <b>{{  ucfirst($user->name) }}</b> for amount  <b>{{$user->sourceAmount}} {{$user->currentCurrency()->symbol}}</b> was successful at <b>{{  $dt->format("Y-m-d H:i:s") }}.</b>
    </p>
    <p>Your Unique Transaction Reference Number is <b>{{ $user->token }}</b> and the Payee Name is <b>{{ $user->ag_payer_name }}</b> and the Bank Reference Number is <b>{{ $user->ag_bank_reference_no }}</b>.</p>
		<p>For any issues, disputes or complaints, please mail us at <b>grievances@zookpe.com</b> or raise a ticket from your merchant dashboard. Our team will assist you.
    </p>
</body>
</html>
