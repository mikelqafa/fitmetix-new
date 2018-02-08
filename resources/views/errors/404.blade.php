<!DOCTYPE html>
<html>
    <head>
        <title>404 Page not found</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #212121;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }

            .btn {
                text-decoration: none;
                padding: 8px 16px;
                background-color: #1E7C82;
                color: #fff;
                outline: none;
                border-radius: 2px;
                border: none;
                cursor: pointer;
                font-family: 'serif';
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">This link appears to be broken</div>
                <a class="btn" href="{{ url('/') }}">Go Home</a>
            </div>
        </div>
    </body>
</html>
