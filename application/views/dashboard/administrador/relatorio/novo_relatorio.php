<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"> Painel para Criação de Novo Relatório </h2>
                        
                    </div>
                    <div class="col-md-12 mt-3">

                    </div>
                </div>
            </div>
            <div class="row py-2">
                <div class="col-lg-12">
                    <div class="au-card d-flex flex-column">
                        <form method="POST" action="<?= base_url('relatorio/insert_novo_relatorio') ?>">
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
                            <?php 
                            foreach($setores as $setor):
                                ?>
                                <input type="checkbox" id="setor-<?= $setor->setor_pk ?>" name="setor[]" value="<?= $setor->setor_pk ?>"><label for="setor-<?= $setor->setor_pk ?>"><?= $setor->setor_nome ?></label>  <br>
                                <?php
                            endforeach;
                            ?>
                        </div>
                        <div class="col-md-6">
                            <h5><b>Escolhe o(s) tipo(s) de serviço(s):</b></h5> <br>
                            <?php 
                            foreach($tipos_servicos as $tipo):
                                ?>
                                <input type="checkbox" id="tipo-servico-<?= $tipo->tipo_servico_pk ?>" name="tipo[]>" value="<?= $tipo->tipo_servico_pk ?>"><label for="tipo-servico-<?= $tipo->tipo_servico_pk ?>"><?= $tipo->tipo_servico_nome ?></label> <br>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row pt-5 d-flex justify-content-center">
                            <h5><b>Selecione o revisor:</b></h5> <br>
                            <select class="form-control" name="funcionario_fk">
                                <?php 
                                    foreach($motoristas_de_caminhao as $motorista):
                                ?>    
                                        <option value="<?= $motorista->funcionario_pk ?>"><?= $motorista->pessoa_nome ?></option>
                                <?php 
                                    endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row pt-5 d-flex justify-content-center">
                            <button id="gerar_pdf" class="au-btn au-btn-icon au-btn--blue form-control">
                                Gerar Relatório
                            </button>
                        </div>
                    </div>

                    </form>
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
</div>

