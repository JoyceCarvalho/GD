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