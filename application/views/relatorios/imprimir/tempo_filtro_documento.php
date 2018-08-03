<?php
date_default_timezone_set('America/Sao_Paulo');
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
                                <h1 class="title no-print"> <?=$titulo_documento;?> </h1>                                  
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
                <h1 class="title"><?=$titulo_documento;?></h1>
                <h6 class="data"><?=date('d/m/Y');?></h6>
            </div>
            <!-- Fim página de titulo -->

            <!-- Conteudo do Relatório -->
            <div class="panel panel-default sessao no-break geral">  
                <div class="panel-heading">
                    <span class="titulo-sessao"><?=$titulo_documento?></span>
                </div>
                <div class="panel-body">
                    <p>Quantidade de protocolos no documento <?=count($protocolos_documento);?>!</p>

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
                    <span class="titulo-sessao">Protocolos</span>
                </div>
                <div class="panel-body">

                    <div class="line"></div>

                    <?php 
                    foreach ($protocolos_documento as $doc ) {
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
                                    $this->load->model('timer_model', 'timermodel');

                                    $timer = $this->timermodel->listar_timer($doc->idprotocolo);

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
                                    $sum_media += $seconds;
                                    $media += $sum_media;
                                    $mostraNumero = converteHoras($seconds);

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
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </body>
</html>