<!doctype html>
   <html>
         <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <title>Marquee Text</title>
            <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet'>
            <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css' rel='stylesheet'>
            <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
            <style>::-webkit-scrollbar {
               width: 8px;
            }
            /* Track */
            ::-webkit-scrollbar-track {
               background: #f1f1f1; 
            }
            /* Handle */
            ::-webkit-scrollbar-thumb {
               background: #888; 
            }
            /* Handle on hover */
            ::-webkit-scrollbar-thumb:hover {
               background: #555; 
            } .content-info {
                padding: 40px 0;
                background-size: cover!important;
                background-position: top center!important;
                background-repeat: no-repeat!important;
                position: relative;
                padding-bottom:100px;
}
</style>
</head>
<body>
    <div class="row" style="margin-bottom:0; margin-top: 15px;">
        <marquee><span style="font-size:{{$settings->display_font_size}}px;color:{{$settings->display_font_color}}">{{$settings->display_notification ? $settings->display_notification : 'Hello' }}<span></span></span></marquee>
    </div>
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