const { forEach } = require("lodash");

if (document.getElementById("call-page")) {
  var app = {
    data() {
      return {
        token: null,
        selected_service: window.JLToken?.selectedService,
        selected_agent: window.JLToken?.selectedAgent,
        selected_counter: window.JLToken?.selectedCounter,
        selected_ticket: window.JLToken?.selectedTicket,
        service_id: null,
        counter_id: null,
        dataLoaded: false,
        queueData: false,
        genderIsMale: false,
        today_noshow: 0,
        slaReached: false,
        today_queue: 0,
        today_served: 0,
        today_serving: 0,
        genderIsFemale: false,
        paymentModeIsCash: false,
        paymentModeIsInsurance: false,
        payment_mode: null,
        services: window.JLToken?.services,
        counters: window.JLToken?.counters,
        users: window.JLToken?.users,
        isCalled: false,
        callNextClicked: false,
        servedClicked: false,
        noshowClicked: false,
        holdClicked: false,
        breakClicked: false,
        playClicked: false,
        recallClicked: false,
        called_tokens: [],
        tokens_for_next_to_call: [],
        count: "0",
        queueDataCount: 0,
        callDataCount: 1,
        countHeld: "0",
        time_after_called: null,
        timer_interval: null,
        start_interval: null,
        current_lang: window.JLToken?.current_lang,
        font_size_smaller:
          window.JLToken?.current_lang == "gb" ||
          window.JLToken?.current_lang == "sa"
            ? false
            : true,
      };
    },
    methods: {
      serveTicket() {
        if (this.selected_ticket) {
          this.selected_ticket = JSON.parse(this.selected_ticket);
          console.log(this.selected_ticket);
          this.token = this.selected_ticket;
          this.isCalled = true;
          document.getElementById("phone").value = this.selected_ticket.phone;
          document.getElementById("ticket_no").value =
            this.selected_ticket.ticket_id;
          document.getElementById("comment").value =
            this.selected_ticket.comment;
          document.getElementById("reason").value =
            this.selected_ticket.reason_for_visit;
          this.setDataForTimer(this.token);
        }
      },
      editTokenDetails(token) {
        this.closeEditTokenModal();
        this.enableLoader();
        const data = {
          gender: this.gender,
          phone: this.phone,
          token: this.token,
          payment_mode: this.payment_mode,
          reason: this.reason,
        };
        axios
          .post(window.JLToken.edit_token_url, data)
          .then((res) => {
            if (
              res.data &&
              res.data.status_code &&
              res.data.status_code == 500
            ) {
              this.disableLoader();
              this.closeEditTokenModal();
              M.toast({
                html: window?.JLToken?.alredy_selected_lang,
                classes: "toast-error",
              });
            } else {
              if (this.service != res.data.reason) {
                this.token = null;
                this.tokens_for_next_to_call = res.data.tokens_for_call;
                this.called_tokens = res.data.called_tokens;
                this.isCalled = false;
                document.getElementById("phone").value = "";
                document.getElementById("payment_mode").value = "";
                document.getElementById("gender").value = "";
                document.getElementById("reason").value = "";
              } else {
                document.getElementById("phone").value = res.data.phone;
                document.getElementById("payment_mode").value =
                  res.data.payment_mode;
                document.getElementById("gender").value = res.data.gender;
                document.getElementById("reason").value = res.data.reason;
              }
              this.disableLoader();
              M.toast({ html: window?.JLToken?.edit_token_lang });
            }
          })
          .catch((err) => {
            this.disableLoader();
            M.toast({
              html: window?.JLToken?.error_lang,
              classes: "toast-error",
            });
          });
      },
      openEditTokenModal() {
        $(".modal").modal({
          dismissible: false,
        });
        $("#edit-token").modal("open");
      },
      closeEditTokenModal() {
        $("#edit-token").modal("close");
      },
      callNext() {
        //this.enableLoader();
        this.callNextClicked = true;
        this.holdClicked = false;
        const data = {
          service_id: this.selected_service.id,
          counter_id: this.selected_counter.id,
          by_id: false,
        };
        axios
          .post(window.JLToken.call_next_url, data)
          .then((res) => {
            if (res.data) {
              if (res.data.no_token_found && res.data.no_token_found == true) {
                //this.disableLoader();
                //M.toast({ html: window?.JLToken?.no_ticket_lang, timeRemaining: 20 });
              } else if (
                res.data &&
                res.data.status_code &&
                res.data.status_code == 500
              ) {
                this.isCalled = false;
                this.callNextClicked = false;
                //this.disableLoader();
                //M.toast({ html: window?.JLToken?.error_lang, classes: "toast-error" });
              } else {
                this.tokens_for_next_to_call =
                  this.tokens_for_next_to_call.filter(
                    (element) =>
                      element.id != this.tokens_for_next_to_call[0].id
                  );
                this.called_tokens.unshift(res.data);
                this.token = res.data;
                this.slaReached = false;
                document.getElementById("phone").value = res.data.queue.phone;
                document.getElementById("payment_mode").value =
                  res.data.queue.payment_mode;
                document.getElementById("gender").value = res.data.queue.gender;
                document.getElementById("reason").value = res.data.service.name;
                this.setDataForTimer(this.token);
                this.isCalled = true;
                //this.disableLoader();
                //M.toast({ html: window?.JLToken?.called_lang });
              }
              this.callNextClicked = false;
            }
          })
          .catch((err) => {
            this.isCalled = false;
            this.callNextClicked = false;
            //this.disableLoader();
            //M.toast({ html: window?.JLToken?.error_lang, classes: "toast-error" });
          });
      },
      queueDetails() {
        axios
          .get(window.JLToken.get_queue_data)
          .then((res) => {
            if (res.data) {
              if (res.data.status_code == 500) {
                this.queueData = false;
              } else {
                this.queueData = true;
                this.today_noshow = res.data.today_noshow;
                this.today_queue = res.data.today_queue;
                this.today_served = res.data.today_served;
                this.today_serving = res.data.today_serving;
              }
            }
          })
          .catch((err) => {
            this.queueData = false;
          });
      },

      serveToken(id) {
        this.enableLoader();
        this.servedClicked = true;
        const data = {
          id: id,
        };

        axios
          .post(window.JLToken.serve_token_url, data)
          .then((res) => {
            if (
              res.data &&
              res.data.status_code &&
              res.data.status_code == 500
            ) {
              this.servedClicked = false;
              this.disableLoader();
              M.toast({
                html: window?.JLToken?.error_lang,
                classes: "toast-error",
              });
            } else if (
              res.data &&
              res.data.already_executed &&
              res.data.already_executed == true
            ) {
              this.servedClicked = false;
              this.disableLoader();
              M.toast({
                html: window?.JLToken?.alredy_used_lang,
                classes: "toast-error",
              });
            } else {
              this.isCalled = false;
              this.servedClicked = false;
              localStorage.setItem("count", "1");
              document.getElementById("phone").value = "";
              document.getElementById("ticket_no").value = "";
              document.getElementById("comment").value = "";
              document.getElementById("reason").value = "";
              this.token = null;
              this.selected_ticket = null;
              this.disableLoader();
              M.toast({ html: "Served" });
              //   setTimeout(function () {
              //     this.callNext();
              //   }, 5000);
            }
          })
          .catch((err) => {
            this.servedClicked = false;
            console.log(err);
            this.disableLoader();
            M.toast({
              html: window?.JLToken?.error_lang,
              classes: "toast-error",
            });
          });
      },
      noShowToken(id) {
        this.enableLoader();
        this.noshowClicked = true;
        const data = {
          id: id,
        };
        axios
          .post(window.JLToken.noshow_token_url, data)
          .then((res) => {
            if (
              res.data &&
              res.data.status_code &&
              res.data.status_code == 500
            ) {
              this.disableLoader();
              M.toast({
                html: window?.JLToken?.error_lang,
                classes: "toast-error",
              });
              this.noshowClicked = false;
            } else if (
              res.data &&
              res.data.already_executed &&
              res.data.already_executed == true
            ) {
              this.disableLoader();
              M.toast({
                html: window?.JLToken?.alredy_used_lang,
                classes: "toast-error",
              });
              this.noshowClicked = false;
            } else {
              this.isCalled = false;
              this.servedClicked = false;
              localStorage.setItem("count", "1");
              document.getElementById("phone").value = "";
              document.getElementById("ticket_no").value = "";
              document.getElementById("comment").value = "";
              document.getElementById("reason").value = "";
              this.token = null;
              this.selected_ticket = null;
              this.noshowClicked = false;
              this.disableLoader();
              M.toast({ html: window?.JLToken?.noshow_lang });
            }
          })
          .catch((err) => {
            this.disableLoader();
            console.log(err);
            M.toast({
              html: window?.JLToken?.error_lang,
              classes: "toast-error",
            });
            this.noshowClicked = false;
          });
      },
      holdToken(id) {
        this.holdClicked = true;
        this.countHeld = this.count;
      },
      continueToken(id) {
        this.holdClicked = false;
        this.count = this.countHeld;
      },
      breakToken(id) {
        this.enableLoader();
        if (this.breakClicked == true) {
          this.breakClicked = false;
          this.playClicked = true;
        } else {
          this.breakClicked = true;
          this.playClicked = false;
        }
        const data = {
          call_id: id,
          held_at_time: this.time_after_called,
        };

        axios
          .post(window.JLToken.break_token_url, data)
          .then((res) => {
            if (
              res.data &&
              res.data.status_code &&
              res.data.status_code == 500
            ) {
              this.disableLoader();
              M.toast({
                html: window?.JLToken?.error_lang,
                classes: "toast-error",
              });
            } else if (
              res.data &&
              res.data.already_executed &&
              res.data.already_executed == true
            ) {
              this.disableLoader();
              M.toast({
                html: window?.JLToken?.alredy_used_lang,
                classes: "toast-error",
              });
            } else {
              this.token = res.data;
              this.called_tokens = this.called_tokens.filter(
                (element) => element.id != id
              );
              this.called_tokens.unshift(res.data);
              this.token = res.data;
              if (this.breakClicked == true) {
                this.countHeld = this.count;
              } else {
                this.count = this.countHeld;
              }
              this.disableLoader();
              M.toast({ html: window?.JLToken?.hold_lang });
            }
          })
          .catch((err) => {
            this.disableLoader();
            M.toast({
              html: window?.JLToken?.error_lang,
              classes: "toast-error",
            });
            this.breakClicked = false;
          });
      },
      recallToken(id) {
        this.enableLoader();
        this.recallClicked = true;
        this.holdClicked = false;
        const data = {
          id: id,
        };
        axios
          .post(window.JLToken.recall_token_url, data)
          .then((res) => {
            if (res.data && res.data.status_code == 500) {
              this.recallClicked = false;
              this.isCalled = true;
              this.disableLoader();
              M.toast({
                html: window?.JLToken?.error_lang,
                classes: "toast-error",
              });
            } else {
              localStorage.setItem("count", "1");
              this.selected_ticket = JSON.parse(this.selected_ticket);
              console.log(this.selected_ticket);
              this.token = this.selected_ticket;
              this.isCalled = true;
              document.getElementById("phone").value =
                this.selected_ticket.phone;
              document.getElementById("ticket_no").value =
                this.selected_ticket.ticket_id;
              document.getElementById("comment").value =
                this.selected_ticket.comment;
              document.getElementById("reason").value =
                this.selected_ticket.reason_for_visit;
              this.setDataForTimer(this.token);
              this.recallClicked = false;
              this.isCalled = true;
              this.disableLoader();
              M.toast({ html: window?.JLToken?.recalled_lang });
            }
          })
          .catch((err) => {
            this.recallClicked = false;
            this.isCalled = true;
            console.log(err);
            this.disableLoader();
            M.toast({
              html: window?.JLToken?.error_lang,
              classes: "toast-error",
            });
          });
      },

      enableLoader() {
        $("body").removeClass("loaded");
      },

      disableLoader() {
        $("body").addClass("loaded");
      },

      timer() {
        this.timer_interval = setInterval(() => {
          if (parseInt(this.count) <= 0) {
            clearInterval();
            return;
          }
          this.time_after_called = this.toHHMMSS(this.count);
          this.count = (parseInt(this.count) + 1).toString();
          localStorage.setItem("count", this.count);
        }, 1000);
      },

      toHHMMSS(count) {
        if (this.holdClicked) {
          this.count = this.countHeld;
        }
        if (parseInt(this.count) > 5) {
          this.slaReached = true;
        }
        var sec_num = parseInt(count, 10);
        var hours = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - hours * 3600) / 60);
        var seconds = sec_num - hours * 3600 - minutes * 60;
        if (hours < 10) {
          hours = "0" + hours;
        }
        if (minutes < 10) {
          minutes = "0" + minutes;
        }
        if (seconds < 10) {
          seconds = "0" + seconds;
        }
        var time = hours + ":" + minutes + ":" + seconds;
        return time;
      },

      setDataForTimer(token) {
        if (this.timer_interval) clearInterval(this.timer_interval);
        this.time_after_called = null;
        // this.count = token.counter_time;
        this.count = localStorage.getItem("count");
        // this.count = "1";
        this.timer();
      },

      hideMainMenu() {
        var openLength = $(".collapsible .open").children().length;
        $(".sidenav-main.nav-collapsible, .navbar .nav-collapsible")
          .addClass("nav-collapsed")
          .removeClass("nav-expanded");
        $("#slide-out > li.open > a")
          .parent()
          .addClass("close")
          .removeClass("open");
        setTimeout(function () {
          // Open only if collapsible have the children
          if (openLength > 1) {
            var collapseEl = $(".sidenav-main .collapsible");
            var collapseInstance = M.Collapsible.getInstance(collapseEl);
            collapseInstance.close($(".collapsible .close").index());
          }
        }, 100);
        $(".sidenav-main").removeClass("nav-lock");
        $(".nav-collapsible .navbar-toggler i").text("radio_button_unchecked");
        $(".navbar .nav-collapsible").removeClass("sideNav-lock");
        $("#main").addClass("main-full");
      },
    },
    mounted() {
      this.hideMainMenu();
      console.log(this.count);
      this.serveTicket();
      //open right nav
      document.addEventListener("DOMContentLoaded", function () {
        var elems = document.querySelectorAll(".sidenav");
        var instance = M.Sidenav.init(elems[1], {
          edge: "right",
          draggable: false,
          closeOnClick: true,
        });
        instance.open();
      });

      //show menu on mouse enter
      $(".sidenav-main.nav-collapsible, .navbar .brand-sidebar").mouseenter(
        function () {
          $(".sidenav-main.nav-collapsible, .navbar .nav-collapsible")
            .addClass("nav-expanded")
            .removeClass("nav-collapsed");
          $("#slide-out > li.close > a")
            .parent()
            .addClass("open")
            .removeClass("close");
          setTimeout(function () {
            // Open only if collapsible have the children
            if ($(".collapsible .open").children().length > 1) {
              var collapseEl = $(".sidenav-main .collapsible");
              var collapseInstance = M.Collapsible.getInstance(collapseEl);
              collapseInstance.open($(".collapsible .open").index());
            }
          }, 100);
        }
      );
      this.disableLoader();
    },
  };
  window.jlTokenCallPageApp = Vue.createApp(app).mount("#call-page");
}
