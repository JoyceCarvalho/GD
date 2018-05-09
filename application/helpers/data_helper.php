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