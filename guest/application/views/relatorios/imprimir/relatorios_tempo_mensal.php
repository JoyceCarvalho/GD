<?php
ini_set('display_errors', 'off');

$this->load->model("timer_model", "timermodel");

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
                                <h1 class="title no-print"> <?=mes_extenso(converte_data_mes_ano($mes_ano));?> </h1>                                  
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
                <h1 class="title"><?=mes_extenso(converte_data_mes_ano($mes_ano));?></h1>
            </div>
            <!-- Fim página de titulo -->

            <!-- Conteudo do Relatório -->
            <div class="panel panel-default sessao no-break geral">  
                <div class="panel-heading">
                    <span class="titulo-sessao">Dados do Mensais</span>
                </div>
                <div class="panel-body">
                    <p>Mês: <?=mes_extenso(converte_data_mes_ano($mes_ano));?></p>
                    <p>Quantidade de documento: <?=count($dados_mensais);?></p>

                    <div class="line"></div>

                    <div class="panel panel-default sessao">
                        <div class="panel-heading">
                            <span class="subtitulo">Tempo médio mensal</span>
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
                            $sum_media += $seconds;
                            $mostraNumero = converteHoras($seconds);

                            echo "O tempo médio mensal foi <strong>" . $mostraNumero . "</strong>";
                            
                            ?>
                        </div>                                

                    </div> 
                    
                </div>
            </div>
            <div class="panel panel-default sessao no-break geral">
                <div class="panel-heading">
                    <span class="titulo-sessao">Documentos</span>
                </div>
                <div class="panel-body">
                
                    <div class="col-sm-3">
                        <table class="table table-striped table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>Protocolo</th>
                                    <th>Documento</th>
                                    <th>Grupo</th>
                                    <th>Tempo</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php 
                                foreach ($dados_mensais as $documento) {
                                    ?>                                
                                    <tr>    
                                        <td><?=$documento->protocolo;?></td>
                                        <td><?=$documento->documento;?></td>
                                        <td><?=$documento->grupo;?></td>
                                        <td>
                                            <?php
                                            $tempo = $this->timermodel->listar_timer($documento->idprotocolo);

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
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>  
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Fim do conteudo -->
        </div>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </body>
</html>