<div class="modal fade" id="upgradeAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(auth()->user()->plan_type=='free')
                    Please upgrade your account for accessing this feature by clicking <a href="{!! url()->route('users.subscription') !!}">here</a>.
                @endif
                {{--<div class="lu">
                    <div class="lu__icon"><img src="img/lu-icon.png"/></div>
                    <h3 class="lu__title">Subscribe To Get Our Latest Updates</h3>
                    <p class="lu__note">Subscribe for our newsletter and get notified when we publish new articles for
                        free!</p>
                    <form class="lu__input">
                        <input type="text" placeholder="Enter your email address"/>
                        <button class="button button--accent">Subscribe Now</button>
                    </form>
                </div>--}}
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
