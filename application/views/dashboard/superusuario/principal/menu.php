
<header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="index.html">
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
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                            <i class="fas fa-edit"></i>Gerenciamento</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="<?=base_url('superusuario')?>">Superusuários</a>
                                </li>
                                <li>
                                    <a href="<?=base_url('organizacao')?>">Filiais</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="#">
                    <img src="<?php echo base_url('assets/images/icon/logo.png') ?>" alt="Evidência" />
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li class="active has-sub">
                            <a class="js-arrow" href="#">
                            <i class="fas fa-edit"></i>Gerenciamento</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                    <a href="<?=base_url('superusuario')?>">Superusuários</a>
                                </li>
                                <li>
                                    <a href="<?=base_url('organizacao')?>">Filiais</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div style="margin-top: 50px !important;">
                        <a href="#">
                            <img class="logo_parceiros" src="<?php echo base_url('assets/images/icon/maps-logo.png') ?>" alt="Evidência" />
                        </a>
                    </div>
                    <div style="margin-top: 15px !important;">
                        <a href="#">
                            <img  class="logo_parceiros" src="<?php echo base_url('assets/images/icon/certified microsoft.png') ?>" alt="Evidência" />
                        </a>
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
                                        <div class="image">
                                            <img src="<?php echo $this->session->user['image_user_min'] ?>" alt="John Doe" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#"><?php echo $this->session->user['name_user']; ?></a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="<?php echo $this->session->user['image_user_min'] ?>" alt="John Doe" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#"><?php echo $this->session->user['name_user']; ?></a>
                                                    </h5>
                                                    <span class="email"><?php echo $this->session->user['email_user']; ?></span>
                                                </div>
                                            </div>
                                            <!-- <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="<?php echo base_url('pessoa/profile') ?>">
                                                        <i class="zmdi zmdi-account"></i>Conta
                                                    </a>
                                                </div>
                                            </div> -->
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
            <!-- HEADER DESKTOP