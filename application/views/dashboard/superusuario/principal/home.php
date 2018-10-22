<!-- MAIN CONTENT--> 
            <div class="main-content"> 
                <div class="section__content section__content--p30"> 
                    <div class="container-fluid"> 
                        <div class="row"> 
                            <div class="col-md-12"> 
                                <div class="overview-wrap"> 
                                    <h2 class="title-1">visão geral </h2> 
                                </div> 
                            </div> 
                        </div> 
                        <div class="row m-t-25 pb-5"> 
                            <div class="col-sm-6 col-lg-3 pt-sm-4"> 
                                <div class="overview-item overview-item--c1"> 
                                    <div class="overview__inner"> 
                                        <div class="overview-box clearfix"> 
                                            <div class="icon col-12 pl-0"> 
                                                <i class="fas fa-building"></i> 
                                            </div> 
                                            <div class="text pt-1"> 
                                                <h2>2</h2> 
                                                <span>organizações ativas</span> 
                                            </div> 
                                        </div> 
                                        <div class="overview-chart"> 
                                            <canvas id="widgetChart1"></canvas> 
                                        </div> 
                                    </div> 
                                </div> 
                            </div> 
                            <div class="col-sm-6 col-lg-3 pt-sm-4"> 
                                <div class="overview-item overview-item--c2"> 
                                    <div class="overview__inner"> 
                                        <div class="overview-box clearfix"> 
                                            <div class="icon col-12 pl-0"> 
                                                <i class="fas fa-search"></i> 
                                            </div> 
                                            <div class="text pt-1"> 
                                                <h2>200</h2> 
                                                <span>ordens de serviço</span> 
                                            </div> 
                                        </div> 
                                        <div class="overview-chart"> 
                                            <canvas id="widgetChart2"></canvas> 
                                        </div> 
                                    </div> 
                                </div> 
                            </div> 
                            <div class="col-sm-6 col-lg-3 pt-sm-4"> 
                                <div class="overview-item overview-item--c3"> 
                                    <div class="overview__inner"> 
                                        <div class="overview-box clearfix"> 
                                            <div class="icon col-12 pl-0"> 
                                                <i class="zmdi zmdi-calendar-note"></i> 
                                            </div> 
                                            <div class="text pt-1"> 
                                                <h2>50</h2> 
                                                <span>ordens esta semana</span> 
                                            </div> 
                                        </div> 
                                        <div class="overview-chart"> 
                                            <canvas id="widgetChart3"></canvas> 
                                        </div> 
                                    </div> 
                                </div> 
                            </div> 
                            <div class="col-sm-6 col-lg-3 pt-sm-4"> 
                                <div class="overview-item overview-item--c4"> 
                                    <div class="overview__inner"> 
                                        <div class="overview-box clearfix"> 
                                            <div class="icon col-12 pl-0"> 
                                                <i class="far fa-user"></i> 
                                            </div> 
                                            <div class="text pt-1"> 
                                                <h2>20</h2> 
                                                <span>usuários online</span> 
                                            </div> 
                                        </div> 
                                    </div> 
                                </div> 
                            </div> 
                        </div>   
                        <div class="row"> 
                            <div class="col-lg-6 d-flex"> 
                                <div class="col-12 au-card recent-report"> 
                                    <div class="au-card-inner"> 
                                        <h3 class="title-2">ordens de serviço recentes</h3> 
                                        <div class="chart-info"> 
                                            <div class="chart-info__left"> 
                                                <div class="chart-note"> 
                                                    <span class="dot dot--blue"></span> 
                                                    <span>prudenco</span> 
                                                </div> 
                                                <div class="chart-note"> 
                                                    <span class="dot dot--green"></span> 
                                                    <span>outra empresa</span> 
                                                </div> 
                                            </div> 
                                            <div class="chart-info__right"> 
                                                <div class="chart-statis"> 
                                                    <span class="index incre"> 
                                                        <i class="zmdi zmdi-long-arrow-up"></i>25%</span> 
                                                    <span class="label">prudenco</span> 
                                                </div> 
                                                <div class="chart-statis mr-0"> 
                                                    <span class="index decre"> 
                                                        <i class="zmdi zmdi-long-arrow-down"></i>10%</span> 
                                                    <span class="label">outra empresa</span> 
                                                </div> 
                                            </div> 
                                        </div> 
                                        <div class="recent-report__chart"> 
                                            <canvas id="recent-rep-chart"></canvas> 
                                        </div> 
                                    </div> 
                                </div> 
                            </div> 
                            <div class="col-lg-6 d-flex"> 
                                <div class="col-12 au-card chart-percent-card"> 
                                    <div class="au-card-inner"> 
                                        <h3 class="title-2 tm-b-5">tipos de ordem de serviço</h3> 
                                        <div class="row no-gutters"> 
                                            <div class="col-xl-6"> 
                                                <div class="chart-note-wrap"> 
                                                    <div class="chart-note mr-0 d-block"> 
                                                        <span class="dot dot--blue"></span> 
                                                        <span>limpeza</span> 
                                                    </div> 
                                                    <div class="chart-note mr-0 d-block"> 
                                                        <span class="dot dot--red"></span> 
                                                        <span>coleta</span> 
                                                    </div> 
                                                </div> 
                                            </div> 
                                            <div class="col-xl-6"> 
                                                <div class="percent-chart"> 
                                                    <canvas id="percent-chart"></canvas> 
                                                </div> 
                                            </div> 
                                        </div> 
                                    </div> 
                                </div> 
                            </div> 
                        </div> 
                        <div class="row pb-5"> 
                            <div class="col-lg-8 d-flex "> 
                                <div class="au-card d-flex flex-column"> 
                                <h2 class="title-1 m-b-25">últimas ordens de serviço</h2> 
                                <div class="table-responsive table--no-card m-b-40"> 
                                    <table class="table table-borderless table-striped table-earning"> 
                                        <thead> 
                                            <tr> 
                                                <th>data</th> 
                                                <th>endereço</th> 
                                                <th>funcionário</th> 
                                                <th>prioridade</th> 
                                            </tr> 
                                        </thead> 
                                        <tbody> 
                                            <tr> 
                                                <td>2018-09-29 05:57</td> 
                                                <td>Rua Exemplo, 100 - Centro, Presidente Prudente - SP</td> 
                                                <td>funcionário</td> 
                                                <td>urgente</td> 
                                            </tr> 
                                            <tr> 
                                                <td>2018-09-29 05:57</td> 
                                                <td>Rua Exemplo, 100 - Centro, Presidente Prudente - SP</td> 
                                                <td>funcionário</td> 
                                                <td>urgente</td> 
                                            </tr> 
                                            <tr> 
                                                <td>2018-09-29 05:57</td> 
                                                <td>Rua Exemplo, 100 - Centro, Presidente Prudente - SP</td> 
                                                <td>funcionário</td> 
                                                <td>urgente</td> 
                                            </tr> 
                                            <tr> 
                                                <td>2018-09-29 05:57</td> 
                                                <td>Rua Exemplo, 100 - Centro, Presidente Prudente - SP</td> 
                                                <td>funcionário</td> 
                                                <td>urgente</td> 
                                            </tr> 
                                            <tr> 
                                                <td>2018-09-29 05:57</td> 
                                                <td>Rua Exemplo, 100 - Centro, Presidente Prudente - SP</td> 
                                                <td>funcionário</td> 
                                                <td>urgente</td> 
                                            </tr> 
                                            <tr> 
                                                <td>2018-09-29 05:57</td> 
                                                <td>Rua Exemplo, 100 - Centro, Presidente Prudente - SP</td> 
                                                <td>funcionário</td> 
                                                <td>urgente</td> 
                                            </tr> 
                                            <tr> 
                                                <td>2018-09-29 05:57</td> 
                                                <td>Rua Exemplo, 100 - Centro, Presidente Prudente - SP</td> 
                                                <td>funcionário</td> 
                                                <td>urgente</td> 
                                            </tr> 
                                        </tbody> 
                                    </table> 
                                </div> 
                            </div> 
                            </div> 
                            <div class="col-lg-4 d-flex"> 
                                <div class="au-card d-flex flex-column"> 
                                    <h2 class="title-1 m-b-25">Top Bairros</h2> 
                                    <div class="au-card au-card--bg-blue au-card-top-countries m-b-40"> 
                                        <div class="au-card-inner"> 
                                            <div class="table-responsive"> 
                                                <table class="table table-top-countries"> 
                                                    <tbody> 
                                                        <tr> 
                                                            <td>Centro </td> 
                                                            <td class="text-right">40</td> 
                                                        </tr> 
                                                        <tr> 
                                                            <td>Ana Jacinta</td> 
                                                            <td class="text-right">30</td> 
                                                        </tr> 
                                                        <tr> 
                                                            <td>Jardim Bongiovani</td> 
                                                            <td class="text-right">25</td> 
                                                        </tr> 
                                                    </tbody> 
                                                </table> 
                                            </div> 
                                        </div> 
                                    </div> 
                                </div> 
                            </div> 
                        </div> 
                        <div class="row"> 
                            <div class="col-lg-6 d-flex"> 
                                <div class="au-card col-12 au-card--no-shadow au-card--no-pad m-b-40"> 
                                    <div class="au-card-title" style="background-image:url('images/bg-title-01.jpg');"> 
                                        <div class="bg-overlay bg-overlay--blue"></div> 
                                        <h3> 
                                            <i class="zmdi zmdi-account-calendar"></i>25 de Junho de 2018</h3> 
                                        <button class="au-btn-plus"> 
                                            <i class="zmdi zmdi-plus"></i> 
                                        </button> 
                                    </div> 
                                    <div class="au-task js-list-load"> 
                                        <div class="au-task__title"> 
                                            <p>Tarefas de Ronaldo</p> 
                                        </div> 
                                        <div class="au-task-list js-scrollbar3"> 
                                            <div class="au-task__item au-task__item--danger"> 
                                                <div class="au-task__item-inner"> 
                                                    <h5 class="task"> 
                                                        <a href="#">Sprint Review</a> 
                                                    </h5> 
                                                    <span class="time">8:00</span> 
                                                </div> 
                                            </div> 
                                            <div class="au-task__item au-task__item--warning"> 
                                                <div class="au-task__item-inner"> 
                                                    <h5 class="task"> 
                                                        <a href="#">Reunião com equipe da Prudenco</a> 
                                                    </h5> 
                                                    <span class="time">9:00</span> 
                                                </div> 
                                            </div> 
                                            <div class="au-task__item au-task__item--primary"> 
                                                <div class="au-task__item-inner"> 
                                                    <h5 class="task"> 
                                                        <a href="#">Primeira aula</a> 
                                                    </h5> 
                                                    <span class="time">14:00</span> 
                                                </div> 
                                            </div> 
                                            <div class="au-task__item au-task__item--success"> 
                                                <div class="au-task__item-inner"> 
                                                    <h5 class="task"> 
                                                        <a href="#">Segunda Aula</a> 
                                                    </h5> 
                                                    <span class="time">16:00</span> 
                                                </div> 
                                            </div> 
                                            <div class="au-task__item au-task__item--danger js-load-item"> 
                                                <div class="au-task__item-inner"> 
                                                    <h5 class="task"> 
                                                        <a href="#">Parecer Capacitação SisCursinhos</a> 
                                                    </h5> 
                                                    <span class="time">17:40</span> 
                                                </div> 
                                            </div> 
                                            <div class="au-task__item au-task__item--warning js-load-item"> 
                                                <div class="au-task__item-inner"> 
                                                    <h5 class="task"> 
                                                        <a href="#">Corrigir Provas</a> 
                                                    </h5> 
                                                    <span class="time">19:00</span> 
                                                </div> 
                                            </div> 
                                        </div> 
                                        <div class="au-task__footer"> 
                                            <button class="au-btn au-btn-load js-load-btn">carregar mais</button> 
                                        </div> 
                                    </div> 
                                </div> 
                            </div> 
                            <div class="col-lg-6 d-flex"> 
                                <div class="au-card col-12 au-card--no-shadow au-card--no-pad m-b-40"> 
                                    <div class="au-card-title" style="background-image:url('images/bg-title-02.jpg');"> 
                                        <div class="bg-overlay bg-overlay--blue"></div> 
                                        <h3> 
                                            <i class="zmdi zmdi-comment-text"></i>Novas Mensagens</h3> 
                                        <button class="au-btn-plus"> 
                                            <i class="zmdi zmdi-plus"></i> 
                                        </button> 
                                    </div> 
                                    <div class="au-inbox-wrap js-inbox-wrap"> 
                                        <div class="au-message js-list-load"> 
                                            <div class="au-message__noti"> 
                                                <p>Você tem 
                                                    <span>2</span> 
                                                    novas mensagens 
                                                </p> 
                                            </div> 
                                            <div class="au-message-list"> 
                                                <div class="au-message__item unread"> 
                                                    <div class="au-message__item-inner"> 
                                                        <div class="au-message__item-text"> 
                                                            <div class="avatar-wrap"> 
                                                                <div class="avatar"> 
                                                                    <img src="<?php echo base_url('assets/images/icon/avatar-02.jpg')?>" alt="John Smith"> 
                                                                </div> 
                                                            </div> 
                                                            <div class="text"> 
                                                                <h5 class="name">John Smith</h5> 
                                                                <p>Lorem ipsum dolor sit amet</p> 
                                                            </div> 
                                                        </div> 
                                                        <div class="au-message__item-time"> 
                                                            <span>há 10 minutos</span> 
                                                        </div> 
                                                    </div> 
                                                </div> 
                                                <div class="au-message__item unread"> 
                                                    <div class="au-message__item-inner"> 
                                                        <div class="au-message__item-text"> 
                                                            <div class="avatar-wrap online"> 
                                                                <div class="avatar"> 
                                                                    <img src="<?php echo base_url('assets/images/icon/avatar-03.jpg')?>" alt="Nicholas Martinez"> 
                                                                </div> 
                                                            </div> 
                                                            <div class="text"> 
                                                                <h5 class="name">Nicholas Martinez</h5> 
                                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod 
                                                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim</p> 
                                                            </div> 
                                                        </div> 
                                                        <div class="au-message__item-time"> 
                                                            <span>11:00</span> 
                                                        </div> 
                                                    </div> 
                                                </div> 
                                                <div class="au-message__item"> 
                                                    <div class="au-message__item-inner"> 
                                                        <div class="au-message__item-text"> 
                                                            <div class="avatar-wrap online"> 
                                                                <div class="avatar"> 
                                                                    <img src="<?php echo base_url('assets/images/icon/avatar-04.jpg')?>" alt="Michelle Sims"> 
                                                                </div> 
                                                            </div> 
                                                            <div class="text"> 
                                                                <h5 class="name">Michelle Sims</h5> 
                                                                <p>Lorem ipsum dolor sit amet</p> 
                                                            </div> 
                                                        </div> 
                                                        <div class="au-message__item-time"> 
                                                            <span>Ontem</span> 
                                                        </div> 
                                                    </div> 
                                                </div> 
                                                <div class="au-message__item"> 
                                                    <div class="au-message__item-inner"> 
                                                        <div class="au-message__item-text"> 
                                                            <div class="avatar-wrap"> 
                                                                <div class="avatar"> 
                                                                    <img src="<?php echo base_url('assets/images/icon/avatar-05.jpg')?>" alt="Michelle Sims"> 
                                                                </div> 
                                                            </div> 
                                                            <div class="text"> 
                                                                <h5 class="name">Michelle Sims</h5> 
                                                                <p>Purus feugiat finibus</p> 
                                                            </div> 
                                                        </div> 
                                                        <div class="au-message__item-time"> 
                                                            <span>Sábado</span> 
                                                        </div> 
                                                    </div> 
                                                </div> 
                                                <div class="au-message__item js-load-item"> 
                                                    <div class="au-message__item-inner"> 
                                                        <div class="au-message__item-text"> 
                                                            <div class="avatar-wrap online"> 
                                                                <div class="avatar"> 
                                                                    <img src="<?php echo base_url('assets/images/icon/avatar-04.jpg')?>" alt="Michelle Sims"> 
                                                                </div> 
                                                            </div> 
                                                            <div class="text"> 
                                                                <h5 class="name">Michelle Sims</h5> 
                                                                <p>Lorem ipsum dolor sit amet</p> 
                                                            </div> 
                                                        </div> 
                                                        <div class="au-message__item-time"> 
                                                            <span>Quarta-feira</span> 
                                                        </div> 
                                                    </div> 
                                                </div> 
                                                <div class="au-message__item js-load-item"> 
                                                    <div class="au-message__item-inner"> 
                                                        <div class="au-message__item-text"> 
                                                            <div class="avatar-wrap"> 
                                                                <div class="avatar"> 
                                                                    <img src="<?php echo base_url('assets/images/icon/avatar-05.jpg')?>" alt="Michelle Sims"> 
                                                                </div> 
                                                            </div> 
                                                            <div class="text"> 
                                                                <h5 class="name">Michelle Sims</h5> 
                                                                <p>Purus feugiat finibus</p> 
                                                            </div> 
                                                        </div> 
                                                        <div class="au-message__item-time"> 
                                                            <span>18/06/2018</span> 
                                                        </div> 
                                                    </div> 
                                                </div> 
                                            </div> 
                                            <div class="au-message__footer"> 
                                                <button class="au-btn au-btn-load js-load-btn">carregar mais</button> 
                                            </div> 
                                        </div> 
                                        <div class="au-chat"> 
                                            <div class="au-chat__title"> 
                                                <div class="au-chat-info"> 
                                                    <div class="avatar-wrap online"> 
                                                        <div class="avatar avatar--small"> 
                                                            <img src="<?php echo base_url('assets/images/icon/avatar-02.jpg')?>" alt="John Smith"> 
                                                        </div> 
                                                    </div> 
                                                    <span class="nick"> 
                                                        <a href="#">John Smith</a> 
                                                    </span> 
                                                </div> 
                                            </div> 
                                            <div class="au-chat__content"> 
                                                <div class="recei-mess-wrap"> 
                                                    <span class="mess-time">há 10 minutos</span> 
                                                    <div class="recei-mess__inner"> 
                                                        <div class="avatar avatar--tiny"> 
                                                            <img src="<?php echo base_url('assets/images/icon/avatar-02.jpg')?>" alt="John Smith"> 
                                                        </div> 
                                                        <div class="recei-mess-list"> 
                                                            <div class="recei-mess">Lorem ipsum dolor sit amet, consectetur adipiscing elit non iaculis</div> 
                                                            <div class="recei-mess">Donec tempor, sapien ac viverra</div> 
                                                        </div> 
                                                    </div> 
                                                </div> 
                                                <div class="send-mess-wrap"> 
                                                    <span class="mess-time">30 Segundos atrás</span> 
                                                    <div class="send-mess__inner"> 
                                                        <div class="send-mess-list"> 
                                                            <div class="send-mess">Lorem ipsum dolor sit amet, consectetur adipiscing elit non iaculis</div> 
                                                        </div> 
                                                    </div> 
                                                </div> 
                                            </div> 
                                            <div class="au-chat-textfield"> 
                                                <form class="au-form-icon"> 
                                                    <input class="au-input au-input--full au-input--h65" type="text" placeholder="Type a message"> 
                                                    <button class="au-input-icon"> 
                                                        <i class="zmdi zmdi-camera"></i> 
                                                    </button> 
                                                </form> 
                                            </div> 
                                        </div> 
                                    </div> 
                                </div> 
                            </div> 
                        </div> 
                        <div class="row"> 
                            <div class="col-md-12"> 
                                <div class="copyright"> 
                                    <p>Copyright © 2018 Colorlib. All rights reserved. Template by <a href="https://colorlib.com">Colorlib</a>.</p> 
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div> 
            <!-- END MAIN CONTENT--> 
            <!-- END PAGE CONTAINER-->