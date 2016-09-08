<div id="modal-generate-password" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="changePasswordLabel">
  <div id="modal-generate-password-loading" class="col-xs-12 text-center" style="margin-top: 20%; border-radius: 6px;">
    <img src="/images/loading_spinner.gif" style="max-height: 50px;">
  </div>
  <div id="modal-generate-password-dialog" class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header" style="min-height: 50px">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 id="modalInfo" class="text-center"></h3>
      </div>
      <div class="modal-body">
        {{--<div class="input-group text-center">--}}
          {{--СПЕЦИАЛИСТ--}}
        {{--</div>--}}
        <h4 id="modal-name" class="modal-title">Фамилия </br> Имя </br> Отчество</h4>
        </br>

        <div class="input-group">
          <span class="input-group-addon">Тел.</span>
          <input type="text" class="form-control" placeholder="Телефон (логин)" name="genPassPhone">
        </div>

        <div id="error" class="alert alert-danger" style="margin-top: 15px; margin-bottom: 0; display: none" role="alert">
          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
          <span id="errorMsg"></span>
        </div>

        {{--<div class="input-group text-center">--}}
          {{--КЛИЕНТ--}}
        {{--</div>--}}
      </div>
      <div class="modal-footer">
        <div id="modal-genpass-loading" class="col-xs-12 text-center">
          <img src="/images/loading_spinner.gif" style="max-height: 50px;">
        </div>
        <button id="btnGenPass" type="button" class="btn btn-default text-center" style="width: 100%; font-size:18px; padding-left: 2px; padding-right: 2px;">Сгенерировать новый пароль</button>
        <div id="newpass" class="input-group" style="display: none">
          <span class="input-group-addon">Пароль</span>
          <div class="form-control" id="newpassinput"></div>
        </div>
      </div>
    </div>
  </div>
</div>