<?php
//ini_set('display_errors', 'off');

$this->load->model("timer_model", "timermodel");

foreach ($dados_cargo as $cargo) {
    $titulo_cargo = $cargo->titulo;
    $id_cargo = $cargo->id;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>SGT - Gestão e Tecnologia</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Joyce Carvalho">
        <!-- Main CSS-->
        <link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/main.css');?>">
        <!-- Font-icon css-->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Icone SGT -->
        <link rel="shortcut icon" href="<?=base_url('assets/img/favicon.ico');?>">
        <!-- Bootstrap -->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- CSS para impressão -->
        <link rel="stylesheet" href="<?=base_url("assets/css/imprimir.css");?>">
        <style>
            .divFiltros{
                margin-top: 15px;
            }

            .botaoImprimir{
                margin-top: 40px;
                margin-bottom: 15px;
            }   
        </style>
    </head>
    <body class="app sidebar-mini rtl">
        <div class="container-fluid panel panel-default wrapper">
            <div class="panel-body no-print text-center">
                <div class="row d-print-none">
                    <div class="col-12">
                        <form method="post" action="<?=base_url("relatorio_tcargo/".$id_cargo)?>" autocomplete="off">
                            <div class="col-6">
                                <div class="col-md-4 divFiltros" align="center">
                                    <div class="form-group">
                                        <label> De: </label>
                                        <div class='input-group date dataDe'>
                                            <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                            <input id="dataDe" name="dataDe" type='text' class="form-control" value="<?=$dataDe;?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="col-md-4 divFiltros" align="center">
                                    <div class="form-group">
                                        <label> Até: </label>
                                        <div class='input-group date'>
                                            <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                            <input id="dataAte" name="dataAte" type='text' class="form-control" value="<?=$dataAte;?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="col-md-4 divFiltros" align="center">
                                    <div class="form-group">
                                        <label>Documento:</label>
                                        <div class="col-sm-9">
                                            <select name="documento_filtro" style="height: auto; width:auto" class="form-control">
                                                <option value="nda"> - Selecione - </option>
                                                <?php
                                                if(!empty($documentos_filtro)){
                                                    foreach ($documentos_filtro as $filtro_doc) {
                                                        ?>
                                                        <option <?=($filtro_doc->id == $sel_doc) ? "selected=\"selected\"" : "" ?> value="<?=$filtro_doc->id?>"><?=$filtro_doc->titulo?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-1 botaoImprimir">
                                
                                <div class="form-group">
                                    <button type="submit" name="filtrar" class="form-control btn btn-sm btn-primary"><i class="fa fa-filter"></i> Filtrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row d-print-none mt-2">
                    <div class="col-12 text-center">
                        <a href="javascript:window.print()" class="btn btn-warning"><i class="fa fa-print"></i> Imprimir</a>
                    </div>
                </div>
            </div>

            <div class="panel-body content">
                <!-- Cabeçalho -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="header-container">
                            <div class="col-xs-3 col-md-3">
                                <?php
                                foreach ($nome_empresa as $empresa) {
                                    if (!empty($empresa->logo_code)) {
                                        ?>
                                        <img class="pull-right img-responsive" src="<?=base_url();?>assets/img/logo_empresas/<?=$empresa->logo_code;?>" alt="<?=$empresa->nome;?>">
                                        <?php
                                    } else {
                                        ?>
                                        <img class="pull-right img-responsive" src="<?=base_url("assets/img/logo_sgt.png");?>" alt="<?=$empresa->nome;?>">
                                        <?php
                                    }
                                }   
                                ?>
                            </div>
                            <div class="col-xs-6 text-center">
                                <h1 class="title no-print"> <?=$titulo_cargo;?> </h1>                                  
                            </div>
                            <div class="col-xs-3 col-md-3">
                                <img class="pull-right img-responsive" src="<?=base_url("assets/img/logo_sgt.png");?>" alt="SGT - Gestão e Tecnologia">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim cabeçalho -->
                <div class="col-xs-12">
                    <hr class="no-print">
                </div>
            </div>
            

            <!-- Página de titulo (apenas impressão) -->
            <div class="first-page print-only">
                <h1 class="title"><?=$titulo_cargo;?></h1>
            </div>
            <!-- Fim página de titulo -->

            <!-- Conteudo do Relatório -->
            <div class="panel panel-default sessao no-break geral">  
                <div class="panel-heading">
                    <span class="titulo-sessao">Dados do Cargo</span>
                </div>
                <div class="panel-body">
                    <p>Cargo: <?=$titulo_cargo;?></p>
                    <p>Quantidade de documentos: <?=count($documento_trabalhados);?></p>

                    <div class="line"></div>

                    <div class="panel panel-default sessao">
                        <div class="panel-heading">
                            <span class="subtitulo">Tempo médio por cargo</span>
                        </div>
                        
                        <div class="panel-body">
                            <?php
                            // Trecho adaptado do 1º gestão de documentos
                            $id         = 0;
                            $aux        = 0;
                            $count      = 0;

                            //trecho de codigo adaptado do 1º Gestão de Documentos (contém várias modificações)
                            $seconds    = 0;
                            $sum_media  = 0;
                            //print_r($tempomedio);
                            if(!empty($tempo_medio)){
                                foreach ($tempo_medio as $tempo) {
                                    $id = $tempo->idprotocolo;
                                    if ($id == $aux) {
                                        $action = $tempo->action;
                                        switch ($action) {
                                            case 'start':
                                                $seconds -= $tempo->timestamp;
                                                break;
                                            
                                            case 'pause':
                                                if($seconds !== 0){
                                                    $seconds += $tempo->timestamp;
                                                }
                                                break;
                                        }
                                    } else {
                                        $action = $tempo->action;
                                        switch ($action) {
                                            case 'start':
                                                $seconds -= $tempo->timestamp;
                                                break;
                                            
                                            case 'pause':
                                                if($seconds !== 0){
                                                    $seconds += $tempo->timestamp;
                                                }
                                                break;
                                        }
                                        $count++;
                                    }
                                    $aux = $tempo->idprotocolo;
                                }
                            }

                            $sum_media = $seconds;
                            //echo $seconds;

                            if($sum_media != 0){

                                $divide = ($sum_media / $count);
                                //$mostraNumero = converteHoras(round($divide));

                            } else {

                                $divide = "00:00:00"; 

                            }
                            
                            ?>
                            <input type="hidden" name="t_total" id="t_total" value="<?=$divide;?>">
                            <strong id="tempo_total"></strong>
                        </div>                                

                    </div> 
                    
                </div>
            </div>
            <div class="panel panel-default sessao no-break geral">  
                <div class="panel-heading">
                    <span class="titulo-sessao">Protocolos</span>
                </div>
                <div class="panel-body">

                    <div class="line"></div>

                    <?php 
                    foreach ($documento_trabalhados as $doc ) {
                        ?>
                        <div class="panel panel-default sessao">
                            <div class="panel-heading">
                                <span class="subtitulo"><?=$doc->protocolo;?></span>
                            </div>
                            
                            <div class="panel-body">
                                <p>Documento: <?=$doc->documento;?></p>
                                <p>Data de criação: <?=$doc->data_criacao;?></p>
                                <p>Documento criado por <?=$doc->nome_usuario;?></p>
                                <?php if(!empty($doc->prazo_documento)): ?>
                                    <p>Prazo para finalização do documento <?=$doc->prazo_documento;?></p>
                                <?php else: ?>
                                    <p>Documento sem prazos para finalização!</p>
                                <?php endif; ?>

                                <p>Documento finalizado em <?=$doc->data_finalizacao;?></p>

                                <p>
                                    <?php
                                    $quantidade_etapas = $this->docetapa->qnt_etapas_por_documento($doc->idprotocolo);
                                    $verfica = $this->timermodel->verifica_reinicio($doc->idprotocolo);

                                    if($verfica){
                                        $timer = $this->timermodel->listar_timer_suspenso($doc->idprotocolo);
                                    } else {
                                        $timer = $this->timermodel->listar_timer($doc->idprotocolo);
                                    }

                                    // Trecho adaptado do 1º gestão de documentos
                                    $seconds = 0;
                                    $sum_media = 0;
                                    $media = 0;
                                    foreach ($timer as $t) {
                                        $action = $t->action;
                                        switch ($action) {
                                            case 'start':
                                                $seconds -= $t->timestamp;
                                                break;
                                            
                                            case 'pause':
                                                if($seconds !== 0){
                                                    $seconds += $t->timestamp;
                                                }
                                                break;
                                        }
                                    }
                                    $sum_media = $seconds/$quantidade_etapas;
                                    
                                    $mostraNumero = converteHoras(round($sum_media));

                                    ?>
                                    <strong>Tempo médio do documento <?=$mostraNumero;/*?> </strong>
                                </p>

                                <p>
                                    <?php
                                    $timer = $this->timermodel->listar_timer($doc->idprotocolo);

                                    // Trecho adaptado do 1º gestão de documentos
                                    $seconds = 0;
                                    $sum_media = 0;
                                    foreach ($timer as $t) {
                                        //echo $t->id . "<br/>";
                                        $action = $t->action;
                                        switch ($action) {
                                            case 'start':
                                                $seconds -= $t->timestamp;
                                                break;
                                            
                                            case 'pause':
                                                if($seconds !== 0){
                                                    $seconds += $t->timestamp;
                                                }
                                                break;
                                        }
                                    }
                                    $sum_media += $seconds;
                                    $mostraNumero = converteHoras($sum_media);

                                    ?>
                                    <strong>Tempo total do documento <?=$mostraNumero;*/?> </strong>
                                </p>
                            </div>                                
                        </div> 
                            <?php
                    }
                    ?>               
                </div>
            </div>
            <!-- Fim do conteudo -->
        </div>
        <!-- Essential javascripts for application to work-->
        <script src="<?=base_url('assets/js/popper.min.js');?>"></script>
        <script src="<?=base_url('assets/js/bootstrap.min.js');?>"></script>
        <script src="<?=base_url('assets/js/main.js');?>"></script>
        <!-- The javascript plugin to display page loading on top-->
        <script src="<?=base_url('assets/js/jquery-3.2.1.min.js');?>"></script>
        <script src="<?=base_url('assets/js/plugins/pace.min.js');?>"></script>
        <!-- Page specific javascripts-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!--<script type="text/javascript" src="<?//=base_url("assets/datetimepicker/sample/jquery/jquery-1.8.3.min.js");?>" charset="UTF-8"></script>-->
        <!-- Page specific javascripts-->
        <script type="text/javascript" src="<?=base_url("assets/js/plugins/bootstrap-datepicker.min.js")?>"></script>
        <script type="text/javascript" src="<?=base_url("assets/js/plugins/select2.min.js");?>"></script>
        <script type="text/javascript" src="<?=base_url("assets/js/plugins/bootstrap-datepicker.min.js");?>"></script>
        <script type="text/javascript">
            $('#dataAte').datepicker({
                minViewMode: 'months',
                format: "mm/yyyy",
                autoclose: true,
                todayHighlight: true
            });

            $("#dataDe").datepicker({
                minViewMode: 'months',
                format: "mm/yyyy",
                autoclose: true,
                todayHighlight: true
            });
        </script>
        <script>
        window.addEventListener("DOMContentLoaded", function() {

            var format = function(seconds) {
                var tempos = {
                    segundos: 60
                ,   minutos: 60
                ,   horas: 24
                ,   dias: ''
                };
                var parts = [], string = '', resto, dias;
                for (var unid in tempos) {
                    if (typeof tempos[unid] === 'number') {
                        resto = seconds % tempos[unid];
                        seconds = (seconds - resto) / tempos[unid];
                    } else {
                        resto = seconds;
                    }
                    parts.unshift(resto);
                }
                dias = parts.shift();
                if (dias) {
                    string = dias + (dias > 1 ? ' dias ' : ' dia ');
                }
                for (var i = 0; i < 3; i++) {
                    parts[i] = ('0' + parts[i]).substr(-2);
                }
                string += parts.join(':');
                return string;
            };

            $(function (){

                $(document).ready(function() {

                    var tempo_total = parseInt($("#t_total").val());
                    $('#tempo_total').html(format(++tempo_total));
                });

            })
        });

        </script>
    </body>
</html>