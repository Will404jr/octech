@extends('layout.app')
@section('title','Display Ads')
@section('ad','active')
@section('content')
<div id="main" style="width:99%;">
    <div id="breadcrumbs-wrapper">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h5 class="breadcrumbs-title col s5"><b>{{__('messages.ad_page.edit_ad')}}</b></h5>
                    <ol class="breadcrumbs col s7 right-align">
                        <a class="btn-floating waves-effect waves-light teal tooltipped" href="{{route('ads.index')}}" data-position=top data-tooltip="{{__('messages.common.go back')}}"><i class="material-icons">arrow_back</i></a>
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
                            <form id="ad_form" method="post" action="{{route('ads.update',[$ad->id])}}" enctype="multipart/form-data">
                                {{@csrf_field()}}
                                {{method_field('PATCH')}}
                                <div class="row">
                                    <div class="row form_align">
                                        <div class="input-field col s12">
                                            <label for="name">{{__('messages.ad_page.name')}}</label>
                                            <input id="name" name="name" type="text" value="{{$ad->name}}" data-error=".name">
                                            <div class="name">
                                                @if ($errors->has('name'))
                                                <span class="text-danger errbk">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="input-field col s12">
                                            <select name="branch_id" id="branch_id">
                                                <option value="" disabled selected>{{__('messages.branch_page.select branch')}}</option>
                                                @foreach ($branches as $branch)
                                                <option value="{{$branch->id}}" {{ $ad->branch->name == $branch->name ? 'selected' : '' }}>{{$branch->name}} </option>
                                                @endforeach
                                            </select>
                                            <label>{{__('messages.branch_page.branch')}}</label>
                                            <div class="branch">
                                                @if ($errors->has('branch'))
                                                <span class="text-danger errbk">{{ $errors->first('branch') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                        <div class="file-field input-field col s6">
                                            <div class="btn">
                                                <span>{{__('messages.ad_page.ad_img')}}</span>
                                                <input type="file" name="ad_img" data-error=".ad_img">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input class="file-path validate" type="text">
                                            </div>
                                            @if ($errors->has('ad_img'))
                                            <span class="text-danger errbk">{{ $errors->first('ad_img') }}</span>
                                            @endif
                                        </div>
                                        @if($ad->ad_img && Storage::disk('public')->exists($ad->ad_img))<div class="input-field col s6"><img height="500px" width="100%" src="{{ $ad->ad_img_url }}"></div>@endif
                                    <div class="input-field col s12">
                                        <button class="btn waves-effect waves-light right submit" type="submit" name="action">{{__('messages.common.update')}}
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
    });


    $(function() {
        $('#ad_form').validate({
            rules: {
                name: {
                    required: true,
                },
                ad_img: {
                    accept: "jpg|jpeg|png"
                }
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
</script>
@endsection