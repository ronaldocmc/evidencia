<?php 

function get_string($array, $method = NULL, $condition = ' ', $piece = 0){
     $string = '';

     for($i = 0; $i < count($array); $i++){
        if($i == 0){ //se for o primeiro registro
        }else if($i == count($array) -1){ //se for o ultimo registro:
            $string.= ' e ';
        }else{ // se tiver no meio
            $string.= ', ';
        }

        if($method == NULL){
            $string.= $array[$i]->setor_nome;
        } else {
            if($method == 'explode') {
             $explode_array = explode($condition, $array[$i]->setor_nome);

             $string.= $explode_array[$piece]; 
         }
     }
 }

 return $string;
}


 ?>