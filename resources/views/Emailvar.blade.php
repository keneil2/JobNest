<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email verification </title>
</head>
<body>
    Email send !!
    <form action="{{route("verification.send")}}" method="POST">
    @csrf
     resend Email?
<button type="submit">send code</button>
</form>
</body>
</html>