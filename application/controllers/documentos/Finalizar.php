<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Finalizar extends CI_Controller {
    
    public function __construct(){
        parent::__construct();

        $this->load->model('documentos_model', 'docmodel');
        $this->load->model('horario_model', 'horasmodel');
        $this->load->model('DocEtapas_model', 'docetapamodel');
        $this->load->model('competencia_model', 'compmodel');
        $this->load->model('usuario_model', 'usermodel');
        $this->load->model('empresa_model', 'empresamodel');
        $this->load->model('etapa_model', 'etapasmodel');
    }

    public function index($identificador){
        
        if ((!isset($_SESSION["logado"])) && ($_SESSION["logado"] != true)) {
            
            redirect("/");

        }

        //transforma o identificador em um array
        $id = str_split($identificador);

        //pega o valor total do array (quantidade de caracteres)
        $tamanho = count($id);

        $protocolo = "";

        for ($i=32; $i < $tamanho ; $i++) { 
            $protocolo .= $id[$i];
        }

        //transforma a string em inteiro
        $idprotocolo = (int)$protocolo;

        $dados = new stdClass;

        $dados = $this->docmodel->documento_transferencia($idprotocolo);

        $dataInicio = $dados->data_hora;

        $etapa = $dados->etapa;

        //Trecho de código adaptado do primeiro sistema de Gestão de Documentos criado por Maykel Uroda
        $horario = $this->horasmodel->verifica_horario($_SESSION["idempresa"]);

        $primeiro_turno_inicio  = $horario->manha_entrada;
        $primeiro_turno_fim     = $horario->manha_saida;
        $segundo_turno_inicio   = $horario->tarde_entrada;
        $segundo_turno_fim      = $horario->tarde_saida;

        $primeiro_turno_inicio_ex  = explode(":", $primeiro_turno_inicio);
        $primeiro_turno_inicio_min = ($primeiro_turno_inicio_ex[0] * 60) + $primeiro_turno_inicio_ex[1];

        $primeiro_turno_fim_ex  = explode(":", $primeiro_turno_fim);
        $primeiro_turno_fim_min = ($primeiro_turno_fim_ex[0] * 60) + $primeiro_turno_fim_ex[1];

        $segundo_turno_inicio_ex  = explode(":", $segundo_turno_inicio);
        $segundo_turno_inicio_min = ($segundo_turno_inicio_ex[0] * 60) + $segundo_turno_inicio_ex[1];

        $segundo_turno_fim_ex  = explode(":", $segundo_turno_fim);
        $segundo_turno_fim_min = ($segundo_turno_fim_ex[0] * 60) + $segundo_turno_fim_ex[1];


        $minutos_turno_primeiro = ($primeiro_turno_fim_min - $primeiro_turno_inicio_min);
        $minutos_turno_segundo  = ($segundo_turno_fim_min - $segundo_turno_inicio_min);
        $minutos_turno_total    = $minutos_turno_primeiro + $minutos_turno_segundo;

        $dataFim = date("Y-m-d H:i:s");

        $dataInicioExplode = explode(" ". $dataInicio);
        $dataInicioQuebrada = $dataInicioExplode[0];
        $HoraInicioQuebrada = $dataInicioExplode[1];
        $HoraInicioQuebrada = explode(":", $HoraInicioQuebrada);
        $HoraInicioQuebrada = $HoraInicioQuebrada[0] . ":" . $HoraInicioQuebrada[1];

        $dataFimExplode  = explode(" ",$dataFim);
        $dataFimQuebrada = $dataFimExplode[0];
        $HoraFimQuebrada = $dataFimExplode[1];
        $HoraFimQuebrada = explode(":", $HoraFimQuebrada);
        $HoraFimQuebrada = $HoraFimQuebrada[0] . ":" . $HoraFimQuebrada[1];

        $dataInicioQuebrada = $dataInicioQuebrada;

        if ($dataInicioQuebrada == $dataFimQuebrada) {
            
            $somatorioTotalMinutos = 0;

            if ((($HoraInicioQuebrada >= $primeiro_turno_inicio) and ($HoraInicioQuebrada <= $primeiro_turno_fim)) and (($HoraFimQuebrada >= $segundo_turno_inicio) and ($HoraFimQuebrada <= $segundo_turno_fim))) {
                
                $HoraInicioQuebradaExplode = explode(":", $HoraInicioQuebrada);
                $HoraInicioQuebradaMinutos = ($HoraInicioQuebradaExplode[0] * 60) + $HoraInicioQuebradaExplode[1];

                $minutos_a = $primeiro_turno_fim_min - $HoraInicioQuebradaMinutos;

                $HoraFimQuebradaExplode = explode(":", $HoraFimQuebrada);
                $HoraFimQuebradaMinutos = ($HoraFimQuebradaExplode[0] * 60) + $HoraFimQuebradaExplode[1];

                $minutos_b = $segundo_turno_fim_min - $HoraFimQuebradaMinutos;

                $minutos = $minutos_a + $minutos_b;

                $somatorioTotalMinutos = $minutos;
                
            } elseif ((($HoraInicioQuebrada >= $primeiro_turno_inicio) and ($HoraInicioQuebrada <= $primeiro_turno_fim)) and (($HoraInicioQuebrada >= $primeiro_turno_inicio) and ($HoraInicioQuebrada <= $primeiro_turno_fim))) {
                
                $HoraInicioQuebradaExplode = explode(":", $HoraInicioQuebrada);
                $HoraInicioQuebradaMinutos = ($HoraInicioQuebradaExplode[0] * 60) + $HoraInicioQuebradaExplode[1];

                $HoraFimQuebradaExplode = explode(":", $HoraFimQuebrada);
                $HoraFimQuebradaMinutos = ($HoraFimQuebradaExplode[0] * 60) + $HoraFimQuebradaExplode[1];

                $minutos = ($HoraFimQuebradaMinutos - $HoraInicioQuebradaMinutos);

                $somatorioTotalMinutos = $minutos;  
            
            } elseif((($HoraInicioQuebrada >=  $segundo_turno_inicio) and ($HoraInicioQuebrada <= $segundo_turno_fim)) and (($HoraInicioQuebrada >= $segundo_turno_inicio) and ($HoraInicioQuebrada <= $segundo_turno_fim))){
                
                $HoraInicioQuebradaExplode = explode(":", $HoraInicioQuebrada);
                $HoraInicioQuebradaMinutos = ($HoraInicioQuebradaExplode[0] * 60) + $HoraInicioQuebradaExplode[1];

                $HoraFimQuebradaExplode = explode(":", $HoraFimQuebrada);
                $HoraFimQuebradaMinutos = ($HoraFimQuebradaExplode[0] * 60) + $HoraFimQuebradaExplode[1];

                $minutos = ($HoraFimQuebradaMinutos - $HoraInicioQuebrada);

                $somatorioTotalMinutos = $minutos;

            } else {

                $HoraInicioQuebradaExplode = explode(":", $HoraInicioQuebrada);
                $HoraInicioQuebradaMinutos = ($HoraInicioQuebradaExplode[0] * 60) + $HoraINicioQuebradaExplode[1];

                $HoraFimQuebradaExplode = explode(":", $HoraFimQuebrada);
                $HoraFimQuebradaMinutos = ($HoraFimQuebradaExplode[0] * 60) + $HoraFimQuebradaExplode[1];

                $minutos = $HoraFimQuebradaMinutos - $HoraInicioQuebradaMinutos;

                $somatorioTotalMinutos = $minutos;

            }
        } else {

            $i = 0;
            $contadorDias = 0;
            while($i != 1){
                $dataVerifica = strtotime("+1 day", strtotime($dataInicioVerifica));
                $dataInicioVerifica = date("Y-m-d", $dataVerifica);

                $contadorDias++;
                if ($dataFimQuebrada == $dataInicioVerifica) {
                    $i = 1;
                    $contadorDias--;
                }
            }

            $somatorioTotalMinutos = $contadorDias * $minutos_turno_total;
            $debugSomatorioTotalMinutos = $somatorioTotalMinutos;

            if ($HoraInicioQuebrada <= $primeiro_turno_fim) {
                
                $HoraInicioQuebradaExplode = explode(":", $HoraInicioQuebrada);
                $HoraInicioQuebradaMinutos = ($HoraInicioQuebradaExplode[0] * 60 ) + $HoraInicioQuebradaExplode[1];

                $minutos_inicio = ($primeiro_turno_fim_min - $HoraInicioQuebradaMinutos) + $minutos_turno_segundo;

            } else {

                $HoraInicioQuebradaExplode = explode(":", $HoraInicioQuebrada);
                $HoraInicioQuebradaMinutos = ($HoraInicioQuebradaExplode[0] * 60) + $HorasInicioQuebradaExplode[1];

                $minutos_inicio = ($segundo_turno_fim_min - $HoraInicioQuebradaMinutos);

            }

        }

    }
}
