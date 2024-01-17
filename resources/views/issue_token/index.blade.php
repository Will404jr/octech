@extends('layout.call_page')
@section('content')
<!-- BEGIN: Page Main-->
<style>
    .display-card {
      box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
      transition: 0.3s;
      width: 40%;
    }

    .display-btn-large {
        height: 200px
    }
    
    .display-card:hover {
      box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }
    
    </style>
<div id="loader-wrapper">
    <div id="loader"></div>

    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>

</div>
<div id="main" class="noprint" style="padding: 15px 15px 0px;">
    <div class="wrapper">
        <section class="content-wrapper no-print">
            <div class="container no-print">
                <div class="row">
                    <div class="col s6">
                        <div class="card" style="background:#f9f9f9;box-shadow:none;" id="service-btn-container">
                            <span class="card-title" style="line-height:1;font-size:22px"> {{__('messages.issue_token.click one service to issue token')}}</span>
                            <div class="divider" style="margin:10px 0 10px 0"></div>
                            
                            @foreach($services as $service)
                            <span class="btn display-btn-large btn-queue waves-effect waves-light mb-1" id="service_id_24" style="background: #009688; margin-right: 40px; margin-top: 20px;" onclick="queueDept({{$service}})">
                                <div style="width: 100; height: 100; margin-top: 20px;">
                                    <img src="{{URL::asset('app-assets/images/gallery/doc_icon.png')}}" alt="Avatar" style="width:100%">
                                    <div>
                                      <h4><b style="color: white; margin-top: 20px;">{{$service->name}}</b></h4> 
                                    </div>
                                  </div>
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="col s6">
                        <div class="card" style="background:#f9f9f9;box-shadow:none" id="service-btn-container">
                        <span class="card-title" style="line-height:1;font-size:22px"> {{__('messages.issue_token.welcome')}}</span>
                        <div class="divider" style="margin:10px 0 10px 0"></div>
                        <img class="mySlides" src="{{URL::asset('app-assets/images/gallery/ihk.png')}}" style="width: 100%; ">
                        </div>
                    </div>
                    <form action="{{route('create-token')}}" method="post" id="my-form-two" style="display: none;">
                        {{csrf_field()}}
                    </form>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal Structure -->
    <div id="modal1" class="modal modal-fixed-footer" style="max-height: 30%; width:80%">
        <form id="details_form">
            <div class="modal-content" style="padding-bottom:0">
                <div id="inline-form">
                    <div class="card-content">
                        <div class="row">
                            <div class="input-field col s4" id="gender_tab">
                                <select id="gender" name="gender" value="" data-error=".gender">
                                    <option value="0">Male</option>
                                    <option value="1">Female</option>
                                </select>
                                <label for="gender">{{__('messages.settings.gender')}}</label>
                                <div class="gender">

                                </div>
                            </div>
                            <div class="input-field col s4" id="payment_mode_tab">
                                <select id="payment_mode" name="payment_mode" type="text" value="" data-error=".payment_mode">
                                    <option value="0">Cash</option>
                                    <option value="1">Insurance</option>
                                </select>
                                <label for="payment_type">{{__('messages.settings.payment mode')}}</label>
                                <div class="payment_mode">

                                </div>
                            </div>
                            <div class="input-field col s4" id="phone_tab">
                                <input id="phone" name="phone" type="text" value="" data-error=".phone">
                                <label for="phone">{{__('messages.settings.phone')}}</label>
                                <div class="phone">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="modal_button" type="submit" class="modal-action waves-effect waves-green btn-flat" style="background: #009688; color:#fff" onclick="issueToken()">{{__('messages.common.submit')}}</button>
            </div>
            <form>
    </div>
