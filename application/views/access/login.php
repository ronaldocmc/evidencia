<body>
  <div class="container-fluid">
    <div class="row">
      <!--Elemento de menu e informações, lateral esquerda-->
      <div class="col-lg-8 pl-0 col-md-12 col-sm-12 geral">
        <nav class="navbar navbar-expand-lg navbar-light">
          <a class="navbar-brand" href="#">Evidência</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon light"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
              <!-- <a class="nav-item nav-link active" href="#">Conheça-nos <span class="sr-only">(current)</span></a>
              <a class="nav-item nav-link" href="#">Preços</a>
              <a class="nav-item nav-link" href="#">Contrate-nos</a> -->
            </div>
          </div>
        </nav>
        <div class="geral content">
          <section class=" col-md-12 col-sm-12 info">
            <div class="col-md-12 manchete">
              <div class="col-md-8 col-lg-10 titulo">
                <h2>Gerenci&shy;amento com prati&shy;cidade e inovação</h2>
              </div>
              <div class="col-md-12 sobretitulo">
                <h3> Cidades inteligentes é uma realidade</h3>
              </div>
            </div>
            <br>
            <div class="col-md-12 col-sm-12 sobre">
              <p>O Sistema Evidência permite o gerenciamento dos serviços oferecendo funcionalidades como mapeamento de casos, gestão de ordens de serviço, designação de responsabilidades, fiscalização da execução por fotos, visualização de dados departamentais e operacionais, tudo em dois ambientes de execução, web e mobile. </p>
              <button type ="button" class="mt-4 btn btn-light d-block d-sm-block d-md-block d-lg-none abrir-acesso pull-left" data-toggle="modal" data-target="#modal-acesso">Login</button>
              <div class="modal fade" id="modal-acesso" >
                <div class="modal-dialog modal-login">
                  <div class="modal-content">
                    <div class="modal-header">
                      <div class="avatar" id="avatar_id">
                        <img src= "<?=base_url('/assets/img/avatar.png')?>" alt="Avatar">
                      </div>
                      <h4 class="modal-title">Área de Acesso</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <input type="text" class="form-control" autocomplete="false" name="username" placeholder="Digite seu e-mail" required="required" id="username_modal">
                        </div>
                        <div class="form-group">
                          <input type="password" class="form-control" autocomplete="false" name="password" placeholder="Digite sua senha" required="required" id="email_modal">
                        </div>
                        <!-- <div class="form-group">
                         <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                       </div> -->
                       <div class="form-group">
                        <button type="button" class="btn btn-primary btn-lg btn-block login-btn" id="btn-login" onclick="verify_data()" name="post">Entrar</button>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <a href="#" data-toggle="modal" data-target="#password_recover" id="esqueci-minha-senha">Esqueci minha senha</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <footer class="col-md-12 col-sm-12 rodape">
          <p>Companhia Prudentina de Desenvolvimento &copy</p>
        </footer>
      </div>
    </div>
    <!--Elemento de área de acesso lateral direita -->
    <div class="col-lg-3 d-none d-sm-none d-md-none d-lg-block area-acesso">
      <div class="modal-dialog modal-login">
        <div class="shadow-lg p-3 mb-5 bg-white rounded modal-content">
          <div class="modal-header">
            <div class="avatar" id="avatar_id_large">
              <img src= "<?=base_url('/assets/img/avatar.png')?>" alt="Avatar">
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

<!--Importando arquivo js do bootstrap-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

<!--Importando a biblioteca noty JavaScript para notificação do usuário-->
<script src="<?php echo base_url('assets/js/jquery.noty.packaged.min.js') ?>"></script>

<!--Importando o Recaptcha para verificação de usuário real-->
<!-- <script src="https://www.google.com/recaptcha/api.js?render=<?php echo RECAPTCHA_SITE_KEY ?>"></script> -->

<!--Importando funções Jquery responsáveis pela verificação de dados e execução da entrada do usuário-->
<script src="<?php echo base_url('assets/js/access/access.js') ?>"></script>