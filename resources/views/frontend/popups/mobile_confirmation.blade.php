<div class="modal fade" id="mobile_confirmation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  </div>
          <div class="modal-body">
            <div class="lu">
              <div class="lu__icon"><img src="{!! asset('img/lu-icon.png') !!}"/></div>
              <h3 class="lu__title js__title">Mobile Number Confirmation</h3>
              <p class="lu__note js__message">We will call <span class="js__mobile_number"></span> with your one time passcode.</p>
              <p class="jq__call_exception"></p>
                <a href="#nogo">
                  <button class="button button--accent sendOtpViaCall">Accept</button>
                </a>
                <a href="#nogo">
                  <button class="button button--accent js__change_number">Change Number</button>
                </a>
            </div>
          </div>
          <div class="modal-footer"></div>
        </div>
      </div>
    </div>
