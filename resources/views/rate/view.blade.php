<!doctype html>
   <html>
         <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <title>Exchange Rates</title>
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

table {
    width: 100%;
    background: #fff;
    border: 1px solid #dedede;
}

table thead tr th {
    padding: 20px;
    border: 1px solid #dedede;
    color: #000;
}

table.table-striped tbody tr:nth-of-type(odd) {
    background: #f9f9f9;
}

table.result-point tr td.number {
    width: 100px;
    position: relative;
} 

.text-left {
    text-align: left!important;
}

table tr td {
    padding: 10px 20px;
    border: 1px solid #dedede;
}
table.result-point tr td .fa.fa-caret-up {
    color: green;
}

table.result-point tr td .fa {
    font-size: 20px;
    position: absolute;
    right: 20px;
}

table tr td {
    padding: 10px 40px;
    border: 1px solid #dedede;
}

table tr td img {
    max-width: 32px;
    float: left;
    margin-right: 11px;
    margin-top: 1px;
    border: 1px solid #dedede;
}

</style>
</head>
<body>
    <div class="col-lg-4">
<section class="content-info">
   <div class="container">
      <div class="row">
            <table class="table-striped table-responsive">
               <thead class="point-table-head">
                <tr>
                    <th></th>
                    <th style="font-size:20px;font-weight:bold;line-height:1.2">{{__('messages.rate_page.buying_rate')}}</th>
                    <th style="font-size:20px;font-weight:bold;line-height:1.2">{{__('messages.rate_page.selling_rate')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rates as $key=>$rate)
                <tr>
                    <td class="text-left">
                        <img src="{{$rate->country_flag}}" alt="Uganda"><span>{{$rate->currency_code}}</span>
                     </td>
                    <td style="font-size:22px;font-weight:bold;line-height:1.2">{{$rate->buying_rate}}</td>
                    <td style="font-size:22px;font-weight:bold;line-height:1.2">{{$rate->selling_rate}}</td>
                </tr>
                @endforeach
            </tbody>
            </table>
         </div>
   </div>
   
</section>
</div>
<script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js'></script>
<script type='text/javascript' src='#'></script>
<script type='text/javascript' src='#'></script>
<script type='text/javascript' src='#'></script>
<script type='text/javascript'>var myLink = document.querySelector('a[href="#"]');
myLink.addEventListener('click', function(e) {
    e.preventDefault();
});
</script>

</body>
</html>