</div>
@endsection
<div id="printarea" class="printarea" style="text-align:center;margin-top: 20px; display:none">
</div>
@section('js')
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
    $(document).ready(function() {
        $('body').addClass('loaded');
        $('.modal').modal();
    })
    var service;

    function queueDept(value) {
        if (value.ask_payment_mode == 1 || value.ask_gender == 1 || value.ask_phone == 1) {
            if (value.ask_gender == 1) $('#gender_tab').show();
            else $('#gender_tab').hide();
            if (value.ask_payment_mode == 1) $('#payment_mode_tab').show();
            else $('#payment_mode_tab').hide();
            if (value.ask_phone == 1) $('#phone_tab').show();
            else $('#phone_tab').hide()
            service = value;
            $('#modal_button').removeAttr('disabled');
            $('#modal1').modal('open');
        } else {
            $('body').removeClass('loaded');
            let data = {
                service_id: value.id,
                with_details: false
            }
            createToken(data);
        }
    }

    function issueToken() {
        $('#details_form').validate({
            rules: {
                gender: {
                    required: function(element) {
                        return service.gender == "1";
                    },
                },
                payment_mode: {
                    required: function(element) {
                        return service.payment_mode == "1";
                    },
                    payment_mode: true
                },
                phone: {
                    required: function(element) {
                        return service.phone_required == "1";
                    },
                    number: true
                },
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                var placement = $(element).data('error');
                if (placement) {
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                $('#modal_button').attr('disabled', 'disabled');
                $('body').removeClass('loaded');
                let data = {
                    service_id: service.id,
                    gender: $('#gender').val(),
                    payment_mode: $('#payment_mode').val(),
                    phone: $('#phone').val(),
                    with_details: true
                }
                createToken(data);
            }
        });
    }

    function createToken(data) {
        $.ajax({
            type: "POST",
            url: "{{route('create-token')}}",
            data: data,
            cache: false,
            success: function(response) {
                if (response.status_code == 200) {
                    $('#modal1').modal('close');
                    $('#phone').val(null);
                    $('#gender').val(null);
                    $('#payment_mode').val(null);
                    let html = `
                            <p style="font-size: 15px; font-weight: bold; margin-top:-15px;">` + response.settings.name + `,` + response.settings.location + `
                            </p>
                            <p style="font-size: 10px; margin-top:-15px;">` + response.queue.service.name + `</p>
                            <h3 style="font-size: 20px; margin-bottom: 5px; font-weight: bold; margin-top:-12px; margin-bottom:16px;">` + response.queue.letter + ` - ` + response.queue.number + `</h3>
                            <p style="font-size: 12px; margin-top: -16px;margin-bottom: 27px;">` + response.queue.formated_date + `</p>
                            <div style="margin-top:-20px; margin-bottom:15px;" align="center">
                            </div>
                            <p style="font-size: 10px; margin-top:-12px;">{{__('messages.issue_token.please wait for your turn')}}</p>
                            <p style="font-size: 10px; margin-top:-12px;">{{__('messages.issue_token.customer waiting')}}:` + response.customer_waiting + ` 
                            </p>
                            <p style="text-align:left !important;font-size:8px;"></p>
                            <p style="text-align:right !important; margin-top:-23px;font-size:8px;"></p>`;
                    $('#printarea').html(html);
                    $('body').addClass('loaded');
                    window.print();
                } else if (response.status_code == 422 && response.errors && (response.errors['gender'] || response.errors['payment_mode'] || response.errors['phone'])) {
                    $('#modal_button').removeAttr('disabled');
                    if (response.errors['gender'] && response.errors['gender'][0]) {
                        $('.gender').html('<span class="text-danger errbk">' + response.errors['gender'][0] + '</span>')
                    }
                    if (response.errors['payment_mode'] && response.errors['payment_mode'][0]) {
                        $('.payment_mode').html('<span class="text-danger errbk">' + response.errors['payment_mode'][0] + '</span>')
                    }
                    if (response.errors['phone'] && response.errors['phone'][0]) {
                        $('.phone').html('<span class="text-danger errbk">' + response.errors['phone'][0] + '</span>')
                    }
                    $('body').addClass('loaded');
                } else {
                    $('#modal1').modal('close');
                    $('#phone').val(null);
                    $('#payment_mode').val(null);
                    $('#gender').val(null);
                    $('body').addClass('loaded');
                    M.toast({
                        html: 'something went wrong',
                        classes: "toast-error"
                    });
                }
            },
            error: function() {
                $('body').addClass('loaded');
                $('#modal1').modal('close');
                M.toast({
                    html: 'something went wrong',
                    classes: "toast-error"
                });
            }
        });
    }
</script>
@endsection()