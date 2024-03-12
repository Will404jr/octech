@extends('layout.app')
@section('title','Branches')
@section('branch','active')
@section('content')
<div id="main" style="width:99%;">
    <div id="breadcrumbs-wrapper">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h5 class="breadcrumbs-title col s5"><b>{{__('messages.branch_page.edit branch')}}</b></h5>
                    <ol class="breadcrumbs col s7 right-align">
                        <a class="btn-floating waves-effect waves-light teal tooltipped" href="{{route('branches.index')}}" data-position=top data-tooltip="{{__('messages.common.go back')}}"><i class="material-icons">arrow_back</i></a>
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
                            <form id="branch_form" method="post" action="{{route('branches.update',[$branch->id])}}" enctype="multipart/form-data">
                                {{@csrf_field()}}
                                {{method_field('PATCH')}}
                                <div class="row">
                                    <div class="row form_align">
                                        <div class="input-field col s6">
                                            <label for="name">{{__('messages.branch_page.name')}}</label>
                                            <input id="name" name="name" type="text" value="{{$branch->name}}" data-error=".name">
                                            <div class="name">
                                                @if ($errors->has('name'))
                                                <span class="text-danger errbk">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="input-field col s6">
                                            <label for="address">{{__('messages.branch_page.address')}}</label>
                                            <input id="address" name="address" type="text" value="{{$branch->address}}" data-error=".address">
                                            <div class="address">
                                                @if ($errors->has('address'))
                                                <span class="text-danger errbk">{{ $errors->first('address') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="input-field col s6">
                                            <label for="local_network_address">{{__('messages.branch_page.local_network_address')}}</label>
                                            <input id="local_network_address" name="local_network_address" type="text" value="{{$branch->local_network_address}}" data-error=".local_network_address">
                                            <div class="local_network_address">
                                                @if ($errors->has('local_network_address'))
                                                <span class="text-danger errbk">{{ $errors->first('local_network_address') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="input-field col s6">
                                            <label for="db_host">{{__('messages.branch_page.db_host')}}</label>
                                            <input id="db_host" name="db_host" type="text" value="{{$branch->db_host}}" data-error=".db_host">
                                            <div class="db_host">
                                                @if ($errors->has('db_host'))
                                                <span class="text-danger errbk">{{ $errors->first('db_host') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="input-field col s6">
                                            <label for="db_name">{{__('messages.branch_page.db_name')}}</label>
                                            <input id="db_name" name="db_name" type="text" value="{{$branch->db_name}}" data-error=".db_name">
                                            <div class="db_name">
                                                @if ($errors->has('db_name'))
                                                <span class="text-danger errbk">{{ $errors->first('db_name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="input-field col s6">
                                            <label for="db_user">{{__('messages.branch_page.db_user')}}</label>
                                            <input id="db_user" name="db_user" type="text" value="{{$branch->db_username}}" data-error=".db_user">
                                            <div class="db_user">
                                                @if ($errors->has('db_user'))
                                                <span class="text-danger errbk">{{ $errors->first('db_user') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="input-field col s6">
                                            <label for="db_password">{{__('messages.branch_page.db_password')}}</label>
                                            <input id="db_password" name="db_password" type="text" value="{{$branch->db_password}}" data-error=".db_password">
                                            <div class="db_password">
                                                @if ($errors->has('db_password'))
                                                <span class="text-danger errbk">{{ $errors->first('db_password') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
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
        $('#branch_form').validate({
            rules: {
                name: {
                    required: true,
                },
                address: {
                    required: true,
                },
                local_network_address: {
                    required: true,
                },
                db_host: {
                    required: true,
                },
                db_name: {
                    required: true,
                },
                db_user: {
                    required: true,
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
</script>
@endsection