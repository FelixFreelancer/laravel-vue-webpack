<div class="modal fade" id="changeMobileNumber" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="lu">
                    <div class="lu__icon"><img src="{!! asset('img/lu-icon.png') !!}"/></div>
                    <h3 class="lu__title">Change Mobile Number</h3>
                    <p class="lu_note error_submission"></p>
                    <!-- <p class="lu__note">Subscribe for our newsletter and get notified when we publish new articles for
                        free!</p> -->
                    <form class="lu__input" id="update_number">
                        <div class="select-wrap">
                        {!!Form::select('cd_country_code', $country_codes, session('location')->iso_code,['placeholder'=>'Country Code *','id'=>'country_code','required'])!!}
                        <span class="cd_country_code_error error_msg"></span>
                        <input type="text" name="cd_phone" placeholder="Enter your mobile number" id="jq__cd_phone" required/>
                        </div>
                        <span class="cd_phone_error error_msg"></span>
                        <input type="hidden" name="user_id" value="{!! $user['id'] !!}">
                        <button type="submit" class="button button--accent">Change Now</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
