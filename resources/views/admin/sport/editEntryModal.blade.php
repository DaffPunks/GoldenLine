<div id="modal-edit-entry" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
                <div class="text-center" style="width: 100%; margin-top: 30px; margin-bottom: 0;">
                    <div class="btn-group" style="width: 65%;">
                        <input id="edit-workout-name-input" class="form-control text-center" style="font-size: 18px; width: 100%;" placeholder="Название тренировки" type="text">
                        <ul id="edit-workout-name-input-ul" class="dropdown-menu col-xs-12" style="cursor: pointer">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-body-schedule row">
                <div class="col-sm-12">
                    <div class="col-xs-12 text-center">
                        <div id="modal-edit-coach-loading" class="col-xs-12 text-center">
                            <p class="text-center">Тренер</p>
                            <img src="/images/loading_spinner.gif" style="max-height: 50px;">
                        </div>
                        <div id="modal-edit-coach-dropdown" class="btn-group dropdown" style="margin-bottom: 20px; min-width: 50%;">
                            <button type="button" class="btn dropdown-toggle dropdown-entry" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Тренер <span class="caret"></span>
                            </button>
                            <ul id="modal-edit-coach-dropdown-list" class="dropdown-menu">
                            </ul>
                        </div>
                        <div id="modal-edit-checkbox-coach-replaced" class="checkbox col-xs-12 text-center">
                            <label style="font-size: 17px;"><input type="checkbox" id="checkbox-is-coach-replaced">Тренер заменен</label>
                        </div>
                    </div>
                    <div class="col-xs-12 edit-section-title" style="margin-bottom: 10px;">
                        Клиенты
                    </div>
                    <ul class="col-xs-12 list-group" style="padding-left: inherit">
                        <li class="list-group-item">
                            <form class="form-inline row">
                                <div class="btn-group col-xs-6">
                                    <input id="edit-client-input" placeholder="Имя/Телефон клиента" name="client-phone" class="form-control" style="width: 100%" value="" autocomplete="off">
                                    <ul id="edit-client-ul" class="dropdown-menu" style="cursor: pointer">
                                    </ul>
                                </div>

                                <div class="btn-group col-xs-4">
                                    {{--<input type="text" placeholder="№ Абонемента" class="form-control"  style="display:none">--}}
                                    <input id="edit-subscription-input" type="text" placeholder="№ Абонемента" name="subscription-number" class="form-control" value="" autocomplete="off">
                                    <ul id="edit-subscription-ul" class="dropdown-menu" style="cursor: pointer">
                                    </ul>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <button id="btn-add-subscription" class="btn btn-default" type="button" style="float: right;" disabled="disabled"><span class="glyphicon glyphicon-plus"></span></button>
                                </div>
                            </form>
                        </li>
                    </ul>
                    <div id="free-places-label" class="col-xs-12 text-center" style="margin-bottom: 16px;"></div>
                    <div id="edit-loading" class="col-xs-12 text-center">
                        <img src="/images/loading_spinner.gif" style="max-height: 100px">
                    </div>
                    <div id="unconfirmed-clients-label" class="col-xs-12 edit-section-title">Заявки на тренировку:</div>
                    <ul id="unconfirmed-clients-list" class="col-xs-12 list-group" style="padding-left: inherit">
                    </ul>
                    <div id="edit-client-label" class="col-xs-12 edit-section-title">Утвержденные клиенты:</div>
                    <ul id="edit-client-list" class="col-xs-12 list-group" style="padding-left: inherit">
                    </ul>

                </div>
            </div>
            <div class="modal-footer">
                <div id="modal-edit-loading" class="col-xs-12 text-center">
                    <img src="/images/loading_spinner.gif" style="max-height: 50px;">
                </div>
                <div id="modal-edit-buttons" class="row" style="display: none;">
                    <div class="checkbox col-xs-12 text-center">
                        <label style="font-size: 17px;"><input type="checkbox" id="checkbox-update-subsequent">Обновить последующие тренировки в этой временной ячейке</label>
                    </div>
                    <div class="col-xs-10">
                        <button id="btn-edit-entry" type="button" class="btn btn-default modal-btn">Сохранить</button>
                    </div>
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-default modal-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: auto; float: right">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a id="btn-delete-entry" role="button">УДАЛИТЬ</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->