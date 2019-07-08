<?php
//print_r($informacoes_documento);
foreach ($informacoes_documento as $documento) {
    
    $protocolo = $documento->protocolo;
    $nome_documento = $documento->nome_documento;
    $grupo_documento = $documento->nome_grupo;
    $data_criacao = $documento->data_criacao;
    $responsavel_criacao = $documento->usuario_nome;
    
    $prazo_documento = $documento->prazo;
}


foreach ($etapas_documento as $etapas) {
    
    if ($etapas->descricao == "FINALIZADO") {
        $data_finalizacao = $etapas->data;
        $hora_finalizacao = $etapas->hora;
    }
    
    $data_inicio[] = $etapas->data;
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
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

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
            <div class="panel panel-default sessao no-break">  
                <div class="panel-heading">
                    <span class="titulo-sessao">Dados do Documento</span>
                </div>
                <div class="panel-body">
                    <p>Protocolo: <?=$protocolo;?></p>
                    <p>Documento: <?=$nome_documento;?></p>
                    <p>Grupo de documentos: <?=$grupo_documento;?></p>
                    <p>Documento criado <?=$data_criacao;?></p>
                    <p>Documento criado por <?=$responsavel_criacao;?>
                    
                    <?php if(!empty($prazo_documento)): ?>
                        <p>Prazo para finalização do documento <?=$prazo_documento;?></p>
                    <?php else: ?>
                        <p>Documento sem prazos para finalização!</p>
                    <?php endif; ?>

                    <p>Documento finalizado em <?=$data_finalizacao;?></p>

                    <div class="line"></div>

                    <?php 
                    $i = 0;
                    foreach ($etapas_documento as $etapa ) {
                        if (($etapa->descricao != "CRIADO") && ($etapa->descricao != "FINALIZADO")) {
                            $i++;
                            $j = $i;
                            ?>
                            <div class="panel panel-default sessao">
                                <div class="panel-heading">
                                    <span class="subtitulo"><?=$i?> º Etapa do documento: <?=$etapa->etapa;?></span>
                                </div>
                                
                                <div class="panel-body">
                                    <p>Responsável etapa do documento: <?=$etapa->nome;?></p>
                                    <p>Data de inicio da etapa: <?=$etapa->data;?></p>
                                    <?php 
                                    $this->load->model("etapas_model", "etapasmodel");
                                    $prazo = $this->etapasmodel->prazo_etapa($etapa->idprotocolo, $etapa->idetapa);
                                    if (!empty($prazo)) {
                                        echo "<p>Prazo para finalização da etapa: " . converte_data($prazo) . "</p>";
                                    }
                                    ?>
                                    <p>Data de finalização da etapa: <?=$data_inicio[$j+1];?></p>
                                </div>                                

                            </div> 
                            <?php
                        }
                    }
                    ?>
                    
                </div>
            </div>
            <div class="panel panel-default sessao no-break geral">
                <div class="panel-heading">
                    <span class="titulo-sessao">Erros do documento</span>
                </div>
                <div class="panel-body">
                    <?php 
                    if ($erros_documento) {
                        
                        foreach ($erros_documento as $erros) {
                            $responsavel = $this->usermodel->get_user($erros->responsavel);
                            ?>
                            <div class="panel panel-default sessao no-break">
                                <div class="panel-heading">
                                    <span class="subtitulo"><?=$erros->titulo;?></span>
                                </div>
                                <div class="panel-body">
                                    <p>Erro de natureza <strong><?=$erros->tipo;?></strong> relatado por <?=$erros->relator;?> no dia <?=$erros->quando;?></p>
                                    <p>Ocorreu na etapa <strong><?=$erros->titulo_etapa;?></strong> de responsabilidade de <?=$responsavel->nome;?></p>
                                    <p>Descrição: <?=$erros->descricao;?></p>                                    
                                </div>
                            </div>
                            <?php
                        }

                    } else {
                        ?>
                        <p>Não ocorreram erros no documento</p>
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