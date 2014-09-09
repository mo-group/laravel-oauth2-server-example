<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel PHP Framework</title>
    <style>
        @import url(//fonts.googleapis.com/css?family=Lato:700);

        body {
            margin:0;
            font-family:'Lato', sans-serif;
            text-align:center;
            color: #999;
        }

        .welcome {
            width: 300px;
            height: 200px;
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -150px;
            margin-top: -100px;
        }

        a, a:visited {
            text-decoration:none;
        }

        h1 {
            font-size: 32px;
            margin: 16px 0 0 0;
        }
    </style>
</head>
<body>
    <div class="welcome">
        <form action="/signup" method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <label for="">Email:</label>
            <input type="email" name="email">
            <br>
            <label for="">First Name:</label>
            <input type="text" name="first_name">
            <br>
            <label for="">Last Name:</label>
            <input type="text" name="last_name">
            <br>
            <label for="">Password:</label>
            <input type="password" name="password">
            <br>
            <label for="">Password Confirmation:</label>
            <input type="password" name="password_confirmation">
            <br>
            <input type="submit" value="submit">
        </form>
    </div>
</body>
</html>