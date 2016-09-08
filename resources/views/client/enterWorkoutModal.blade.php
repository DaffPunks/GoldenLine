<!--Enter modal-->
<div id="enter-modal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{$gymnames[$gymtype]}}</h4>
            </div>
            <div class="modal-body-schedule">
                <div class="col-xs-12">
                    <time>
                    </time>
                    <day>
                    </day>
                </div>
                <div class="col-xs-12">
                    <div class="name-wrapper">
                        <name>
                        </name>
                        <div class="text-center">
                            Тренер
                        </div>
                        <coach>
                        </coach>
                    </div>
                </div>
                <div class="col-xs-12">
                    <free>
                    </free>
                </div>
            </div>
            <div class="modal-footer">

                <div id="edit-loading" class="col-xs-12 text-center" style="max-height: 50px; display: none;">
                    <img src="/images/loading_spinner.gif" style="max-height: inherit">
                </div>
                <button id="btn-main-enter-modal" type="button" class="btn btn-default modal-btn"></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->