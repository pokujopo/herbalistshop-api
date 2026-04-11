<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Document</title>
    <link href="customer/css/style.css" rel="stylesheet">
</head>
<body>
    <center>
    <form action="http://127.0.0.1:8000/test_post" method="post" >
        @csrf
        <label for="">username</label>
        <input type="text" name="name">

        <label for="">email</label>
        <input type="email" name="email" id="">

        <label for="">password</label>
        <input type="password" name="password">

        <button type="submit">post</button>
    </form>
    </center>


    <div>
        <h1>all users</h1>
        <br>
        <div>
            @foreach($users as $user)
            <h1> username: {{$user->username}}</h1>
            <h2> email: {{$user->email}}</h2>
            <h3>password: {{$user->password}}</h3>
            @endforeach
        </div>
    </div>
</body>
</html>