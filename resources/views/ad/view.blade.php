<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Ads</title>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css' rel='stylesheet'>
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <style>
        body, html, .content-info {
            height: 100%;
            margin: 0;
        }

        .content-info {
            display: flex;
            justify-content: center;
            align-items: center;
            background-size: cover!important;
            background-position: center!important;
            position: relative;
            overflow: hidden;
        }

        .mySlides {
            width: 100%;
            height: 100%;
            /* object-fit: cover; */
        }
    </style>
</head>
<body>
<section class="content-info">
    @foreach($ads as $ad)
    <img class="mySlides" src="{{ $ad->ad_img_url }}" alt="Ad Image">
    @endforeach
</section>
<script type='text/javascript'>
    var myIndex = 0;
    carousel();

    function carousel() {
        var i;
        var x = document.getElementsByClassName("mySlides");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        myIndex++;
        if (myIndex > x.length) {myIndex = 1}
        x[myIndex-1].style.display = "block";
        setTimeout(carousel, 2000);
    }
</script>
</body>
</html>
