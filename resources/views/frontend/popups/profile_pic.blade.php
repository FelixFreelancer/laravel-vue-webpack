<div class="modal fade" id="profile_pic" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
			  <h4 class="modal-title">Upload Profile Picture</h4>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div class="lu">
				<div class="ss-crop ss-crop--cover">
                    <div class="ss-crop__wrap">
                        <div class="ss-crop__preview">
                            <div id="image-preview"></div>
                        </div>
                        <input type="file"  accept="image/x-png,image/gif,image/jpeg"  name="" value="" id="image" class="ss-crop__file">
                        <label for="image" class="ss-crop__select">
                            <img src="{!! asset('img/profile-pic.svg') !!}" alt="">
                            <span>Select</span>
                        </label>
                    </div>
                    <div class="">
                        <a href="javascript:;" class="ss-crop__reset">Cancel</a>
                    </div>
					<div class="form__error"></div>
                </div>
				<a href="">
				  <button class="button button--accent updateprofile_picture">Update Picture</button>
				</a>
			</div>
          </div>
          <div class="modal-footer"></div>
        </div>
      </div>
    </div>
