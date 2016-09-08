<div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="changePasswordLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header" style="min-height: 50px">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 class="text-center">Смена пароля</h3>
      </div>
      <div class="modal-body">

        <div class="form-group">
          <input type="password" class="form-control" placeholder="Старый пароль" name="oldPassword">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" data-toggle="popover" data-placement="right" data-content="Пароль должен содержать не менее 6 символов" placeholder="Новый пароль" name="newPassword">
        </div>
        <div class="form-group" style="margin-bottom: 0;">
          <input type="password" class="form-control" placeholder="Повторите новый пароль" name="newPasswordRepeated">
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div id="error" class="alert alert-danger" style="margin-top: 15px; margin-bottom: 0; display: none" role="alert">
          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
          <span id="errorMsg"></span>
        </div>
      </div>
      <div class="modal-footer">
        <div id="modal-change-password-loading" class="col-xs-12 text-center" style="display: none;">
          <img src="/images/loading_spinner.gif" style="max-height: 50px;">
        </div>
        <button id="btnChangePass" type="button" class="btn btn-default" style="width: 100%; font-size:18px;">Изменить</button>
      </div>
    </div>
  </div>
</div>