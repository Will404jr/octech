@extends('layout.call_page')
@section('call','active')
@section('content')
<div id="loader-wrapper">
    <div id="loader"></div>

    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>

</div>

{{-- Dashboard cards --}}
<div id="main">
    <div id = "call-page">
    <div id="card-stats" class="pt-0">
        <div class="row">
            <div class="col s12 m6 l3">
                <div class="card ">
                    <div class="card-content cyan white-text">
                        <p class="card-stats-title">{{ __('messages.dashboard.today queue') }}</p>
                        <h4 class="card-stats-number white-text">@{{today_queue}} </h4>
                        <p class="card-stats-compare">
                        </p>
                    </div>
                    <div class="card-action cyan darken-1">
                        <div id="clients-bar" class="center-align"></div>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 l3">
                <div class="card ">
                    <div class="card-content red accent-2 white-text">
                        <p class="card-stats-title">{{ __('messages.dashboard.today served') }}</p>
                        <h4 class="card-stats-number white-text">@{{ today_served }}</h4>
                        <p class="card-stats-compare">
                        </p>
                    </div>
                    <div class="card-action red">
                        <div id="sales-compositebar" class="center-align"></div>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 l3">
                <div class="card ">
                    <div class="card-content orange lighten-1 white-text">
                        <p class="card-stats-title"> {{ __('messages.dashboard.today noshow') }}</p>
                        <h4 class="card-stats-number white-text">@{{ today_noshow }}</h4>
                        <p class="card-stats-compare">
                        </p>
                    </div>
                    <div class="card-action orange">
                        <div id="profit-tristate" class="center-align"></div>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 l3">
                <div class="card">
                    <div class="card-content green lighten-1 white-text">
                        <p class="card-stats-title"> {{ __('messages.dashboard.today serving') }}</p>
                        <h4 class="card-stats-number white-text">@{{ today_serving }}</h4>
                        <p class="card-stats-compare">
                        </p>
                    </div>
                    <div class="card-action green">
                        <div id="invoice-line" class="center-align"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col m6 s12">
           <div class=card-panel>
              <span style="line-height:0;font-size:22px;font-weight:300">{{__('messages.dashboard.token form')}}</span>
              <div class=divider style="margin:15px 0 10px 0"></div>
              <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
								<label class="control-label">Phone Number</label>						
								<input type="text" class="form-control" name="phone" id = "phone" value="" readonly>
							</div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
								<label class="control-label">Payment Type</label>
                                <input type="text" class="form-control" name="payment_mode" id = "payment_mode" value="" readonly>						
								<!-- <select class="form-control auto-select select2" id="payment_mode" name="payment_mode" required>
									<option disabled value="">Select Payment Type</option>
                                    <option selected = "paymentModeIsCash" value="0">Cash</option>
                                    <option selected = "paymentModeIsInsurance" value="1">Insurance</option>
								</select> -->
							</div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
								<label class="control-label">Gender</label>	
                                <input type="text" class="form-control" name="gender" id = "gender" value="" readonly>					
								<!-- <select class="form-control auto-select select2" id="gender" name="gender" required>
									<option disabled value="">Select Gender</option>
                                    <option selected = "genderIsMale" value="0">Male</option>
                                    <option selected = "genderIsFemale" value="1">Female</option>
								</select> -->
							</div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Reason for Visit</label>
                                <input type="text" class="form-control" name="reason" id = "reason" value="" readonly>		
                                <!-- <select type="text" class="form-control" name="reason"
                                    id="reason" required>
                                    <option disabled value = "">Reason For Visit</option>
                                    @foreach ($reasons as $reason)
                                    <option selected = "service_id == {{$reason->id}}" value="{{$reason->id}}">{{$reason->name}} </option>
                                    @endforeach
                                </select> -->
                            </div>
                        </div>
                        <div class="row">
                            <!-- <div class="input-field col s6">
                                <button id="next_call" class="btn waves-effect waves-light center call-bt submit " type="submit" @click="callNext()" :disabled="isCalled || callNextClicked || holdClicked">{{__('messages.call_page.call next')}}
                                    <i class="material-icons right">add</i>
                                </button>
    
                            </div> -->
                            <div class="input-field col s6">
                                <button class="btn waves-effect waves-light center submit call-bt" type="submit" @click="openEditTokenModal()" name="action" :disabled="!isCalled">{{__('messages.call_page.edit')}}
                                    <i class="material-icons right">edit</i>
                                </button>
                            </div>
                        </div>
                    </div>
            </div>
           </div>
        </div>
        <div class="col m6 s12">
            <div class=card-panel>
               <span style="line-height:0;font-size:22px;font-weight:300">{{__('messages.dashboard.call center')}}</span>
               <div class=divider style="margin:15px 0 10px 0"></div>
                <div class="row" v-if="selected_counter && selected_service" style="min-width: 800px;">
                    <div class="col s10 center" v-if="token">
                        <span style="font-size: 40px;" class="truncate">
                            <a class="waves-effect waves-light  modal-trigger" href="#modal5" dismissible="false" style="color: #000;">
                                <input type="hidden" name="transfer_queue" id="transfer_queue" value="1989">
                                <input type="hidden" name="last_call" id="last_call" value="queue" v-cloak>
                                @{{token.letter?token.letter : token.token_letter }}-@{{token.number? token.number : token.token_number}}
                            </a>
                        </span>
                    </div>
                    <div class="col s10 center" v-if="!token">
                        <span style="font-size: 48px; color:black" class="truncate">
                            {{__('messages.call_page.nil')}}
                        </span><br>
                    </div>
                    <br>
                    <div style="margin-top:10px;" class="col s10 center">
                        <div style="font-size:20px;" v-if="token?.call_status_id == {{CallStatuses::SERVED}}">{{__('messages.call_page.served')}}</div>
                        <div style="font-size:20px;" v-if="token?.call_status_id == {{CallStatuses::HOLD}}">{{__('messages.call_page.onhold')}}</div>
                        <div style="font-size:20px;" v-if="token?.call_status_id == {{CallStatuses::NOSHOW}}">{{__('messages.call_page.noshow')}}</div>
                        <div style="font-size:20px; color: red;" v-if="token && isCalled && slaReached && this.token.ended_at == null">@{{time_after_called}}</div>
                        <div style="font-size:20px; color: black;" v-if="token && isCalled && !slaReached && this.token.ended_at == null">@{{time_after_called}}</div>
                    </div>
                </div>
                <div class="row" style="margin-top:40px; min-width:800px">
                    <div class=" col m6 offset-m2 col s8 center">
                        <div class="input-field col s6">
                            <button class="btn waves-effect waves-light center call-bt submit" type="submit" @click="serveToken(token.id)" style="min-width:165px" :disabled="!isCalled ||servedClicked">{{__('messages.call_page.served')}}
                                <i class="material-icons right">done</i>
                            </button>

                        </div>
                        <div class="input-field col s6">
                            <button class="btn waves-effect waves-light center submit call-bt" type="submit" @click="recallToken(token.id)" name="action" style="min-width:165px" :disabled="!isCalled || recallClicked || breakClicked">{{__('messages.call_page.recall')}}
                                <i class="material-icons right">replay</i>
                            </button>
                        </div>
                    </div>
                    <div class="col m6 offset-m2 col s12 center">
                        <div class="input-field col s6">
                            <button class="btn waves-effect waves-light center submit call-bt" type="submit" name="action" @click="holdToken(token.id)" :disabled="!isCalled" style="min-width:165px;">{{__('messages.call_page.hold')}}
                                <i class="material-icons right">pause</i>
                            </button>
                        </div>
                        <div class="input-field col s6" v-if="breakClicked">
                            <button class="btn waves-effect waves-light center submit call-bt" type="submit" name="action" @click="holdToken(token.id)" :disabled="!isCalled" style="min-width:165px;">{{__('messages.call_page.continue')}}
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                        <div class="input-field col s6">
                            <button id="next_call" class="btn waves-effect waves-light center call-bt submit " type="submit" style="min-width:165px;" @click="openSetTransferModal(token.id)" name="action"  :disabled="!isCalled || recallClicked || breakClicked">{{__('messages.call_page.transfer')}}
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </div>
                    <div class="col m6 offset-m2 col s12 center">
                        <div class="input-field col s6">
                            <button class="btn waves-effect waves-light center submit call-bt" type="submit" name="action" @click="noShowToken(token.id)" :disabled="!isCalled || noshowClicked || breakClicked" style="min-width:165px;">{{__('messages.call_page.noshow')}}
                                <i class="material-icons right">clear</i>
                            </button>
                        </div>

                        <div class="input-field col s6">
                            <button class="btn waves-effect waves-light center submit call-bt" type="submit" name="action" @click="breakToken(token.id)" :disabled="isCalled || breakClicked" style="min-width:165px;">{{__('messages.call_page.break')}}
                                <i class="material-icons right">pause</i>
                            </button>
                        </div>
                    </div>
                    <div class="col s12 center-align mt-2" v-if="selected_service && selected_counter">
                        <b>{{__('messages.call_page.service')}}:</b> @{{ selected_service.name }}|
                        <b>{{__('messages.call_page.counter')}}: </b>@{{selected_counter.name}} |
                        <a class="btn-floating btn-action waves-effect waves-light orange tooltipped" @click="openSetServiceModal()" data-position="top" data-tooltip="Change"><i class="material-icons">edit</i></a>
                    </div>
                </div>

                <div id="select-service" class="modal modal-fixed-footer">
                    <div class="modal-content">
                        <div class="offset-s1"></div>
    
                        <form action="" method="" class="form-horizontal">
                            <h4 class="header center" style="font-size:34px;text-transform:none;">
                                {{__('messages.call_page.set service and counter')}}
                            </h4>
                            <div class="divider col s12"></div>
                            <div class="row" style="padding-top: 7px;">
                                <!-- <div class="row">
                                    <div class="input-field col s10 offset-s1">
                                        <div class="input-field col s12">
                                            <select v-model="status">
                                                <option value="" disabled selected>{{__('messages.call_page.status')}}</option>
                                                <option value="1">Available</option>
                                                <option value="0">Unavailable</option>
                                            </select>
                                            <label>{{__('messages.call_page.status')}}</label>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="row">
                                    <div class="input-field col s10 offset-s1">
                                        <div class="input-field col s12">
                                            <select v-model="service_id">
                                                <option value="" disabled selected>{{__('messages.call_page.choose your service')}}</option>
                                                <option v-for="service in services" :value="service.id">@{{service.name}}</option>
                                            </select>
                                            <label>{{__('messages.call_page.service')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s10 offset-s1">
                                        <div class="input-field col s12">
                                            <select v-model="counter_id">
                                                <option value="" disabled selected>{{__('messages.call_page.choose your counter')}}</option>
                                                <option v-for="counter in counters" :value="counter.id">@{{counter.name}}</option>
                                            </select>
                                            <label>{{__('messages.call_page.counter')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
    
                        <a v-if="!selected_service && !selected_counter" href="{{route('show_call_page')}}"><button class="btn waves-effect waves-light red" style="margin-right: 20px ; margin-left: 20px" type="button">{{__('messages.common.go back')}}
                                <i class="material-icons right">close</i>
                            </button></a>
                        <button v-if="selected_service && selected_counter" class="modal-close btn waves-effect waves-light red" style="margin-right: 20px ; margin-left: 20px" type="button">{{__('messages.common.close')}}
                            <i class="material-icons right">close</i>
                        </button>
                        <button class="btn waves-effect waves-light submit" type="submit" name="action" :disabled="!service_id || !counter_id" @click="setService()">{{__('messages.common.submit')}}
                            <i class="material-icons right">send</i>
                        </button>
                    </div>
    
                </div>

                <div id="edit-token" class="modal modal-fixed-footer">
                    <div class="modal-content">
                        <div class="offset-s1"></div>
    
                        <form action="" method="" class="form-horizontal">
                            <h4 class="header center" style="font-size:34px;text-transform:none;">
                                {{__('messages.call_page.edit token')}}
                            </h4>
                            <div class="divider col s12"></div>
                                <div class="row">
                                    <div class="row">
                                        <div class="input-field col s10 offset-s1">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Phone Number</label>						
                                                    <input type="text" class="form-control" name="phone" v-model = "phone" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="input-field col s10 offset-s1">                                    
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Payment Type</label>					
                                                    <select class="form-control auto-select select2" v-model="payment_mode" name="payment_mode" required>
                                                        <option disabled value="">Select Payment Type</option>
                                                        <option selected = "paymentModeIsCash" value="0">Cash</option>
                                                        <option selected = "paymentModeIsInsurance" value="1">Insurance</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="row">
                                        <div class="input-field col s10 offset-s1">                                    
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Gender</label>					
                                                    <select class="form-control auto-select select2" v-model="gender" name="gender" required>
                                                        <option disabled value="">Select Gender</option>
                                                        <option selected = "genderIsMale" value="0">Male</option>
                                                        <option selected = "genderIsFemale" value="1">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s10 offset-s1">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Reason for Visit</label>	
                                                    <select type="text" class="form-control" name="reason"
                                                    v-model="reason" required>
                                                        <option disabled value = "">Reason For Visit</option>
                                                        @foreach ($reasons as $reason)
                                                        <option selected = "service_id == {{$reason->id}}" value="{{$reason->id}}">{{$reason->name}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="modal-close btn waves-effect waves-light red" style="margin-right: 20px ; margin-left: 20px" type="button">{{__('messages.common.close')}}
                            <i class="material-icons right">close</i>
                        </button>
                        <button class="btn waves-effect waves-light submit" type="submit" name="action" @click="editTokenDetails(token)">{{__('messages.common.submit')}}
                            <i class="material-icons right">send</i>
                        </button>
                    </div>
    
                </div>
    
                <div id="select-transfer" class="modal modal-fixed-footer">
                    <div class="modal-content">
                        <div class="offset-s1"></div>
    
                        <form action="" method="" class="form-horizontal">
                            <h4 class="header center" style="font-size:34px;text-transform:none;">
                                {{__('messages.call_page.call transfer')}}
                            </h4>
                            <div class="divider col s12"></div>
                            <div class="row" style="padding-top: 7px;">
                                <div class="row">
                                    <div class="input-field col s10 offset-s1">
                                        <div class="input-field col s12">
                                            <select v-model="service_id">
                                                <option value="" disabled selected>{{__('messages.call_page.choose your service')}}</option>
                                                <option v-for="service in services" :value="service.id">@{{service.name}}</option>
                                            </select>
                                            <label>{{__('messages.call_page.service')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    <div class="col s10 offset-s1">
                                        <div class="input-field col s12">
                                            <select v-model="counter_id">
                                                <option value="" disabled selected>{{__('messages.call_page.choose your counter')}}</option>
                                                <option v-for="counter in counters" :value="counter.id">@{{counter.name}}</option>
                                            </select>
                                            <label>{{__('messages.call_page.counter')}}</label>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="modal-close btn waves-effect waves-light red" style="margin-right: 20px ; margin-left: 20px" type="button">{{__('messages.common.close')}}
                            <i class="material-icons right">close</i>
                        </button>
                        <button class="btn waves-effect waves-light submit" type="submit" name="action" :disabled="!service_id || !counter_id" @click="setAgent(token)">{{__('messages.common.submit')}}
                            <i class="material-icons right">send</i>
                        </button>
                    </div>
    
                </div>
            </div>
         </div>
     </div>
</div>
</div>
@endsection
@section('js')
<script src="{{asset('app-assets/chart.js')}}"></script>
<script>
   var ChartData = {
      labels: [
         "{{__('messages.common.queue')}}",
         "{{__('messages.common.served')}}",
         "{{__('messages.common.noshow')}}",
         "{{__('messages.common.serving')}}",
      ],
      datasets: [{
         label: 'Today',
         backgroundColor: [
            'rgb(0, 188, 212)',
            'rgb(255, 82, 82)',
            'rgb(255, 167, 38)',
            'rgb(102, 187, 106)',
         ],
         data: ['{{$today_queue}}', '{{$today_served}}', '{{$today_noshow}}', '{{$today_serving}}'],
         hoverOffset: 4,
      }]
   };

   const LineChartData = {
      labels: ['00:00', '6:00', '12:00', '18:00', '24:00'],
      datasets: [{
            label: "{{__('messages.dashboard.today')}}",
            data: [@foreach($chart_data['today'] as $indx => $data)
               @if($indx == 0) <?php echo "'$data'"; ?>
               @else <?php echo ", '$data'"; ?>
               @endif
               @endforeach
            ],
            borderColor: ['rgb(54, 162, 235)', ],
            backgroundColor: ['rgb(255,255,255)'],
            pointStyle: 'circle',
            pointRadius: 10,
            pointHoverRadius: 15,

         },
         {
            label: "{{__('messages.dashboard.yesterday')}}",
            data: [@foreach($chart_data['yesterday'] as $indx => $data)
               @if($indx == 0) <?php echo "'$data'"; ?>
               @else <?php echo ", '$data'"; ?>
               @endif
               @endforeach
            ],
            borderColor: ['rgb(255, 99, 132)', ],
            backgroundColor: ['rgb(255,255,255)'],
            pointStyle: 'circle',
            pointRadius: 10,
            pointHoverRadius: 15
         }
      ]
   };

   $(document).ready(function () {
      $('.datepicker').datepicker({
         format: 'yyyy-mm-dd'
      });


      var c2 = document.getElementById("chart2").getContext("2d");
      window.myBar2 = new Chart(c2, {
         type: 'line',
         data: LineChartData,
         options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
               y: {
                  min: 0,
                  title: {
                     display: true,
                     text: "{{__('messages.dashboard.number of tokens')}}"
                  },
                  ticks: {
                     stepSize: 1,
                  }
               },
               x: {
                  title: {
                     display: true,
                     text: "{{__('messages.dashboard.time')}}"
                  },
               }
            },
         }
      });

   });
</script>
@endsection
@section('b-js')
<script>
    window.JLToken = {
        current_lang: '{{\App::currentLocale()}}',
        call_page_loaded: true,
        set_service_counter_url: "{{ route('set-service-and-counter') }}",
        get_token_for_call_url: "{{ route('get-token-for-call') }}",
        get_queue_data: "{{ route('get-queue-data')}}",
        get_services_url: "{{ route('get-token-for-call') }}",
        isServiceSelected: "{{session()->has('service')}}",
        get_services_and_counters_url: "{{route('get-services-counters')}}",
        transfer_url: "{{route('transfer-token')}}",
        edit_token_url: "{{route('edit-token')}}",
        get_called_tokens_url: "{{route('get-called-tokens')}}",
        call_next_url: "{{route('call_next')}}",
        serve_token_url: "{{route('serve_token')}}",
        noshow_token_url: "{{route('noshow-token')}}",
        hold_token_url: "{{route('hold-token')}}",
        break_token_url: "{{route('break-token')}}",
        recall_token_url: "{{route('recall_token')}}",
        services: JSON.parse('{!!$services->toJson()!!}'),
        users: JSON.parse('{!!$users->toJson()!!}'),
        counters: JSON.parse('{!!$counters->toJson()!!}'),
        selectedCounter: "{{session()->has('counter')}}" ? JSON.parse('{!!session()->get("counter")!!}') : null,
        selectedService: "{{session()->has('service')}}" ? JSON.parse('{!!session()->get("service")!!}') : null,
        get_tokens_from_file: "{{ asset('storage/app/public/tokens_for_callpage.json') }}",
        date: "{{ $date }}",
        served_lang: "{{__('messages.call_page.served')}}",
        noshow_lang: "{{__('messages.call_page.noshow')}}",
        hold_lang: "{{__('messages.call_page.hold')}}",
        transfer_lang: "{{__('messages.call_page.transfer')}}",
        edit_token_lang: "{{__('messages.call_page.edit_token')}}",
        called_lang: "{{__('messages.call_page.called')}}",
        recalled_lang: "{{__('messages.call_page.recalled')}}",
        no_ticket_lang: "{{__('messages.call_page.no ticket available')}}",
        alredy_used_lang: "{{__('messages.call_page.already used')}}",
        alredy_selected_lang: "{{__('messages.call_page.already selected')}}",
        error_lang: "{{__('messages.call_page.something went wrong')}}",
        alredy_called_lang: "{{__('messages.call_page.already called')}}",

    }
</script>
@endsection