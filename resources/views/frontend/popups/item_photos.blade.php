<div class="modal fade" id="itemPhotos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(auth()->user()->plan_type=='free')
                    Please upgrade your account for accessing this feature by clicking <a href="#nogo">here</a>.
                @elseif(auth()->user()->plan_type=='paid')
                    <div id="itemPhotosSliderMain" class="slider-for">
   
                    </div>
                    <div id="itemPhotosSlider" class="slider-nav">

                    </div>
                @endif
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
