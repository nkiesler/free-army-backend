<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>

<body>
<h2>Welcome to the Free Army, {{$user['name']}}</h2>
<br/>
	Click on the below link to verify your account
<br/>
<a href="{{ $verify_link }}">Verify Email</a>
</body>

</html>

