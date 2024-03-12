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
            <div class="col s12 m6 l6">
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
            <div class="col s12 m6 l6">
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
        </div>
    </div>

    <div class="row">
        <div class="col m6 s12">
           <div class=card-panel>
              <span style="line-height:0;font-size:22px;font-weight:300">{{__('messages.dashboard.ticket_details')}}</span>
              <div class=divider style="margin:15px 0 10px 0"></div>
              <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
								<label class="control-label">Ticket No.</label>						
								<input type="text" class="form-control" name="ticket_no" id = "ticket_no" value="" readonly>
							</div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Reason for Visit</label>
                                <input type="text" class="form-control" name="reason" id = "reason" value="" readonly>		
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
								<label class="control-label">Phone Number</label>						
								<input type="text" class="form-control" name="phone" id = "phone" value="" readonly>
							</div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
								<label class="control-label">Comment</label>	
                                <input type="text" class="form-control" name="comment" id = "comment" value="">					
							</div>
                        </div>

                        <div class="row">
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
                                @{{token.ticket_id}}
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
                        <div style="font-size:20px; color: red;" v-if="token && isCalled && slaReached">@{{time_after_called}}</div>
                        <div style="font-size:20px; color: black;" v-if="token && isCalled && !slaReached">@{{time_after_called}}</div>
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
                        <div class="input-field col s6" v-if="!holdClicked">
                            <button class="btn waves-effect waves-light center submit call-bt" type="submit" name="action" @click="holdToken(token.id)" :disabled="holdClicked || !isCalled" style="min-width:165px;">{{__('messages.call_page.hold')}}
                                <i class="material-icons right">pause</i>
                            </button>
                        </div>
                        <div class="input-field col s6" v-if="holdClicked">
                            <button class="btn waves-effect waves-light center submit call-bt" type="submit" name="action" @click="continueToken(token.id)" :disabled="!holdClicked" style="min-width:165px;">{{__('messages.call_page.continue')}}
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                        <div class="input-field col s6">
                            <button id="next_call" class="btn waves-effect waves-light center call-bt submit " type="submit" style="min-width:165px;" @click="openSetTransferModal(token.id)" name="action"  :disabled="isCalled || recallClicked || breakClicked">{{__('messages.call_page.call')}}
                                <i class="material-icons right">call</i>
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
                                                    <label class="control-label">Reason for Visit</label>						
                                                    <input type="text" class="form-control" name="reason" v-model = "reason" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                                    <label class="control-label">Comment</label>						
                                                    <input type="text" class="form-control" name="comment" v-model = "comment" value="">
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
@section('b-js')
<script>
    window.JLToken = {
        current_lang: '{{\App::currentLocale()}}',
        call_page_loaded: true,
        set_service_counter_url: "{{ route('set-service-and-counter') }}",
        get_token_for_call_url: "{{ route('get-token-for-call') }}",
        isServiceSelected: "{{session()->has('service')}}",
        transfer_url: "{{route('transfer-token')}}",
        edit_token_url: "{{route('edit-token')}}",
        get_called_tokens_url: "{{route('get-called-tokens')}}",
        call_next_url: "{{route('call_next')}}",
        serve_token_url: "{{route('serve_token')}}",
        noshow_token_url: "{{route('noshow-token')}}",
        hold_token_url: "{{route('hold-token')}}",
        break_token_url: "{{route('break-token')}}",
        recall_token_url: "{{route('recall_token')}}",
        selectedCounter: 'Manager',
        selectedService: 'Manager',
        selectedTicket: ('{!!$ticket != null ? $ticket->toJson() : null!!}'),
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