<div class="modal fade" id="verified_successful" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div class="lu">
              <div class="lu__icon"><img src="{!! asset('img/lu-icon.png') !!}"/></div>
              <h3 class="lu__title js__title">{!! $title or 'User Verification' !!}</h3>
              <p class="lu__note js__message">{!! $message or '' !!}</p>
              @if(isset($button))
                <a href="{!! $link or '#nogo' !!}">
                  <button class="button button--accent js__button">{!! $button !!}</button>
                </a>
              @endif
            </div>
          </div>
          <div class="modal-footer"></div>
        </div>
      </div>
    </div>
