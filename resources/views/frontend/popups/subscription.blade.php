<div class="modal fade" id="subscription" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                    <h3 class="lu__title">Subscribe To Get Our Latest Update</h3>
                    <p class="lu_note error_submission"></p>
                    <p class="lu__note">Subscribe for our newsletter and get notified when we publish new articles for
                        free!</p>
                    <form class="lu__input" id="subscriptionForm">
                        <input type="text" name="email" id="email" placeholder="Enter Your Email Address">
                        <p class="error subscription_error"></p>
                        <button type="submit" class="button button--accent">Sunscribe Now</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
