<?php
$this->load->model("cargos_model", "cargosmodel");
?>
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
                                <h2>Produtividade Individual</h2>
                            </div>

                            <div class="col-4">
                                <div class="col-xs-6 col-md-6"></div>
                                <div class="col-xs-6 col-md-6">
                                    <img class="pull-right img-responsive" src="<?=base_url("assets/img/logo_sgt.png");?>" alt="SGT - Gestão e Tecnologia">
                                </div>
                            </div>
                        </div>
                        <div class="row invoice-info">
                            <div class="col-4">Usuário
                                <address>
                                    <strong><?=$usuario->nome;?></strong><br>
                                    Cargo: <?=$this->cargosmodel->listar_nome_cargo($usuario->fk_idcargos);?><br/>
                                    Email: <?=$usuario->email;?>
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
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Quantidade de Documentos em Andamento</th>
                                            <th>Quantidade de Documentos Trabalhados</th>
                                            <th>Tempo médio por documento</th>
                                            <th>Quantidade de Erros por documento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?=$documentos_andamento;?>
                                            </td>
                                            <td>
                                                <?=$documentos_fnalizados;?>
                                            </td>
                                            <td>
                                                <?php
                                                $id         = 0;
                                                $aux        = 0;
                                                $count      = 0;

                                                //trecho de codigo adaptado do 1º Gestão de Documentos (contém várias modificações)
                                                $seconds    = 0;
                                                $sum_media  = 0;
                                                //print_r($tempomedio);
                                                if($tempomedio > 0){
                                                    foreach ($tempomedio as $tempo) {
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

                                                if($sum_media > 0){

                                                    $divide = ($sum_media / $count);
                                                    //$mostraNumero = converteHoras(round($divide));

                                                } else {

                                                    $divide = "00:00:00"; 

                                                }

                                                //echo $sum_media;
                                                ?>
                                                <input type="hidden" name="t_total" id="t_total" value="<?=$divide;?>">
                                                <strong id="tempo_total"></strong>
                                            </td>
                                            <td>
                                                <?=$erros_user;?>
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