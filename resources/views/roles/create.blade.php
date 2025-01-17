@extends('layout.app')
@section('title','User Roles')
@section('roles','active')
@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/data-tables/css/jquery.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/data-tables/css/select.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/data-tables.css')}}">
@endsection
@section('content')
<div id="main" style="width:99%;">
    <div id="breadcrumbs-wrapper">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h5 class="breadcrumbs-title col s5"><b>{{__('messages.user_roles_page.add user role')}}</b></h5>
                    <ol class="breadcrumbs col s7 right-align">
                        <a class="btn-floating waves-effect waves-light teal tooltipped" href="{{route('roles.index')}}" data-position=top data-tooltip="{{__('messages.common.go back')}}"><i class="material-icons">arrow_back</i></a>
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
                            <form id="news_form" method="post" action="{{route('roles.store')}}" enctype="multipart/form-data">
                                {{@csrf_field()}}
                                <div class="row">
                                    <div class="row form_align">
                                        <div class="input-field col s6">
                                            <label for="name">{{__('messages.user_roles_page.role name')}}</label>
                                            <input id="name" name="name" type="text" value="" data-error=".name" required>
                                            <div class="name">
                                                @if ($errors->has('name'))
                                                <span class="text-danger errbk">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form_align">
                                        <div class="input-field col s12">
                                            <table id="page-length-option" class="display dataTable">
                                                <thead>
                                                    <tr>
                                                        <td style="font-size:large"><b>{{__('messages.user_roles_page.user roles')}}</b></td>
                                                        <td>
                                                            <label>
                                                                <input type="checkbox" id="select_all" />
                                                                <span>{{__('messages.user_roles_page.select all')}}</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{__('messages.user_roles_page.module')}}</th>
                                                        <th>{{__('messages.user_roles_page.access')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{__('messages.user_roles_page.view dashboard')}}</td>
                                                        <td>
                                                            <label>
                                                                <input type="checkbox" class="checkbox" id="dashboard" name="permission[view dashboard]" />
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('messages.user_roles_page.view branches')}}</td>
                                                        <td>
                                                            <label>
                                                                <input type="checkbox" class="checkbox" id="branches" name="permission[view branches]" />
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('messages.user_roles_page.view rates')}}</td>
                                                        <td>
                                                            <label>
                                                                <input type="checkbox" class="checkbox" id="rates" name="permission[view rates]" />
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('messages.user_roles_page.view users')}}</td>
                                                        <td>
                                                            <label>
                                                                <input type="checkbox" class="checkbox" id="users" name="permission[view users]" />
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('messages.user_roles_page.call token')}}</td>
                                                        <td>
                                                            <label>
                                                                <input type="checkbox" class="checkbox" id="call" name="permission[call token]" />
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('messages.user_roles_page.issue token')}}</td>
                                                        <td>
                                                            <label>
                                                                <input type="checkbox" class="checkbox" id="call" name="permission[issue token]" />
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('messages.user_roles_page.view queues')}}</td>
                                                        <td>
                                                            <label>
                                                                <input type="checkbox" class="checkbox" id="queues" name="permission[view queues]" />
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('messages.user_roles_page.view ads')}}</td>
                                                        <td>
                                                            <label>
                                                                <input type="checkbox" class="checkbox" id="ads" name="permission[view ads]" />
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('messages.user_roles_page.view profile')}}</td>
                                                        <td>
                                                            <label>
                                                                <input type="checkbox" class="checkbox" id="profile" name="permission[view profile]" />
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('messages.user_roles_page.view settings')}}</td>
                                                        <td>
                                                            <label>
                                                                <input type="checkbox" class="checkbox" id="settings" name="permission[view settings]" />
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('messages.user_roles_page.view user roles')}}</td>
                                                        <td>
                                                            <label>
                                                                <input type="checkbox" class="checkbox" id="userroles" name="permission[view user_roles]" />
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="input-field col s12">
                                        <button class="btn waves-effect waves-light right submit" type="submit">{{__('messages.common.submit')}}<i class="mdi-content-send right"></i></button>
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
<script src="{{asset('app-assets/vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('app-assets/vendors/data-tables/js/dataTables.select.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#select_all').on('click', function() {
            if (this.checked) {
                $('.checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.checkbox').each(function() {
                    this.checked = false;
                });
            }
        });
        $('.checkbox').on('click', function() {
            if ($('.checkbox:checked').length == $('.checkbox').length) {
                $('#select_all').prop('checked', true);
            } else {
                $('#select_all').prop('checked', false);
            }
        });


        $('body').addClass('loaded');

    });
</script>
@endsection