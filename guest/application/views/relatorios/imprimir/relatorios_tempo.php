<?php
ini_set('display_errors', 'off');

$this->load->model("timer_model", "timermodel");

//print_r($informacoes_documento);
foreach ($informacoes_documento as $documento) {
    $idprotocolo = $documento->idprotocolo;
    $protocolo = $documento->protocolo;
    $nome_documento = $documento->nome_documento;
    $grupo_documento = $documento->nome_grupo;
    $data_criacao = $documento->data_criacao;
    $responsavel_criacao = $documento->usuario_nome;
    
    $prazo_documento = $documento->prazo;
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
        <!-- Bootstrap CSS-->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- Font Awesome CSS-->
        <link rel="stylesheet" href="<?=base_url('assets/vendor/font-awesome/css/font-awesome.min.css');?>">
        <!-- Fontastic Custom icon font-->
        <link rel="stylesheet" href="<?=base_url('assets/css/fontastic.css');?>">
        <!-- Google fonts - Poppins -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700">
        <!-- theme stylesheet-->
        <link rel="stylesheet" href="<?=base_url('assets/css/style.blue.css');?>" id="theme-stylesheet">
        <!-- Custom stylesheet - for your changes-->
        <link rel="stylesheet" href="<?=base_url('assets/css/custom.css');?>">
        <!-- Favicon-->
        <link rel="shortcut icon" href="<?=base_url('assets/img/favicon.ico');?>">
        <!-- jQuery -->
        <script type="text/javascript" src="http://code.jquery.com/jquery-3.3.1.min.js"></script>

        <!-- CSS para impressão -->
        <link rel="stylesheet" href="<?=base_url("assets/css/imprimir.css");?>">
    </head>
    <body>
        <div class="container-fluid panel panel-default wrapper">
            <div class="panel-body no-print text-center">
                <a href="javascript:window.print()" class="btn btn-warning"><i class="fa fa-print"></i> Imprimir</a>
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
                                        <!--<img class="pull-right img-responsive" src="<?//=base_url();?>assets/img/logo_empresas/<?//=$empresa->logo_code;?>" alt="<?//=$empresa->nome;?>">-->
                                        <img src="http://www.sgtgestaoetecnologia.com.br/gestaodocumentos/assets/img/logo_empresas/<?=$empresa->logo_code;?>" alt="<?=$empresa->nome;?>" class="pull-right img-responsive">
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
                                <h1 class="title no-print"> <?=$protocolo . " - " . $nome_documento;?> </h1>                                  
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
                <h1 class="title"><?=$protocolo;?></h1>
                <h5 class="document"><?= $nome_documento?></h5>
                <h6 class="data"><?=$data_criacao;?></h6>
            </div>
            <!-- Fim página de titulo -->

            <!-- Conteudo do Relatório -->
            <div class="panel panel-default sessao no-break geral">  
                <div class="panel-heading">
                    <span class="titulo-sessao">Dados do Documento</span>
                </div>
                <div class="panel-body">
                    <p>Protocolo: <?=$protocolo;?></p>
                    <p>Documento: <?=$nome_documento;?></p>
                    <p>Grupo de documentos: <?=$grupo_documento;?></p>
                    <p>Documento criado <?=$data_criacao;?></p>
                    <p>Documento criado por <?=$responsavel_criacao;?>
                    <p>Prazo para finalização do documento <?=$prazo_documento;?></p>
                    <p>Documento finalizado em <?=$data_finalizacao;?></p>

                    <div class="line"></div>

                    <div class="panel panel-default sessao">
                        <div class="panel-heading">
                            <span class="subtitulo">Tempo médio do documento</span>
                        </div>
                        
                        <div class="panel-body">
                            <?php
                            // Trecho adaptado do 1º gestão de documentos
                            $seconds = 0;
                            $sum_media = 0;

                            foreach ($tempo_medio as $tempo) {
                                
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
                            }
                            $sum_media = $seconds/$qnt_etapas_documento;
                            $mostraNumero = converteHoras(round($sum_media));
                            
                            echo "O tempo médio desenvolvido no documento foi <strong>" . $mostraNumero . "</strong>";
                            
                            ?>
                        </div>                                

                    </div> 
                    
                </div>
            </div>
            <div class="panel panel-default sessao no-break geral">
                <div class="panel-heading">
                    <span class="titulo-sessao">Tempo total do documento por etapa</span>
                </div>
                <div class="panel-body">
                
                    <div class="col-sm-3">
                        <table class="table table-striped table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>Etapa</th>
                                    <th>Responsável</th>
                                    <th>Tempo</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php 
                                foreach ($tempo_por_etapa as $etapa) {
                                    ?>                                
                                    <tr>    
                                        <td><?=$etapa->etapa_titulo?></td>
                                        <td><?=$etapa->nome_usuario;?></td>
                                        <td>
                                            <?php
                                            $verifica = $this->timermodel->verifica_reinicio($idprotocolo);
                                            
                                            if($verifica > 0){
                                                $tempo = $this->timermodel->tempo_por_etapa_suspenso($idprotocolo, $etapa->idetapa);
                                            } else {
                                                $tempo = $this->timermodel->tempo_por_etapa($idprotocolo, $etapa->idetapa);
                                            }
                                            
                                            // Trecho adaptado do 1º gestão de documentos
                                            $seconds = 0;
                                            $sum_media = 0;
                                            foreach ($tempo as $t) {
                                                
                                                $action = $t->action;
                                                switch ($action) {
                                                    case 'start':
                                                        $seconds -= $t->timestamp;
                                                        break;
                                                    
                                                    case 'pause':
                                                        if ($seconds !== 0) {
                                                            $seconds += $t->timestamp;
                                                        }
                                                        break;
                                                }

                                            }

                                            $sum_media += $seconds;
                                            $mostraNumero = converteHoras($seconds);

                                            echo $mostraNumero;

                                            $charts[$etapa->etapa_titulo] = cutNum(str_replace(":",".",$mostraNumero));
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>  
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-3"></div>
                    <div class="charts-container col-sm-6">
                        <div id="chart_etapa" class="myChart"></div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default sessao no-break geral">
                <div class="panel-heading">
                    <span class="titulo-sessao">Tempo total do documento por responsável</span>
                </div>
                <div class="panel-body">
                    
                    <div class="col-sm-3">

                        <table class="table table-striped table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>Responsável</th>
                                    <th>Tempo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($tempo_por_responsavel as $responsavel) {

                                    $seconds = 0;
                                    $sum_media = 0;

                                    ?>
                                    <tr>
                                        <td><?=$responsavel->nome_usuario?></td>
                                        <td>
                                            <?php
                                            $verifica = $this->timermodel->verifica_reinicio($idprotocolo);
                                            
                                            if($verifica > 0){
                                                $tempo = $this->timermodel->tempo_por_responsavel_sus($idprotocolo, $responsavel->idusuario);
                                            } else {
                                                $tempo = $this->timermodel->tempo_por_responsavel($idprotocolo, $responsavel->idusuario);
                                            }
                                            
                                            // Trecho adaptado do 1º gestão de documentos
                                            $seconds = 0;
                                            $sum_media = 0;
                                            foreach ($tempo as $t) {
                                                
                                                $action = $t->action;
                                                switch ($action) {
                                                    case 'start':
                                                        $seconds -= $t->timestamp;
                                                        break;
                                                    
                                                    case 'pause':
                                                        if ($seconds !== 0) {
                                                            $seconds += $t->timestamp;
                                                        }
                                                        break;
                                                }

                                            }

                                            $sum_media += $seconds;
                                            $mostraNumero = converteHoras($seconds);

                                            echo $mostraNumero;

                                            $charts_responsavel[$responsavel->nome_usuario] = cutNum(str_replace(":",".",$mostraNumero));
                                            ?>
                                        </td>
                                    </tr>
                                    <?php

                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                                
                    <div class="col-sm-3"></div>
                    <div class="charts-container col-sm-6">
                        <div id="chart_resp" class="myChart"></div>
                    </div>
                </div>
            </div>

            <?php if($tempo_em_suspensao): ?>
                <div class="panel panel-default sessao no-break topo">
                    <div class="panel-heading">
                        <span class="titulo-sessao">Tempo do documento aguardando exigência</span>
                    </div>
                    <div class="panel-body">
                        
                        <?php
                        // Trecho adaptado do 1º gestão de documentos
                        $seconds = 0;
                        $sum_media = 0;

                        foreach ($tempo_em_suspensao as $tempo) {
                            
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
                        }
                        $sum_media += $seconds;
                        ?>
                        <input type="hidden" name="tempo_exigencia" id="t_exigencia" value="<?=$seconds;?>">
                        O tempo do documento aguardando exigência foi <strong id="tempo_exigencia"></strong>

                    </div>
                </div>
            <?php endif; ?>

            <?php if($tempo_pendente): ?>
                <div class="panel panel-default sessao no-break topo">
                    <div class="panel-heading">
                        <span class="titulo-sessao">Tempo do documento pendente</span>
                    </div>
                    <div class="panel-body">
                        
                        <?php
                        // Trecho adaptado do 1º gestão de documentos
                        $seconds = 0;
                        $sum_media = 0;

                        foreach ($tempo_pendente as $tempo) {
                            
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
                        }
                        ?>
                        <input type="hidden" name="total_pendente" id="t_pendente" value="<?=$seconds;?>">
                        O tempo em que o documento encontrou-se pendente foi <strong id="tempo_pendente"></strong>

                    </div>
                </div>
            <?php endif; ?>

            <div class="panel panel-default sessao no-break topo geral">
                <div class="panel-heading">
                    <span class="titulo-sessao">Tempo total desenvolvido no documento</span>
                </div>
                <div class="panel-body">
                    <?php
                    $seconds = 0;
                    //$sum_media = 0;

                    foreach ($tempo_total_documento as $tempo) {
                        
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
                    }
                    
                    ?>
                    <input type="hidden" name="tempo_documento" id="t_total" value="<?=$seconds;?>">
                    O tempo total do documento foi <strong id="tempo_total"></strong>
                </div>
            </div>
            <!-- Fim do conteudo -->
        </div>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/highcharts-3d.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="<?=base_url('assets/vendor/popper.js/umd/popper.min.js')?>"> </script>
        <script src="<?=base_url('assets/vendor/jquery.cookie/jquery.cookie.js');?>"> </script>
        <script src="<?=base_url('assets/vendor/jquery-validation/jquery.validate.min.js');?>"></script>
        <script src="<?=base_url('assets/js/jquery.mask.min.js');?>"></script>
        <script>
            Highcharts.chart('chart_etapa', {
                chart: {
                    type: 'column',
                    options3d: {
                        enabled: true,
                        alpha: 10,
                        beta: 25,
                        depth: 70
                    }
                },
                title: {
                    text: 'Tempo total do documento por etapa'
                },
                plotOptions: {
                    column: {
                        depth: 25
                    }
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        rotation: -45,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Hora : minuto'
                    }
                },
                legend: {
                    enabled: false
                },
                credits: {
                    enabled:false
                },
                tooltip: {
                    pointFormat: '<b>{point.y:.2f}</b>'
                },
                series: [{
                    /*name: 'Population',*/
                    data: [
                        <?php
                        foreach ($charts as $key => $value) {
                            ?>
                            ['<?=$key;?>', <?=$value;?>],
                            <?php
                        }
                        ?>
                    ],
                    dataLabels: {
                        enabled: true,
                        //rotation: -90,
                        color: '#FFFFFF',
                        align: 'center',
                        format: '{point.y:.2f}', // one decimal
                        y: 5, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
            });

            Highcharts.chart('chart_resp', {
                chart: {
                    type: 'column',
                    options3d: {
                        enabled: true,
                        alpha: 10,
                        beta: 25,
                        depth: 70
                    }
                },
                title: {
                    text: 'Tempo total do documento por responsável'
                },
                plotOptions: {
                    column: {
                        depth: 25
                    }
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        rotation: -45,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Horas : minutos'
                    }
                },
                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '<b>{point.y:.2f}</b>'
                },
                series: [{
                    /*name: 'Population',*/
                    data: [
                        <?php
                        foreach ($charts_responsavel as $key => $value) {
                            ?>
                            ['<?=$key;?>', <?=$value;?>],
                            <?php
                        }
                        ?>
                    ],
                    dataLabels: {
                        enabled: true,
                        //rotation: -90,
                        color: '#FFFFFF',
                        align: 'center',
                        format: '{point.y:.2f}', // one decimal
                        y: 5, // 10 pixels down from the top
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }]
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

                        <?php if($tempo_em_suspensao): ?>
                            var tempo = parseInt($("#t_exigencia").val());
                            $('#tempo_exigencia').html(format(++tempo));
                        <?php endif; ?>

                        <?php if($tempo_pendente): ?>   
                            var tempo = parseInt($('#t_pendente').val());
                            $('#tempo_pendente').html(format(++tempo));
                        <?php endif; ?>

                        var tempo_total = parseInt($("#t_total").val());
                        $('#tempo_total').html(format(++tempo_total));
                    });

                })
            });
            
        </script>
    </body>
</html>