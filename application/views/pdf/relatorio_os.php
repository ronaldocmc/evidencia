<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet"> 
<style>

body{
    font-family: 'Raleway', sans-serif;
}
    table {
    border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid gray;
    }

    th, td {
        padding: 15px;
        text-align: left;
    }

    tr:nth-child(even) {background-color: #f2f2f2;}
</style>


<h1 style="text-align: center; font-size: 18pt;">Relatório de Ordens de Serviço</h1>

<table style="width: 100%; margin-bottom: 10px;">
    <thead>
        <tr>
            <th>Empresa</th>
            <th>Data</th>
            <th>Quantidade</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= $empresa ?></td>
            <td><?= $data ?></td>
            <td><?= count($ordens) ?></td>
        </tr>
    </tbody>
</table>

<?php if(count($ordens) > 0){ ?>
<?php foreach($ordens as $os){ ?>

<?php
    if($os->imagem == null){
        $os->imagem = './assets/uploads/imagens_situacoes/no-image.png';
    }
?>

<b>COD: </b> <?= $os->ordem_servico_pk ?> <br>
<div style="width: 100%;">
    <img src="<?= $os->imagem ?>"  style="float: left; width: 150px; margin-right: 5px;">
        <p>
        <b>Serviço: </b> <?= $os->servico_nome ?>
        <b>Endereço: </b> <?= $os->logradouro_nome.', '.$os->local_num ?> <br>
        <b>Prioridade: </b> <?= $os->prioridade_nome ?> | <b>Situação: </b> <?= $os->situacao_nome ?> | <b>Setor: </b> <?= $os->setor_nome ?><br>
        <b>Descrição: </b> <?= $os->ordem_servico_desc ?>
        </p>
</div>
    <hr>

<?php } ?>
<?php }else{ echo "Não há dados correspondente a esse relatório."; } ?>


