@extends('layout.app')
@section('title','Exchange Rates')
@section('rate','active')
@section('content')
<div id="main" style="width:99%;">
    <div id="breadcrumbs-wrapper">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h5 class="breadcrumbs-title col s5"><b>{{__('messages.rate_page.add_rate')}}</b></h5>
                    <ol class="breadcrumbs col s7 right-align">
                        <a class="btn-floating waves-effect waves-light teal tooltipped" href="{{route('rates.index')}}" data-position=top data-tooltip="{{__('messages.common.go back')}}"><i class="material-icons">arrow_back</i></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l12  offset-m1">
                    <div class="card-panel">
                        <div class="row">
                            <form id="rate_form" method="post" action="{{route('rates.store')}}">
                                {{@csrf_field()}}
                                <div class="row">
                                    <div class="row form_align">
                                        <div class="input-field col s10">
                                            <select name="country_name" id="country_name">
                                                <option value="" disabled selected>{{__('messages.rate_page.country_name')}}</option>
                                                @foreach ($countries as $country)
                                                <option value="{{$country->code}}">{{$country->country}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input name="country_name_text" type="hidden" id="country_name_text">
                                        
                                        <div class="col s2">
                                            <img id = "country_flag" height="65px" width="60px" src="">
                                        </div>

                                        <input name="country_flag_text" type="hidden" id="country_flag_text">

                                    </div>
                                    <div class="row form_align">
                                        <div class="input-field col s6">
                                            <label id = "country_code_label" for="country_code">{{__('messages.rate_page.country_code')}}</label>
                                            <input id="country_code" name="country_code" type="text" value="" data-error=".country_code" readonly>
                                            <div class="country_code">
                                                @if ($errors->has('country_code'))
                                                <span class="text-danger errbk">{{ $errors->first('country_code') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="input-field col s6">
                                            <label id = "currency_code_label" for="currency_code">{{__('messages.rate_page.currency_code')}}</label>
                                            <input id="currency_code" name="currency_code" type="text" value="{{old('currency_code')}}" data-error=".currency_code" readonly>
                                            <div class="currency_code">
                                                @if ($errors->has('currency_code'))
                                                <span class="text-danger errbk">{{ $errors->first('currency_code') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form_align">
                                        <div class="input-field col s6">
                                            <label for="buying_rate">{{__('messages.rate_page.buying_rate')}}</label>
                                            <input id="buying_rate" name="buying_rate" type="number" value="{{old('buying_rate')}}" data-error=".buying_rate">
                                            <div class="buying_rate">
                                                @if ($errors->has('buying_rate'))
                                                <span class="text-danger errbk">{{ $errors->first('buying_rate') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="input-field col s6">
                                            <label for="selling_rate">{{__('messages.rate_page.selling_rate')}}</label>
                                            <input id="selling_rate" name="selling_rate" type="number" value="{{old('selling_rate')}}" data-error=".selling_rate">
                                            <div class="selling_rate">
                                                @if ($errors->has('selling_rate'))
                                                <span class="text-danger errbk">{{ $errors->first('selling_rate') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-field col s12">
                                        <button class="btn waves-effect waves-light right submit" type="submit">{{__('messages.common.submit')}}
                                            <i class="mdi-content-send right"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{asset('app-assets/js/vendors.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('body').addClass('loaded');

        $(document).on("change", "#country_name", function () {
            var country_code = $(this).val();
            if (country_code != "") {
                $.ajax({
                url: "{{ url('rates/get_details_by_country_code') }}",
                type: "POST",
                data: "country_code=" + country_code ,
                headers: {
                    "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
                },
                beforeSend: function () {
                    $("#preloader").css("display", "block");
                },
                success: function (data) {
                    var json = JSON.parse(data);
                    $("#country_code_label").addClass('active');
                    $("#currency_code_label").addClass('active');
                    $("#country_code").val(json['countryCode']);
                    $("#country_name_text").val(json['country']);
                    $("#country_flag_text").val(json['flag']);
                    $("#currency_code").val(json['code']);
                    $("#country_flag").attr('src', json['flag']);
                },
                });
            }
        });

    });


    $(function() {
        $('#rate_form').validate({
            ignore: [],
            rules: {
                country_name: {
                    required: true,
                },
                country_code: {
                    required: true,
                },
                currency_code: {
                    required: true,
                },
                buying_rate: {
                    required: true,
                    min: 0
                },
                selling_rate: {
                    required: true,
                    min: 0
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
            }
        });
    });


    function changeStatusMessage() {
        if ($('#status_message').val() == 1) {
            $('#status_message_tab').show();
        } else {
            $('#status_message_tab').hide();
        }
    }

</script>
@endsection
