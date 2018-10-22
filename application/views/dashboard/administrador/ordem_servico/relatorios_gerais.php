<style>
    .carousel-caption {
        color: white !important;
        background-color: rgba(0, 0, 0, .45);
    }
    select {
        width: 200px;
        text-align: center;
    }

    input {
        text-align: center;
        width: 100px;
    }

</style>

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">

            <div class="row">

                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-header">
                            <i class="fa fa-exclamation-triangle animated tada infinite"></i><strong> Escolha o Status das Ordens de Serviço</strong>
                        </div>

                        <div class="card-body card-block">
                            <div class="form-group">
                                <p class="pb-2">
                                    As ordens de serviços de todos os relatórios terão o status escolhido
                                    <select class="form-control" id="situacao_pk">
                                        <?php foreach ($situacoes as $s): ?>
                                            <option value="<?=$s->situacao_pk?>">
                                                <?=$s->situacao_nome?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </p>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-6">
                    <div class="card">

                        <div class="card-header">
                            <i class="fas fa-clipboard-list"></i><strong> Relatório Por Data</strong>
                        </div>

                        <div class="card-body card-block">
                            <div class="form-group">
                                <p class="pb-2">
                                    Este relatório seleciona as ordens de serviços criadas até
                                    <input class="form-control" type="number" id="qtd_dias" value="1">
                                    dia(s) atrás
                                </p>
                                <div class="d-flex justify-content-center">
                                    <div class="col-sm-12">
                                        <button id="gerar_pdf_dia" class="au-btn au-btn-icon au-btn--blue form-control">
                                            Gerar PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">

                        <div class="card-header">
                            <i class="fas fa-clipboard-list"></i><strong> Relatório Por Setor</strong>
                        </div>

                        <div class="card-body card-block">
                            <div class="form-group">
                                <p class="pb-2">
                                    Este relatório seleciona as ordens de serviços criadas no
                                    <select class="form-control" id="setor">
                                        <?php foreach ($setores as $s): ?>
                                            <option value="<?= $s->setor_pk ?>">
                                                <?= $s->setor_nome ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </p>
                                <div class="d-flex justify-content-center">
                                    <div class="col-sm-12">
                                        <button id="gerar_pdf_setor" class="au-btn au-btn-icon au-btn--blue form-control">
                                            Gerar PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-6">
                    <div class="card">

                        <div class="card-header">
                            <i class="fas fa-clipboard-list"></i><strong> Relatório Por Departamento</strong>
                        </div>

                        <div class="card-body card-block">
                            <div class="form-group">
                                <p class="pb-2">
                                    Este relatório seleciona as ordens de serviços criadas pelo
                                    <select class="form-control" id="departamento">
                                        <?php foreach ($departamentos as $d): ?>
                                            <option value="<?= $d->departamento_pk ?>">
                                                <?= $d->departamento_nome ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </p>
                                <div class="d-flex justify-content-center">
                                    <div class="col-sm-12">
                                        <button id="gerar_pdf_dpto" class="au-btn au-btn-icon au-btn--blue form-control">
                                            Gerar PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">

                        <div class="card-header">
                            <i class="fas fa-clipboard-list"></i><strong> Relatório Por Serviço</strong>
                        </div>

                        <div class="card-body card-block">
                            <div class="form-group">
                                <p class="pb-2">
                                    Este relatório seleciona as ordens de serviços do serviço
                                    <select class="form-control" id="servico">
                                        <?php foreach ($servicos as $s): ?>
                                            <option value="<?= $s->servico_pk ?>">
                                                <?= $s->servico_nome ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </p>
                                <div class="d-flex justify-content-center">
                                    <div class="col-sm-12">
                                        <button id="gerar_pdf_servico" class="au-btn au-btn-icon au-btn--blue form-control">
                                            Gerar PDF
                                        </button>
                                    </div>
                                </div>
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
</div>


<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER