<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link href="<?php echo base_url('assets/vendor/font-awesome-5/css/fontawesome-all.min.css')?>" rel="stylesheet"
        media="all">

    <link href="<?php echo base_url('assets/vendor/bootstrap-4.1/bootstrap.min.css')?>" rel="stylesheet" media="all">

    <!-- Jquery JS-->
    <script src="<?php echo base_url('assets/vendor/jquery-3.2.1.min.js')?>"></script>
    <!-- Bootstrap JS-->
    <script src="<?php echo base_url('assets/vendor/bootstrap-4.1/bootstrap.min.js')?>"></script>

    <script>
        $(document).ready(function () {
            window.print();
        });
    </script>
</head>

<body style="padding: 40px 10px">


    <div class="card-group">

        <div class="card">
            <div class="card-header">
                <b>Funcionário</b>
            </div>
            <div class="card-body">
            <?= $funcionario->funcionario_nome ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <b>Gerado em</b>
            </div>
            <div class="card-body">
            <?= date("d/m/Y", strtotime($relatorio->relatorio_data_criacao)) ?>
            </div>
        </div>

    </div>


    <div class="card-group">

        <div class="card">
            <div class="card-header">
                <b>Período</b>
            </div>
            <div class="card-body">
                <?= $filtros['data'] ?>
            </div>
        </div>


        <div class="card">
            <div class="card-header">
                <b>Setores Selecionados</b>
            </div>
            <div class="card-body">
                <?= $filtros['setor'] ?>
            </div>
        </div>


        <div class="card">
            <div class="card-header">
                <b>Tipos de Serviços</b>
            </div>
            <div class="card-body">
                <?= $filtros['tipos_servicos'] ?>
            </div>
        </div>


    </div>



    <table id="table_os" class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th id="data_brasileira">Data</th>
                <th>Prioridade</th>
                <th>Endereço</th>
                <th>Serviço</th>
                <th>Setor</th>
                <th>Situação</th>
                <th>Foto</th>
                <th>Avaliação</th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 0; ?>
            <?php if ($ordens_servicos != null): ?>
            <?php foreach ($ordens_servicos as $key => $ordem_servico): ?>
            <tr>
                <td>
                    <?=$ordem_servico->ordem_servico_cod?>
                </td>
                <td>
                    <span style="display: none">
                        <?=$ordem_servico->ordem_servico_criacao?></span>
                    <?= $ordem_servico->ordem_servico_criacao ?>
                </td>
                <td>
                    <?=$ordem_servico->prioridade_nome?>
                </td>
                <td>
                    <span style="text-align: justify;">
                        <?=$ordem_servico->localizacao_rua. ", " .
                                                                $ordem_servico->localizacao_num . " - " .
                                                                $ordem_servico->localizacao_bairro?>
                    </span>
                </td>
                <td>
                    <?=$ordem_servico->servico_nome?>
                </td>

                <td width="75">
                    <?=$ordem_servico->setor_nome?>
                </td>
                <td>
                    <?= $ordem_servico->situacao_nome ?>
                </td>
                <td width="100">
                    <?php if (isset($ordem_servico->image)): ?>
                        <img src="<?= base_url($ordem_servico->image) ?>">
                    <?php else: ?>
                        Sem Imagem
                    <?php endif ?>
                </td>
                <td width="200">
                    <input type="checkbox" id="situacao_1">
                    <label for="situacao_1"> Não resolvido</label>
                    <br>
                    <input type="checkbox" id="situacao_2">
                    <label for="situacao_2"> Recusado(Não procede)</label>
                    <br>
                    <input type="checkbox" id="situacao_3">
                    <label for="situacao_3"> Recusado(Repetido)</label>
                    <br>
                    <input type="checkbox" id="situacao_4">
                    <label for="situacao_4"> Finalizado</label>
                    <br>
                </td>

            </tr>
            <?php $count++; ?>
            <?php endforeach?>
            <?php endif?>
        </tbody>
    </table>

</body>