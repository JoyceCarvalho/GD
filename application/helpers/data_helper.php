<?php
/**
 * Função para correção do datetime Mysql "Y-m-d hh:mm:ss" para o tipo datetime-local html5 "Y-m-dThh:mm"
 *
 * @param datetime $timestamp
 * @return datetime html5
 */
function corrige_datetime($timestamp){
    $ar = explode(" ", $timestamp);
    $time = explode(":", $ar[1]);
    $data = $ar[0] . "T" . $time[0] . ":" . $time[1];
    return $data;
}

/**
 * Função para converter o datetime do MySQL "Y-m-d hh:mm:ss" para melhor visualização "d/m/Y hh:mm"
 *
 * @param datetime $timestamp
 * @return date formatado para melhor visualização na listagem
 */
function converte_datetime($timestamp){
    $ar = explode(" ", $timestamp);
    $date = explode("-", $ar[0]);
    $time = explode(":", $ar[1]);
    $datetime = $date[2]."/".$date[1]."/".$date[0] . " " . $time[0].":".$time[1];
    return $datetime;
}

/**
 * Função para converter uma date no formato mysql DATE Y-m-d para o formato d/m/Y
 *
 * @param date $date
 * @return string com o formato de apresentação
 */
function converte_data($date){
    $day = explode("-", $date);
    return $day[2]."/".$day[1]."/".$day[0];
}

/**
 * Transformar o number_format sem arredondamento
 *
 * @param number $num
 * @param int $precision
 * @return double
 */
function cutNum($num, $precision = 2){
    return floor($num).substr($num-floor($num),1,$precision+1);
}

function numberFormatPrecision($number, $precision = 2, $separator = '.'){
    $numberParts = explode($separator, $number);
    $response = $numberParts[0];
    if(count($numberParts)>1){
        $response .= $separator;
        $response .= substr($numberParts[1], 0, $precision);
    }
    return $response;
}

/**
 * Conversão de horas by Maykel Uroda
 *
 * @param number $segundos
 * @return time
 */
function converteHoras($segundos){
    //header('Content-Type: application/json');
    //$segundos = json_encode($segundos);
    $horas = 0;
    $horas = floor($segundos / 3600); 
    $segundos -= $horas * 3600; 
    $minutos = floor($segundos / 60); 
    $segundos -= $minutos * 60; 
    if ($horas < 10) $horas = "0".$horas; 
    if ($minutos < 10) $minutos = "0".$minutos; 
    if ($segundos < 10) $segundos = "0".$segundos;
    return $horas.":".$minutos.":".$segundos;
}

/**
 * Função responsável por retornar o mes equivalente a data por extenso
 *
 * @param date $data
 * @return date
 */
function mes_extenso($data){

    $separador = explode("/", $data);

    if ($separador[0] == 1) {
        $date = "Janeiro/".$separador[1];
    } elseif($separador[0] == 2){
        $date = "Fevereiro/".$separador[1];
    } elseif($separador[0] == 3){
        $date = "Março/".$separador[1];
    } elseif($separador[0] == 4){
        $date = "Abril/".$separador[1];
    } elseif($separador[0] == 5){
        $date = "Maio/".$separador[1];
    } elseif($separador[0] == 6){
        $date = "Junho/".$separador[1];
    } elseif($separador[0] == 7){
        $date = "Julho/".$separador[1];
    } elseif($separador[0] == 8){
        $date = "Agosto/".$separador[1];
    } elseif($separador[0] == 9){
        $date = "Setembro/".$separador[1];
    } elseif($separador[0] == 10){
        $date = "Outubro/".$separador[1];
    } elseif($separador[0] == 11){
        $date = "Novembro/".$separador[1];
    } elseif($separador[0] == 12){
        $date = "Dezembro/".$separador[1];
    }

    return $date;
}

/**
 * Função para retornar a data em formato mysql
 *
 * @param date $data
 * @return date
 */
function transforma_mes_ano($data){
    
    $d = explode("/", $data);
    return $d[1]. "-" . $d[0];

}

/**
 * Função para conversão de mes ano
 *
 * @param date $data
 * @return date
 */
function converte_data_mes_ano($data){

    $d = explode("-", $data);
    return $d[1] . "/" . $d[0];

}