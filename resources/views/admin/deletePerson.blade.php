<div id="deletePersonModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Удалить?</h4>
            </div>
            <div class="modal-body-schedule">

                <h4 id="modal-delete-person-body" class="text-center">

                </h4>
            </div>
            <div class="modal-footer">
                <div class="col-sm-12">

                    <div id="delete-person-loading" class="col-xs-12 text-center" style="display:none;">
                        <img src="/images/loading_spinner.gif" style="max-height: 50px;">
                    </div>

                    <button id="btnDeletePerson" type="button" class="btn btn-danger modal-btn" style="float: left">Удалить</button>

                    <button id="btn-delete-person-cancel" type="button" class="btn btn-default modal-btn " data-dismiss="modal">Отменить</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->