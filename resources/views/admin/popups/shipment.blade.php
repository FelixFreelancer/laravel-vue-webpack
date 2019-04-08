<!--begin::Users Info Modal-->
<div class="modal fade" id="shipmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Shipment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" id="id">
                        <input type="text" id="tracking_no" placeholder="Tracking Number" class="form-control">
                          <p class="help-block tracking_no_error"></p>
                    </div>
                    <div class="col-md-12">
                      <button type="button" class="btn btn-primary"  id="submit_shipment"></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
