<!DOCTYPE html>
<html lang="en">
    <head>
        <title>SGT - Gestão e Tecnologia</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Main CSS-->
        <link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/main.css');?>">
        <!-- Font-icon css-->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Icone SGT -->
        <link rel="shortcut icon" href="<?=base_url('assets/img/favicon.ico');?>">
        <!-- Bootstrap -->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body class="app sidebar-mini rtl">
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <section class="invoice">
                        <div class="row mb-4">
                            <img class="img-responsive" src="<?=base_url("assets/img/header.jpg");?>" alt="<?=$empresa->nome;?>">      
                        </div>
                        <div class="row invoice-info">
                            <div class="col-4">
                                <address>
                                    <?php
                                    foreach ($informacoes_documento as $doc) {
                                        ?>
                                        Grupo: <?=$doc->nome_grupo;?> <br/>
                                        Documento: <?=$doc->nome_documento;?> <br/>
                                        Protocolo: <?=$doc->protocolo;?> <br/>
                                        <?php
                                    }
                                    ?>
                                </address>
                            </div>
                            <div class="col-4">Empresa
                                <address>
                                    <?php
                                    foreach ($nome_empresa as $empresa) {
                                        ?>
                                        <strong><?=$empresa->nome;?></strong><br>
                                        Missão:<?=$empresa->missao;?>
                                        <?php
                                    }
                                    ?>
                                </address>
                            </div>
                            <div class="col-4">
                                <h5 class="text-right"> <b>Data: <?=date('d/m/Y');?></b> </h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <?php
                                foreach ($historico_documentos as $historico) {
                                    if ($historico->nome == null) {
                                        $nome = "Documento Pendente - Sem Responsável";
                                    } else {
                                        $nome = $historico->nome;
                                    }
                                    ?>
                                    <div class="form-group">
                                        <label><?=$historico->descricao;?></label>
                                        - 
                                        <strong><?=$historico->etapa;?></strong>
                                        <br/>

                                        <p><?=$nome;?></p>
                                        <p><?=$historico->data . ' - ' . $historico->hora;?></p>
                                        
                                        <?php
                                        if ($historico->descricao == 'CANCELADO') {
                                            ?>
                                            <br/><label>Motivo Cancelamento</label><br/>
                                            <p><?=$historico->motivo;?></p>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <hr>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!-- Essential javascripts for application to work-->
        <script src="<?=base_url('assets/js/jquery-3.2.1.min.js');?>"></script>
        <script src="<?=base_url('assets/js/popper.min.js');?>"></script>
        <script src="<?=base_url('assets/js/bootstrap.min.js');?>"></script>
        <script src="<?=base_url('assets/js/main.js');?>"></script>
        <!-- The javascript plugin to display page loading on top-->
        <script src="<?=base_url('assets/js/plugins/pace.min.js');?>"></script>
        <!-- Page specific javascripts-->
    </body>
</html>