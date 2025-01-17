@extends('layout.call_page')
@section('content')
<!-- BEGIN: Page Main-->
<div id="loader-wrapper">
    <div id="loader"></div>

    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>

</div>
<div id="main" class="no-print" style="padding: 15px 15px 0px;">

    <div class="wrapper" style=" min-height: 557px;" id="display-page">
        <section class="content-wrapper no-print">
            <div id="callarea" class="row" style="line-height:1.23;display:flex; flex-direction:row-reverse">
                <div class="col m3">
                    <div class="card-panel center-align p-0" style="margin-bottom:0;height:134vh" id="side-token-display">
                        <div style="border-bottom:1px solid #ddd;height:15%;display:flex;flex-direction:row;justify-content:center;align-items:center">
                            <div>
                                <span v-if="tokens[1]" class="bolder-color" style="font-size:45px;font-weight:bold;line-height:1.2">@{{tokens[1]?.token_letter}}-@{{tokens[1]?.token_number}}</span>
                                <span v-if="!tokens[1]" class="bolder-color" style="font-size:45px;font-weight:bold;line-height:1.2">{{__('messages.display.nil')}}</span><br>
                                <small v-if="tokens[1]" class="bolder-color" id="counter1" style="font-size:25px; font-weight:bold;">@{{tokens[1]?.counter.name}}</small>
                                <small v-if="!tokens[1]" class="bolder-color" id="counter1" style="font-size:25px; font-weight:bold;">{{__('messages.display.nil')}}</small><br>
                                <small v-if="tokens[1]?.call_status_id == {{CallStatuses::SERVED}}" style="font-size:20px; color:#009688; font-weight:bold;">{{__('messages.display.served')}}</small>
                                <small v-if="tokens[1]?.call_status_id == {{CallStatuses::NOSHOW}}" style="font-size:20px;font-weight:bold;color:red">{{__('messages.display.noshow')}}</small>
                                <small v-if="tokens[1] && tokens[1]?.call_status_id == null" style="font-size:20px; color:orange; font-weight:bold;">{{__('messages.display.serving')}}</small>
                                <small v-if="!tokens[1]" style="font-size:20px;">{{__('messages.display.nil')}}</small>
                            </div>
                        </div>
                        <div style="border-bottom:1px solid #ddd;height:15%;display:flex;flex-direction:row;justify-content:center;align-items:center">
                            <div>
                                <span v-if="tokens[2]" class="bolder-color" style="font-size:45px; font-weight:bold;line-height:1.2">@{{tokens[2]?.token_letter}}-@{{tokens[2]?.token_number}}</span>
                                <span v-if="!tokens[2]" class="bolder-color" style="font-size:45px; font-weight:bold;line-height:1.2">{{__('messages.display.nil')}}</span><br>
                                <small v-if="tokens[2]" class="bolder-color" id="counter2" style="font-size:25px;font-weight:bold;">@{{tokens[2]?.counter.name}}</small>
                                <small v-if="!tokens[2]" class="bolder-color" id="counter2" style="font-size:25px;font-weight:bold;">{{__('messages.display.nil')}}</small><br>
                                <small v-if="tokens[2]?.call_status_id == {{CallStatuses::SERVED}}" style="font-size:20px; color:#009688; font-weight:bold;">{{__('messages.display.served')}}</small>
                                <small v-if="tokens[2]?.call_status_id == {{CallStatuses::NOSHOW}}" style="font-size:20px; font-weight:bold; color:red">{{__('messages.display.noshow')}}</small>
                                <small v-if="tokens[2] && tokens[2]?.call_status_id == null" style="font-size:20px;color:orange;font-weight:bold;">{{__('messages.display.serving')}}</small>
                                <small v-if="!tokens[2]" style="font-size:20px; font-weight:bold;">{{__('messages.display.nil')}}</small>
                            </div>
                        </div>
                        <div style="height:15%;border-bottom:1px solid #ddd;display:flex;flex-direction:row;justify-content:center;align-items:center">
                            <div>
                                <span v-if="tokens[3]" class="bolder-color" style="font-size:45px;font-weight:bold;line-height:1.2">@{{tokens[3]?.token_letter}}-@{{tokens[3]?.token_number}}</span>
                                <span v-if="!tokens[3]" class="bolder-color" style="font-size:45px;font-weight:bold;line-height:1.2">{{__('messages.display.nil')}}</span><br>
                                <small v-if="tokens[3]" class="bolder-color" id="counter3" style="font-size:25px; font-weight:bold;">@{{tokens[3]?.counter.name}}</small>
                                <small v-if="!tokens[3]" class="bolder-color" id="counter3" style="font-size:25px; font-weight:bold;">{{__('messages.display.nil')}}</small><br>
                                <small v-if="tokens[3]?.call_status_id == {{CallStatuses::SERVED}}" style="font-size:20px; color:#009688; font-weight:bold;">{{__('messages.display.served')}}</small>
                                <small v-if="tokens[3]?.call_status_id == {{CallStatuses::NOSHOW}}" style="font-size:20px; font-weight:bold; color:red">{{__('messages.display.noshow')}}</small>
                                <small v-if="tokens[3] && tokens[3]?.call_status_id == null" style="font-size:20px; color:orange; font-weight:bold;">{{__('messages.display.serving')}}</small>
                                <small v-if="!tokens[3]" style="font-size:20px; font-weight:bold;">{{__('messages.display.nil')}}</small>
                            </div>
                        </div>
                        <div style="height:15%;display:flex;flex-direction:row;justify-content:center;align-items:center">
                            <div>
                                <span v-if="tokens[4]" class="bolder-color" style="font-size:45px;font-weight:bold;line-height:1.2">@{{tokens[4]?.token_letter}}-@{{tokens[4]?.token_number}}</span>
                                <span v-if="!tokens[4]" class="bolder-color" style="font-size:45px;font-weight:bold;line-height:1.2">{{__('messages.display.nil')}}</span><br>
                                <small v-if="tokens[4]" class="bolder-color" id="counter4" style="font-size:25px; font-weight:bold;">@{{tokens[4]?.counter.name}}</small>
                                <small v-if="!tokens[4]" class="bolder-color" id="counter4" style="font-size:25px; font-weight:bold;">{{__('messages.display.nil')}}</small><br>
                                <small v-if="tokens[4]?.call_status_id == {{CallStatuses::SERVED}}" style="font-size:20px; color:#009688; font-weight:bold;">{{__('messages.display.served')}}</small>
                                <small v-if="tokens[4]?.call_status_id == {{CallStatuses::NOSHOW}}" style="font-size:20px; font-weight:bold; color:red">{{__('messages.display.noshow')}}</small>
                                <small v-if="tokens[4] &&tokens[4]?.call_status_id == null" style="font-size:20px; color:orange; font-weight:bold;">{{__('messages.display.serving')}}</small>
                                <small v-if="!tokens[4]" style="font-size:20px; font-weight:bold;">{{__('messages.display.nil')}}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col m10">
                    <div class="card-panel center-align p-0" style="margin-bottom:0;height:134vh">
                        <div class="w3-content w3-section">
                            @foreach($ads as $ad)
                            <img class="mySlides" src="{{ $ad->ad_img_url}}" style="height: 134vh; width: 100%;">
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col m4">
                    <div class="card-panel center-align" style="margin-bottom:0;height:134vh;display:flex;flex-direction:row;justify-content:center;align-items:start">
                        <table id="page-length-option" class="display dataTable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th style="font-size:20px;font-weight:bold;line-height:1.2">{{__('messages.rate_page.buying_rate')}}</th>
                                    <th style="font-size:20px;font-weight:bold;line-height:1.2">{{__('messages.rate_page.selling_rate')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rates as $key=>$rate)
                                <tr>
                                    <td style="font-size:22px;font-weight:bold;line-height:1.2">{{$rate->currency_code}}</td>
                                    <td style="font-size:22px;font-weight:bold;line-height:1.2">{{$rate->buying_rate}}</td>
                                    <td style="font-size:22px;font-weight:bold;line-height:1.2">{{$rate->selling_rate}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- <div>
                            <div class="bolder-color" style="font-size:50px; margin:0px">{{__('messages.display.token number')}}</div>
                            <span v-if="tokens[0]" style="font-size:130px;color:red;font-weight:bold;line-height:1.2">@{{tokens[0]?.token_letter}}-@{{tokens[0]?.token_number}}</span>
                            <span v-if="!tokens[0]" style="font-size:130px;color:red;font-weight:bold;line-height:1.2">{{__('messages.display.nil')}}</span>
                            <div v-if="tokens[0]?.call_status_id == {{CallStatuses::SERVED}}" style="font-size:40px; color:#009688">{{__('messages.display.served')}}</div>
                            <div v-if="tokens[0]?.call_status_id == {{CallStatuses::NOSHOW}}" style="font-size:40px; color:red">{{__('messages.display.noshow')}}</div>
                            <div v-if="tokens[0] && tokens[0]?.call_status_id == null" style="font-size:40px; color:orange; font-weight: bold">{{__('messages.display.serving')}}</div>
                            <div v-if="!tokens[0]" style="font-size:40px; color:orange; font-weight: bold">{{__('messages.display.nil')}}</div>
                            <div class="bolder-color" style="font-size:40px; line-height:1.4">{{__('messages.display.please proceed to')}}</div>
                            <div v-if="tokens[0]" id="counter0" style="font-size:70px; color:red;line-height:1.5">@{{tokens[0]?.counter.name}}</div>
                            <div v-if="!tokens[0]" style="font-size:70px; color:red;line-height:1.5">{{__('messages.display.nil')}}</div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="row" style="margin-bottom:0; margin-top: 15px;">
                <marquee><span style="font-size:{{$settings->display_font_size}}px;color:{{$settings->display_font_color}}">{{$settings->display_notification ? $settings->display_notification : 'Hello' }}<span></span></span></marquee>
            </div>
            <audio id="called_sound">
                <source src="{{asset('app-assets/audio/sound.mp3')}}" type="audio/mpeg">
            </audio>
        </section>
    </div>
</div>
@endsection
@section('b-js')
<script>
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
    window.JLToken = {
        get_tokens_for_display_url: "{{ asset($file) }}",
        get_initial_tokens: "{{ route('get-tokens-for-display') }}",
        date_for_display: "{{$date}}",
        voice_type: "{{$settings->language->display}}",
        voice_content_one: "{{$settings->language->token_translation}}",
        voice_content_two: "{{$settings->language->please_proceed_to_translation}}",
        date_for_display: "{{$date}}",
        audioEl: document.getElementById('called_sound'),
    }
</script>
@endsection 