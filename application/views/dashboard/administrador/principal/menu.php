<!-- HEADER MOBILE-->
<header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="<?php echo base_url('dashboard/funcionario_administrador') ?>">
                            <img src="<?php echo base_url('assets/images/icon/logo.png') ?>" alt="Evidência" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li>
                            <a href="<?= base_url('dashboard/funcionario_administrador') ?>">
                                <i class="fas fa-home"></i>Início</a>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                            <i class="fas fa-edit"></i>Gerenciamento</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                    <li class="departamento-menu d-none">
                                        <a href="<?php echo base_url('departamento'); ?>">Departamentos</a>
                                    </li>
                                    <li class="setor-menu d-none">
                                        <a href="<?php echo base_url('setor'); ?>">Setor</a>
                                    </li>
                                    <li class="funcionario-menu d-none">
                                        <a href="<?php echo base_url('funcionario'); ?>">Funcionários</a>
                                    </li>
                                    <li class="funcao-menu d-none">
                                        <a href="<?php echo base_url('funcao'); ?>">Funções</a>
                                    </li>
                                    <li class="servico-menu d-none">
                                        <a href="<?php echo base_url('servico'); ?>">Serviços</a>
                                    </li>
                                    <li class="prioridade-menu d-none">
                                        <a href="<?php echo base_url('prioridade'); ?>">Prioridades</a>
                                    </li>
                                    <li class="situacoes-menu d-none">
                                        <a href="<?php echo base_url('situacao'); ?>">Situações</a>
                                    </li>
                                        <?php if(SHOW_HIDE_MENU): ?>
                                        <li class="tipo-servico-menu d-none">
                                            <a href="<?php echo base_url('tipo_servico'); ?>">Tipos de Serviços</a>
                                        </li>
                                        <?php endif; ?>

                                <li class="ordem_servico-menu d-none">
                                    <a href="<?php echo base_url('ordem_servico'); ?>">Ordens de Serviço</a>
                                </li>
                            </ul>
                        </li>
                        <li class="mapa-menu d-none">
                            <a href="<?php echo base_url('mapa') ?>">
                            <i class="fas fa-map-marker-alt"></i>Mapa</a>
                        </li>
                        <li class="relatorio-menu d-none">
                            <a class="js-arrow" href="#">
                            <i class="fas fa-clipboard-list"></i>Relatórios</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="<?= base_url('relatorio') ?>">Listar Relatórios</a>
                                </li>
                                <?php if(SHOW_HIDE_MENU): ?>
                                <li>
                                    <a href="<?= base_url('relatorio/relatorios_gerais') ?>">Relatórios Gerais</a>
                                </li>
                                <li>
                                    <a href="<?= base_url('relatorio/relatorio_especifico') ?>">Relatório Específico</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <li class="has-sub organizacao-menu d-none">
                            <a class="js-arrow" href="#">
                            <i class="fas fa-gear"></i>Opções</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="<?php echo base_url('organizacao/editar'); ?>">Editar Informações</a>
                                </li>
                            </ul>
                        </li>
                        <?php if ($this->session->user['is_superusuario']): ?>
                        <li>
                            <a class="js-arrow"href="<?php echo base_url('dashboard/funcionario_administrador') ?>">
                            <i class="fas fa-sign-out-alt"></i>Voltar ao menu</a>
                        </li>

                        <?php endif;?>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="<?php echo base_url('dashboard/funcionario_administrador') ?>">
                    <img src="<?php echo base_url('assets/images/icon/logo.png') ?>" alt="Evidência" />
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li>
                            <a href="<?php echo base_url('dashboard/funcionario_administrador') ?>">
                                <i class="fas fa-home"></i>Início</a>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                            <i class="fas fa-edit"></i>Gerenciamento</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                    <li class="departamento-menu d-none">
                                        <a href="<?php echo base_url('departamento'); ?>">Departamentos</a>
                                    </li>
                                    <li class="setor-menu d-none">
                                        <a href="<?php echo base_url('setor'); ?>">Setor</a>
                                    </li>
                                    <li class="funcionario-menu d-none">
                                        <a href="<?php echo base_url('funcionario'); ?>">Funcionários</a>
                                    </li>
                                    <li class="funcao-menu d-none">
                                        <a href="<?php echo base_url('funcao'); ?>">Funções</a>
                                    </li>
                                    <li class="servico-menu d-none">
                                        <a href="<?php echo base_url('servico'); ?>">Serviços</a>
                                    </li>
                                    <li class="prioridade-menu d-none">
                                        <a href="<?php echo base_url('prioridade'); ?>">Prioridades</a>
                                    </li>
                                    <li class="situacao-menu d-none">
                                        <a href="<?php echo base_url('situacao'); ?>">Situações</a>
                                    </li>
                                    <?php if(SHOW_HIDE_MENU): ?>
                                        <li class="tipo-servico-menu d-none">
                                            <a href="<?php echo base_url('tipo_servico'); ?>">Tipos de Serviços</a>
                                        </li>
                                    <?php endif; ?>
                                <li class="ordem_servico-menu d-none">
                                    <a href="<?php echo base_url('Ordem_Servico'); ?>">Ordens de Serviço</a>
                                </li>
                            </ul>
                        </li>
                        <li class="mapa-menu d-none">
                            <a href="<?php echo base_url('mapa') ?>">
                            <i class="fas fa-map-marker-alt"></i>Mapa</a>
                        </li>
                        <li class="relatorio-menu d-none">
                            <a class="js-arrow" href="#">
                            <i class="fas fa-clipboard-list"></i>Relatórios</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                 <li>
                                    <a href="<?= base_url('relatorio') ?>">Listar Relatórios</a>
                                </li>
                                <?php if(SHOW_HIDE_MENU): ?>
                                    <li>
                                        <a href="<?= base_url('relatorio/relatorios_gerais') ?>">Relatórios Gerais</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url('relatorio/relatorio_especifico') ?>">Relatório Específico</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li class="has-sub organizacao-menu d-none">
                            <a class="js-arrow" href="#">
                            <i class="fas fa-gear"></i>Opções</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                    <a href="<?php echo base_url('organizacao/editar'); ?>">Editar Informações</a>
                                </li>
                            </ul>
                        </li>
                        <?php if ($this->session->user['is_superusuario']): ?>
                        <li>
                            <a href="<?php echo base_url('dashboard/superusuario') ?>">
                            <i class="fas fa-sign-out-alt"></i>Voltar ao menu</a>
                        </li>

                        <?php endif;?>
                    </ul>
                     <div style="margin-top: 30px !important;">
                        <a href="#">
                            <img class="logo_parceiros" src="<?php echo base_url('assets/images/icon/maps-logo.png') ?>" alt="Evidência" />
                        </a>
                    </div>
                    <div style="margin-top: 15px !important;">
                        <a href="#">
                            <img  class="logo_parceiros" src="<?php echo base_url('assets/images/icon/certified microsoft.png') ?>" alt="Evidência" />
                        </a>
                    </div>
                    <div>
                        <small> Evidência - v<?= VERSION ?> </small>   
                    </div>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

                <!-- PAGE CONTAINER-->
                <div class="page-container">

                <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap float-right">
                            <div class="header-button">
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image" id="avatar">
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#"><?php echo $this->session->user['name_user']; ?></a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#" id="image-a">
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#"><?php echo $this->session->user['name_user']; ?></a>
                                                    </h5>
                                                    <span class="email"><?php echo $this->session->user['email_user']; ?></span>
                                                    <p><?php echo $this->session->user['name_organizacao']; ?></p> 
                                                </div>
                                            </div>
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="<?php echo base_url('minha_conta') ?>">
                                                        <i class="zmdi zmdi-account"></i>Conta</a>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="<?= base_url('access/quit') ?>">
                                                    <i class="zmdi zmdi-power"></i>Sair</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->

            <script>
                function imageNotFound()
                {
                    console.log('image not found');
                    console.log(this);
                    this.src = "<?= base_url('assets/img/default.png') ?>";
                }

                function loadAvatarImage(){
                    let image = new Image();
                    image.alt = "<?= $this->session->user['name_user']; ?>";
                    image.onerror = imageNotFound;
                    image.src = "<?= $this->session->user['image_user_min'] ?>";

                    return image;
                }

                function loadImage(src){
                    let image = new Image();
                    image.onerror = imageNotFound;
                    image.src = src;

                    return image;
                }

                let image = loadAvatarImage();
                let newImage = loadAvatarImage()

                document.getElementById('avatar').appendChild(image);

                document.getElementById('image-a').appendChild(newImage);
            </script>