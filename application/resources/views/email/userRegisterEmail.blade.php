<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New User Sign Up</title>
</head>
<body>
	<img src="{{ asset('assets/frontend/images/zookpe-logo.png') }}" height="141px" width="141px" alt="logo" />
    <p>
       New user register with username : <b>{{  ucfirst($user->name) }}</b> and email : <b>{{$user->email}}</b>
    </p>
  
		<p>For any issues, disputes or complaints, please mail us at <b>grievances@zookpe.com</b> or raise a ticket from your merchant dashboard. Our team will assist you.
    </p>
</body>
</html>
