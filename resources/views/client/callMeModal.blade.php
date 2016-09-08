<!--Create modal-->
<div id="call-me-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="min-height: 50px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 text-center" style="font-size: 18px; margin-bottom: 10px">
                        Ваш номер
                    </div>
                    <div class="col-xs-12" style="margin-bottom: 15px">
                        <div class="form-group text-center" style="width: 80%; margin: auto; margin-bottom: 10px;">
                            <input id="call-me-phone-input" style="font-size: 17px;" type="text" placeholder="Ваш номер телефона" class="form-control text-center">
                        </div>
                    </div>
                    <div class="col-xs-12 text-center" style="font-size: 18px">
                        Вопрос по
                    </div>
                    <div class="col-xs-12 text-center">
                        <div class="btn-group nav-btn-group" role="group">
                            <div class="btn-group" role="group">
                                <a id="btn-call-me-procedures" type="button" class="btn btn-default {{$tab == 'procedures' ? 'active' : ''}}">Процедурам</a>
                            </div>
                            <div class="btn-group" role="group">
                                <a id="btn-call-me-sport" type="button" class="btn btn-default {{$tab == 'sport' ? 'active' : ''}}">Спорту</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-xs-12">
                        <div id="modal-call-me-message" class="col-xs-12 text-center" style="display: none; font-size: 20px;">
                        </div>
                        <div id="modal-call-me-loading" class="col-xs-12 text-center">
                            <img src="/images/loading_spinner.gif" style="max-height: 50px;">
                        </div>
                        <button id="btn-call-me" class="btn btn-default modal-btn call-btn" style="width: 100%; font-size: 17px; display: none;">
                            <span class="glyphicon glyphicon-earphone"></span> Перезвоните Мне
                        </button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->