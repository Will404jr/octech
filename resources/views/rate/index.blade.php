@extends('layout.app')
@section('title','Exchange Rates')
@section('rate','active')
@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/data-tables/css/jquery.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/data-tables/css/select.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/pages/data-tables.css')}}">
@endsection
@section('content')
<div id="main">
    <div id="breadcrumbs-wrapper">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h5 class="breadcrumbs-title col s5"><b>{{__('messages.rate_page.rates')}}</b></h5>
                    <ol class="breadcrumbs col s7 right-align">
                        <a class="btn-floating waves-effect waves-light tooltipped" href="{{route('rates.create')}}" data-position="top" data-tooltip="{{__('messages.rate_page.add_rate')}}">
                            <i class="material-icons">add</i>
                        </a>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12">
        <div class="container" style="width: 99%;">
            <div class="section-data-tables">
                <div class="row">
                    <div class="col s12">
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col s12">
                                        <table id="page-length-option" class="display dataTable">
                                            <thead>
                                                <tr>
                                                    <th width="10px">#</th>
                                                    <th>{{__('messages.rate_page.country_name')}}</th>
                                                    <th>{{__('messages.rate_page.currency_code')}}</th>
                                                    <th>{{__('messages.rate_page.buying_rate')}}</th>
                                                    <th>{{__('messages.rate_page.selling_rate')}}</th>
                                                    <th>{{__('messages.rate_page.status')}}</th>
                                                    <th>{{__('messages.rate_page.action')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($rates as $key=>$rate)
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{$rate->country_name}}</td>
                                                    <td>{{$rate->currency_code}}</td>
                                                    <td>{{$rate->buying_rate}}</td>
                                                    <td>{{$rate->selling_rate}}</td>
                                                    <td>
                                                        <div class="switch">
                                                            <label>
                                                                <input type="checkbox" {{$rate->status?'checked':''}} onchange="changeStatus({{$rate->id}})">
                                                                <span class="lever"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a class="btn-floating btn-action waves-effect waves-light orange tooltipped" href="{{route('rates.edit',[$rate->id])}}" data-position=top data-tooltip="{{__('messages.common.edit')}}"><i class="material-icons">edit</i></a>
                                                        <a class="btn-floating btn-action waves-effect waves-light red tooltipped frmsubmit" href="{{route('rates.destroy',[$rate->id])}}" data-position=top data-tooltip="{{__('messages.common.delete')}}" method="DELETE"><i class="material-icons">delete</i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
    $('#page-length-option').DataTable({
        "responsive": true,
        "autoHeight": false,
        "scrollX": true,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ]
    });
    $(document).ready(function() {
        $('body').addClass('loaded');
    });
</script>
<script>
    function changeStatus(id) {
        $('body').removeClass('loaded');
        var data = "id=" + id + '&_token={{csrf_token()}}';
        $.ajax({
            type: "POST",
            url: "{{Route('rate_change_status')}}",
            data: data,
            cache: false,
            success: function(response) {
                location.reload(true);
            }
        });
    }
</script>
@endsection