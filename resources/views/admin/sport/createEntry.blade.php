<!--Create modal-->
<div id="createEntryModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="btn-group" style="margin-top: 30px; margin-bottom: 0; width: 100%;">
                    <input id="create-workout-name-input" class="form-control" style="font-size: 17px;" placeholder="Название тренировки" type="text">
                    <ul id="create-workout-name-input-ul" class="dropdown-menu col-xs-12" style="cursor: pointer;">
                    </ul>
                </div>
            </div>
            <div class="modal-body-schedule">
                <div class="row" style="padding-left: 10px; padding-right: 10px">
                    <div class="col-xs-12" style="margin-bottom: 15px;">
                        <div class='input-group date' id='create-datetimepicker-date'>
                            <input id="date" type='text' class="form-control" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                        </div>
                    </div>
                    <div class="col-xs-12" style="margin-bottom: 15px;">
                        <p class="col-xs-6">
                            Начало:
                        </p>
                        <div class='col-xs-6 input-group date' id='create-datetimepicker-start' style="float: left">
                            <input type='text' id="startTime" class="form-control" value="09:00"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                        </div>
                    </div>
                    <div class="col-xs-12" style="margin-bottom: 15px;">
                        <p class="col-xs-6">
                            Конец:
                        </p>
                        <div class='col-xs-6 input-group date' id='create-datetimepicker-end' style="float: left">
                            <input type='text' id="endTime" class="form-control warni" value="10:00"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                        </div>
                    </div>
                    <div class="col-xs-12" style="margin-bottom: 15px;">
                        <div id="modal-create-coach-loading" class="col-xs-12 text-center">
                            <p class="text-center">Тренер</p>
                            <img src="/images/loading_spinner.gif" style="max-height: 50px;">
                        </div>
                        <div id="modal-create-coach-dropdown" class="btn-group dropdown" style="width: 100%">
                            <button type="button" class="btn dropdown-toggle dropdown-entry" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Тренер <span class="caret"></span>
                            </button>
                            <ul id="modal-create-coach-dropdown-list" class="dropdown-menu">
                            </ul>
                        </div>
                    </div>
                    <div class="col-xs-12" style="margin-bottom: 5px;">
                        <div class="checkbox col-xs-12 text-center">
                            <label><input type="checkbox" id="create-checkbox-personal">Персональная тренировка</label>
                        </div>
                        <div class="text-center" style="float: left; padding-top: 2px;">
                            Количество мест:
                        </div>
                        <div class="form-group" style="float: right; text-align: right; width: 60px;">
                            <input id="create-places-count" class="form-control" value="20" type="number" class="form-control" min="1" step="1" data-number-to-fixed="1" data-number-stepfactor="1" >
                        </div>

                    </div>
                    <div class="col-xs-12">
                        <div class="text-center" style="float: left; padding-top: 2px;">
                            Количество недель:
                        </div>
                        <div class="form-group" style="float: right; text-align: right; width: 60px;">
                            <input id="weeks_amount" type="number" class="form-control" value="1" min="1" step="1" data-number-to-fixed="1" data-number-stepfactor="1"  />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div id="create-loading" class="col-xs-12 text-center">
                    <img src="/images/loading_spinner.gif" style="max-height: 50px;">
                </div>
                <button id="btn-create-entry" type="button" class="btn btn-default modal-btn">Создать</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->