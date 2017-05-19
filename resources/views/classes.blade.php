<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        #results {
            margin-top: 2vw;
            color: #636b6f;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
            height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.3s;
        }

        #results.expanded {
            height: 100px;
            opacity: 1;
        }

        input[type="submit"] {
            width: 150px;
            height: 50px;
            background-color: #EEEEEE;
            color: #333333;
            font-family: 'Raleway', sans-serif;
            font-weight: 800;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
            border: 1px solid #aaa;
            transition: all 0.3s;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #CCC;
            border: 15px solid #AAA;
        }
    </style>

    <script>
        $(document).ready(function(){
            $("#submit-button").click(function(e){
                e.preventDefault();

                var resultBlock = $("#results");

                $(resultBlock).removeClass("expanded");
                setTimeout(function(){

                    var formData = new FormData(document.getElementById("compute_form"));
                    var r = new XMLHttpRequest();

                    r.open("POST", '/classificate');
                    r.setRequestHeader("ContentType", 'multipart/form-data');
                    r.onreadystatechange = function () {
                        if (r.readyState == 4) {
                            if (r.status != 200) {
                                alert(r.responseText);
                            } else {
                                var result = JSON.parse(r.responseText);
                                var resultHtml = "Suitable classes: <br /><br />";

                                for(var i = 0; i < result.length; i++) {
                                    resultHtml += result[i].name + "<br />";
                                    if (i != result.length-1)
                                        resultHtml += "&<br />";
                                }

                                $(resultBlock).html(resultHtml);
                                $(resultBlock).addClass("expanded");
                            }
                        }
                    };
                    r.send(formData);
                }, 300);
            });
        });
    </script>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @if (Auth::check())
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ url('/login') }}">Login</a>
                <a href="{{ url('/register') }}">Register</a>
            @endif
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            Manage classes
        </div>

        <div class="links">
            <a href="/">Home</a>
            <a href="/classes">Manage classes</a>
            <a href="/classes">Manage cars</a>
            <a href="/classes">How it works</a>
            <a href="/classes">About</a>
        </div>

        <br />
        <br />
        <br />
        <br />
        <br />
        <br />

        <form id="add_class_form">
            {{csrf_field()}}

            <div class="links">
                <a href="#">Type the car price</a>
                <input type="number" name="price">
            </div>
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <input type="submit" value="Add class!" id="submit-button">
        </form>


        <div class="links" id="results">
            Result
        </div>
    </div>
</div>
</body>
</html>
