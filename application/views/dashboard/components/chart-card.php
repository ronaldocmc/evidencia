<?php 
if ($values!== null)
{
    $indices= array_keys($values);
    $colors = [];
    $indices_str = '"'.implode('","',$indices).'"';


    if (is_array($values[$indices[0]]))
    {
        $values_str = [];
        $keys_str = [];

        foreach ($indices as $key_i => $indice){
            $color = graph_colors[$key_i];
            array_push($colors, $color);
            $keys= array_keys($values[$indice]);
            array_push($keys_str, '"'.implode('","',$keys).'"');
            array_push($values_str, implode(',',$values[$indice]));
        }
    }
    else
    {
        foreach ($indices as $key_i => $indice){
            $color = graph_colors[$key_i];
            array_push($colors, $color);
        }
        $values_str = implode(',',$values);
    }

}

?>

<div class="col-lg-<?= $size ?> d-flex"> 
    <div class="col-12 au-card recent-report"> 
        <div class="au-card-inner"> 
            <h3 class="title-2"><i style="cursor: pointer; color: gray" class="fas fa-question fa-xs pull-left" data-toggle="collapse" href="#<?= $id ?>" role="button" aria-expanded="false" aria-controls="<?= $id ?>"></i><?= $titulo ?></h3>
            
            <div class="col-md-12 mt-3">
                        <div class="collapse" id="<?= $id ?>">
                            <div class="card card-body">
                                <?= $description ?>
                            </div>
                        </div>
                    </div>
            <?php if ($vertical === TRUE): ?>
                <div class="chart-info"> 
                    <div class="chart-info__left">
                         <?php foreach ($indices as $key_i => $indice): ?>
                            <div class="chart-note"> 
                                <span class="dot" style="background-color: <?= $colors[$key_i] ?>"></span> 
                                <span><?= $indice ?></span> 
                            </div> 
                         <?php endforeach ?>
                    </div>

                </div>
            <?php else: ?>
                <div class="row"> 
                    <div class="col-xl-4"> 
                        <div class="chart-note-wrap"> 
                            <?php foreach ($indices as $key_i => $indice): ?>
                            <div class="chart-note mr-0 d-block"> 
                                <span class="dot" style="background-color: <?= $colors[$key_i] ?>"></span> 
                                <span><?= $indice ?></span> 
                            </div> 
                         <?php endforeach ?> 
                        </div> 
                    </div> 
            <?php endif ?> 
                <div class="col-12 <?= $vertical!==TRUE?"col-xl-8 pt-xl-3":"" ?>"> 
                    <canvas  class="<?=$chart ?>"  data-params='[
                            [<?=$indices_str?>]
                            <?php if (is_array($values[$indices[0]])): ?>
                                <?php foreach ($keys_str as $key => $key_s): ?>
                                    ,[<?=$key_s?>],[<?=$values_str[$key]?>]                        
                                <?php endforeach ?>
                            <?php else: ?>      
                                ,[<?=$values_str?>]
                            <?php endif ?>
                        ]' data-colors='[<?='"'.implode('","',$colors).'"' ?>]' style="height: 280px !important;"></canvas> 
                </div> 
            <?php if ($vertical!==TRUE): ?>
            </div>
            <?php endif ?>
        </div> 
    </div> 
</div> 