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
                    <div class="row d-print-none mt-2">
                        <div class="col-12 text-center">
                            <a class="btn btn-warning" href="javascript:window.print();" target="_blank"><i class="fa fa-print"></i> Imprimir</a>
                        </div>
                    </div>
                    <section class="invoice">
                        <div class="row mb-4">
                            <div class="col-4">
                                <div class="col-6" style="text-align: left;">
                                    <?php
                                    foreach ($nome_empresa as $empresa) {
                                        if (!empty($empresa->logo_code)) {
                                            ?>
                                            <img class="img-responsive" src="<?=base_url();?>assets/img/logo_empresas/<?=$empresa->logo_code;?>" alt="<?=$empresa->nome;?>">
                                            <?php
                                        } else {
                                            ?>
                                            <img class="img-responsive" src="<?=base_url("assets/img/logo_sgt.png");?>" alt="<?=$empresa->nome;?>">
                                            <?php
                                        }
                                    }   
                                    ?>
                                </div>
                            </div>
                            
                            <div class="col-4">
                                <h2>Documento em atraso</h2>
                            </div>

                            <div class="col-4">
                                <div class="col-xs-6 col-md-6"></div>
                                <div class="col-xs-6 col-md-6">
                                    <img class="pull-right img-responsive" src="<?=base_url("assets/img/logo_sgt.png");?>" alt="SGT - Gestão e Tecnologia">
                                </div>
                            </div>
                        </div>
                        <div class="row invoice-info">
                            <div class="col-4">Documento:
                                <address>
                                    <?php
                                    foreach ($informacoes_documento as $info) {
                                        ?>
                                        <strong><?=$info->protocolo;?></strong> - <?=$info->nome_documento;?><br/>
                                        Data de criação <?=$info->data_criacao;?><br/>
                                        Criado por: <?=$info->usuario_nome;?><br/>
                                        Prazo do documento <?=$info->prazo;?>
                                        <?php
                                    }
                                    ?>
                                </address>
                            </div>
                            <div class="col-4">Empresa:
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
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Informações etapa em atraso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                foreach($documentos_prazo as $doc){
                                                    ?>
                                                    <p>Etapa do documento <?=$doc->etapa_atual;?>.</p>
                                                    <p>Responsável pela etapa <?=$doc->nome_usuario;?>.</p>
                                                    <p>Documento recebido dia <?=$doc->data_recebimento;?>.</p>
                                                    <p>Prazo da etapa <?=$doc->prazo;?>, atraso de <?=$doc->dias_atraso;?> dias.</p>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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