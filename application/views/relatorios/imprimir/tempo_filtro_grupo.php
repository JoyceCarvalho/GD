<?php
date_default_timezone_set('America/Sao_Paulo');

$this->load->model('timer_model', 'timermodel');
$this->load->model('DocEtapas_model', 'docetapa');

$media = 0;
foreach ($documentos_grupo as $documentos) {
    
    $quantidade_etapas = $this->docetapa->qnt_etapas_por_documento($documentos->idprotocolo);
    $verfica = $this->timermodel->verifica_reinicio($documentos->idprotocolo);

    if($verfica){
        $timer = $this->timermodel->listar_timer_suspenso($documentos->idprotocolo);
    } else {
        $timer = $this->timermodel->listar_timer($documentos->idprotocolo);
    }

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
    $sum_media += $seconds/$quantidade_etapas;
    $media += $sum_media;
}

$quantidade_documentos = count($documentos_grupo);
if($quantidade_documentos > 0){
    //$tempo_medio_grupo = converteHoras(round($media/$quantidade_documentos));
    $tempo_medio_grupo = $media/$quantidade_documentos;
} else {
    $tempo_medio_grupo = "00:00:00";
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
    <body>
        <div class="container-fluid panel panel-default wrapper">
            <div class="panel-body no-print text-center">
                <div class="row d-print-none">
                    <div class="col-12">
                        <form method="post" action="<?=base_url("relatorio_tempo_grupo/".$id_grupo)?>" autocomplete="off">
                            <div class="col-6">
                                <div class="col-md-4 divFiltros" align="center">
                                    <div class="form-group">
                                        <label> De: </label>
                                        <div class='input-group date dataAno'>
                                            <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                            <input id="dataAno" name="dataMesAno" type='text' class="form-control" value="<?=$dataMesAno;?>"/>
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
                                <h1 class="title no-print"> <?=$titulo_grupo;?> </h1>                                  
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
                <h1 class="title"><?=$titulo_grupo;?></h1>
                <h6 class="data"><?=date('d/m/Y');?></h6>
            </div>
            <!-- Fim página de titulo -->

            <!-- Conteudo do Relatório -->
            <div class="panel panel-default sessao no-break geral">  
                <div class="panel-heading">
                    <span class="titulo-sessao"><?=$titulo_grupo?></span>
                </div>
                <div class="panel-body">
                    <p>Quantidade de documentos no grupo <?=count($documentos_grupo);?>!</p>

                    <div class="line"></div>

                    <div class="panel panel-default sessao">
                        <div class="panel-heading">
                            <span class="subtitulo">Tempo médio do grupo</span>
                        </div>
                        
                        <div class="panel-body">
                            <!-- O tempo médio do grupo foi <strong><?//=$tempo_medio_grupo?></strong>-->
                            O tempo médio do grupo foi
                            <input type="hidden" name="t_total" id="t_total" value="<?=$tempo_medio_grupo;?>">
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
                    foreach ($documentos_grupo as $doc ) {
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
                                    <strong>Tempo médio do documento <?=$mostraNumero;?> </strong>
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
            $('#dataAno').datepicker({
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