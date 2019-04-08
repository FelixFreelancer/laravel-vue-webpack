<template lang="pug">
section
  form(id="profileForm" @submit.prevent="save")
    .details-wrap
      ul.ul-reset.personal-details.details-ul
        li
          h3.dashboard__content__title Personal Details
        li.gpf-input
          label First name
          span {{userData.first_name}}
        li.gpf-input
          label Last name
          span {{userData.last_name}}
        li.gpf-input
          label Gender
          span {{gender(userData.gender)}}
        li.gpf-input
          label Company Name
          span {{userData.company_name}}
        li.gpf-input
          label Email
          input(type="text" disabled v-model="userData.email")
      ul.ul-reset.contact-details.details-ul
        li
          h3.dashboard__content__title Contact Details
        li.gpf-input
          label Address
          input(type="text" placeholder="78th green grand garden" name="cd_address" v-model="userData.cd_address")
        li.gpf-input
          label City
          input(type="text" placeholder="Rio" name="cd_city" v-model="userData.cd_city")
        li.gpf-input.one-half-wrap
          .one-half-input
            label State of province
            input(type="text" placeholder="Rio" name="cd_state" v-model="userData.cd_state")
          .one-half-input
            label Postal Code
            input(type="text" placeholder="878897" name="cd_postalcode" v-model="userData.cd_postalcode")
        li.gpf-input
          label Country
          select.ss-select.ss-select--country(name="cd_country" v-model="userData.cd_country")
            option Select
            option(v-for="item in country" :value="item.value") {{item.name}}
        li.gpf-input.one-half-wrap
          .one-half-input
            label Country Code
            select.ss-select.ss-select--country(name="cd_country_code" v-model="userData.cd_country_code")
              option Select
              option(v-for="item in countryCode" :value="item.value") {{item.name}}
          .one-half-input
            label Phone Number
              .label-status {{userData.cd_phone_verified_at ?'Verified':'Un-verified'}}
            input(type="text" placeholder="878897" name="cd_phone" v-model="userData.cd_phone")
            button.button--primary.button--verify(
              id="sendOTPButton"
              type="button"
              v-if="!userData.cd_phone_verified_at && !OTPSent"
              @click="sendOTP") Verify now
        li(v-if="OTPSent")
          .authen
            .authen__code.code
              div
                h3.already__title Validate your one time pass-code
              div
                p.already__desc.success.otpViaCall(v-if="status == 1") We have sent a seven digit code via SMS to your registered phone number, please input the code below.It expires in {{ otp_timer}}
                p.already__desc.success.otpViaCall(v-if="status == 2") Haven’t received the SMS yet? You can 
                  a(class="confirmMobile" @click="sendOtpViaCall" href="#" title="Our system will call your phone with the one time passcode") Try the voice option.
                p.already__desc.success.otpViaCall(v-if="status == 3") Didn’t received the call? Unfortunately, we couldn’t verify your phone number automatically, please 
                  a(href="https://www.globalparcelforward.com/contact-us" class="js_contact_us") contact a support representative here.
                p.already__desc.success.otpViaCall(v-if="status == 4") We are calling your registered phone number with your seven digit code. Please input the code below.
            .code__input
              input(
                v-for="n in 7"
                type="text"
                name="otp_digit"
                maxlength="1"
                v-model="otp[n]"
                @keypress="$event.target.nextElementSibling.focus()"
                @keyup.delete="$event.target.previousElementSibling?$event.target.previousElementSibling.focus():''")
              input(
                type="hidden"
                name="otp")
              .code__btn
                button.button.button--accent(type="button" @click="verifyOTP" id="verifyOTPButton")
                  img(src="~Images/arrow-right.svg")
                button.button.button--text(@click="resetOTP" type="button") Reset
    .dashboard__footer.all-center
      button.button--accent.button--update(type="submit") Update Personal Details
      p.update-note Changes to email address or phone number will require re-verification
</template>

<script>
import m_common from "Mixins/common";

export default {
  components: {},
  props: [],
  mixins: [m_common],
  data() {
    return {
      userData: {},
      country: [],
      countryCode: [],
      OTPSent: false,
      otp: [],
      otp_site : [],
      otp_timer : '',
      status : 1
    };
  },
  created() {
    this.getUser();
  },
  mounted() {},
  computed: {
  },
  methods: {
    save($event) {
      var _this = this;
      _this.formSubmit({
        url: `${_this.apiUrl()}/profile`,
        validator: _this.$validator,
        formId: $event.target.id,
        success(data) {
          _this.OTPSent = false;
          _this.otp = [];
          _this.userData = data;
        }
      });
    },
    resetOTP() {
      this.otp = [];
    },
    verifyOTP($event) {
      var _this = this;
      _this.apiPost({
        url: `${_this.apiUrl()}/verify-otp`,
        data: {
          otp: `${_this.otp[1]}${_this.otp[2]}${_this.otp[3]}${_this.otp[4]}${_this.otp[5]}${_this.otp[6]}${_this.otp[7]}`
        },
        buttonId: "verifyOTPButton",
        success(data) {
          _this.OTPSent = false;
          _this.otp = [];
          _this.getUser();
        }
      });
    },
    sendOTP($event) {
      var _this = this;
      _this.apiPost({
        url: `${_this.apiUrl()}/send-otp`,
        buttonId: $event.target.id,
        success(data) {
          _this.OTPSent = true;
          var minute = 60 * parseInt(_this.otp_site.otpExpires);
          _this.startTimer(minute);
          _this.updateHtml();
        }
      });
    },
    updateHtml() {
      var _this = this;
      var otpExpiresInSeconds = (_this.otp_site.otpExpires*60) * 1000;
      var callExpiresInSeconds = (_this.otp_site.callExpires*60) * 1000;

      setTimeout(function () {
        _this.otp_timer='';
        _this.status=2;
          document.getElementsByClassName("already__desc")[0].classList.remove('success');
      }, otpExpiresInSeconds);

      setTimeout(function () {
        _this.otp_timer='';
        _this.status=3;
          document.getElementsByClassName("already__desc")[0].classList.remove('success');
            }, callExpiresInSeconds);
    },
    sendOtpViaCall() {
      var _this = this;
      _this.$http.post(_this.apiUrl()+'/users/'+_this.userData.id+'/otp-call').then(
        response => {
          if (response.data.status) {
            _this.status=4;
          }
          else {
            _this.$notify({
              group: "app",
              title: "Something Went Wrong! Please Try Again.",
              type: "error"
            });
          }
        }
      );
    },
    startTimer(duration) {

      var _this = this;
      var timer = duration, minutes, seconds;
      setInterval(function () {
        if (--timer < 0) {}
        else{
          minutes = parseInt(timer / 60, 10)
          seconds = parseInt(timer % 60, 10);

          minutes = minutes < 10 ? "0" + minutes : minutes;
          seconds = seconds < 10 ? "0" + seconds : seconds;
          _this.otp_timer=minutes + ":" + seconds;
        }
      }, 1000);
    },
    gender(id) {
      switch (id) {
        case 1:
          return "Male";
          break;
        case 2:
          return "Female";
          break;
        default:
          return "";
      }
    },
    getUser() {
      var _this = this;
      _this.apiGet({
        url: `${_this.apiUrl()}/profile`,
        success(data) {
          _this.userData = data.user;
          _this.country = data.country;
          _this.countryCode = data.country_code;
          _this.otp_site = data.site;
        }
      });
    }
  }
};
</script>

<style lang="css">
</style>
