<div class="pb-0"> 
    <div class="overview-item" style="min-height: 120px;"> 
        <div class="overview__inner"> 
            <div class="overview-box clearfix"> 
                <div class="justify-content-center">
                    <div class="icon col-12 pl-0 pull-left col-md-2"> 
                        <i class="<?= $icone ?>"></i> 
                    </div> 
                    <div class="text pt-1 pull-right col-md-9"> 
                        <h2 style="font-size:14pt;"><?= $titulo ?></h2> 
                        <span><?= $descricao ?></span> 
                    </div> 
                </div>
            </div> 
            <?php if ($chart !== null): ?>
                <?php 
                    if ($values!== null)
                    {
                        $keys= array_keys($values);
                        $values_str = ''.implode(',',$values).'';
                        $keys_str = '"'.implode('","',$keys).'"';
                    }
                ?>  
                <div class="overview-chart" style="padding-bottom: 25px">
                    <canvas  class="<?= $chart ?>" data-params='[[<?= $keys_str?>],[<?= $values_str?>]]'></canvas> 
                </div> 
            <?php endif ?>
        </div> 
    </div> 
</div>