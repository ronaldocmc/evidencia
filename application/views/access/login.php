<body>
  <div class="container-fluid">
    <div class="row">
      
      <div class="area-acesso">
        <div class="modal-dialog modal-login">
          <div class="shadow-lg bg-white rounded modal-content">
            <div class="modal-header">
              <div class="avatar" id="avatar_id_large">
                <img src= "<?=base_url('/assets/images/icon/logo-mini.png')?>" alt="Avatar">
              </div>
              <h4 class="modal-title">Área de Acesso</h4>
              <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
            </div>
            <div class="modal-body">
                <div class="form-group">
                  <input type="text" class="form-control press_enter" autocomplete="false" name="login" placeholder="Digite seu e-mail" required="required" id="username">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control press_enter" autocomplete="false" name="password" placeholder="Digite sua senha" required="required" id="password">
                </div>
                <div class="form-group">
                  <button type="button" class="btn btn-primary btn-lg btn-block login-btn" id="btn-login-large" onclick="verify_data()" name="post">Entrar</button>
                </div>
            </div>
            <div class="modal-footer">
              <a href="#" data-toggle="modal" data-target="#password_recover">Esqueci minha senha</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <img src="">
    </div>
  </div>
</div>
</body>
</html>



<!-- Modal recuperação de senha -->

<!-- Modal -->
<div class="modal fade" id="password_recover" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

<div class="modal-dialog modal-login">
        <div class="shadow-lg p-3 mb-5 bg-white rounded modal-content">
          <div class="modal-header">
            <div class="avatar" id="avatar_id_large">
              <img src= "<?=base_url('/assets/img/password.png')?>" alt="Avatar">
            </div>
            <h4 class="modal-title">Recuperar senha</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
          </div>
          <div class="modal-body">
              <div class="form-group">
                <input type="text" class="form-control" name="email_recover" placeholder="Digite seu email" required="required" id="email_recover">
              </div>
              <div class="form-group">
                <button type="button" class="btn btn-primary btn-lg btn-block"  onclick="verify_email()" name="post">Enviar</button>
              </div>
          </div>
        </div>
      </div>
</div>

<!--Importando a biblioteca Jquery na versão 3.3.1 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Importando DataTable -->
<script src="<?= 'assets/vendor/datatables/datatables.min.js' ?>"></script>
<script src="<?= 'assets/vendor/datatables/dataTables.bootstrap4.min.js' ?>"></script>


<!--Importando arquivo js do bootstrap-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

<!--Importando a biblioteca noty JavaScript para notificação do usuário-->
<script src="<?php echo base_url('assets/js/jquery.noty.packaged.min.js') ?>"></script>

<!--Importando funções Jquery responsáveis pela verificação de dados e execução da entrada do usuário-->
<script src="<?php echo base_url('assets/js/access/access.js') ?>"></script>

<script src="<?php echo base_url('assets/js/response_messages.js') ?>"></script>
<script src="<?php echo base_url('assets/js/utils.js') ?>"></script>