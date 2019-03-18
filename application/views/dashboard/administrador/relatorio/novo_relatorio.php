<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"> Painel para Criação de Novo Relatório </h2>
                    </div>
                </div>
            </div>
            <div class="row py-2">
                <div class="col-lg-12">
                    <div class="au-card d-flex flex-column">
                        <form id="submit-form">
                        <h2 class="title-1 m-b-25">

                        Novo Relatório</h2>
                        <div class="">
                            <h5>Filtros</h5><br>
                            <?php if($message): ?>
                                <div class="alert alert-danger">
                                    <?= $message ?>
                                </div>
                            <?php endif; ?>
                            <h5><b>Por data:</b></h5><br>
                            <div class="row">
                                <div class="col-md-6">

                                    De:<input type="date" class="form-control" id="data_inicial" name="data_inicial" required>
                                </div> 
                                <div class="col-md-6">

                                    Até:<input type="date" class="form-control" id="data_final" name="data_final" required>
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <div class="row">
                         <div class="col-md-6">
                            <h5><b>Escolha o(s) setor(es):</b></h5><br>
                            <select class="form-control" id="setor" name="setor[]" multiple>
                                <?php 
                                foreach($setores as $setor):
                                    ?>
                                    <option value="<?= $setor->setor_pk ?>">
                                        <?= $setor->setor_nome ?>
                                    </option>
                                    <?php
                                endforeach;
                                ?>
                            </select>
                            <small>Segure Crtl para selecionar mais de uma opção</small>
                        </div>
                        <div class="col-md-6">
                            <h5><b>Escolha o(s) tipo(s) de serviço(s):</b></h5> <br>
                            <select class="form-control" multiple id="tipo" name="tipo[]">
                                <?php 
                                foreach($tipos_servicos as $tipo):
                                    ?>
                                    <option value="<?= $tipo->tipo_servico_pk ?>">
                                        <?= $tipo->tipo_servico_nome ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small>Segure Crtl para selecionar mais de uma opção</small>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row pt-5 d-flex justify-content-center">
                            <h5><b>Selecione o revisor:</b></h5>
                            <select class="form-control pt-1" name="funcionario_fk">
                                <?php 
                                    foreach($motoristas_de_caminhao as $motorista):
                                ?>    
                                        <option value="<?= $motorista->funcionario_pk ?>"><?= $motorista->funcionario_nome ?></option>
                                <?php 
                                    endforeach;
                                ?>
                            </select>
                        </div>
                    </div>


                    </form>
                    <div class="col-md-12">
                        <div class="row pt-5 d-flex justify-content-center">
                            <button type="button" id="gerar_relatorio" class="btn au-btn btn-primary form-control" data-toggle="modal" data-target="#confirmar_criacao"><i class="fa fa-dot-circle-o"></i> Gerar Relatório
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="copyright">
                    <p>Copyright © 2018 Colorlib. All rights reserved. Template by
                        <a href="https://colorlib.com">Colorlib</a>.</p>
                </div>
            </div>
        </div>

        </div>
    </div>

    <div class="modal fade" id="confirmar_criacao" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                     <h4 class="modal-title">Confirmar Criação</h4>
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <form>
                         <div class="form-group">
                             <b><p id="p_qtd"></p></b>
                         </div>
                         <div class="form-group">
                            <button type="button" class="btn au-btn btn-primary form-control" id="confirmar" name="post" value=""><i class="fa fa-dot-circle-o"></i> Confirmar</button>
                         </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


