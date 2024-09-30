<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Marquee Text</title>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css' rel='stylesheet'>
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: auto;
            overflow: hidden;
        }
        .marquee-container {
            background-color: darkblue;
            color: white;
            font-weight: bold;
            padding: 5px 0;
            text-align: center;
            height: 40px; /* Match this with the footer height */
        }
        marquee {
            display: block;
            width: 100%;
            font-size: 1.8rem;
            line-height: 40px; /* Align text vertically */
        }
    </style>
</head>
<body>
    <div class="marquee-container">
        <marquee>{{$settings->display_notification ? $settings->display_notification : 'Hello' }}</marquee>
    </div>
</body>
</html>
