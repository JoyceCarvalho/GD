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
                        <div class="row mb-4 center">
                            <img class="img-responsive" src="<?=base_url("assets/img/header.jpg");?>" alt="SGT - Gestão e Tecnologia">      
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <p>O documento <strong><?=$documento;?></strong> de protocolo <strong><?=$protocolo;?></strong> está pendente!</p>
                                <br/>
                                <a href="<?=base_url("meusdocumentos")?>">Acesse o Gestão de Prazos e Produtividade para visualizar o documento completo!</a>
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