<?php 
$this->load->model('cargos_model', 'cargosmodel');
?>
<section class="tables">
    <div class="container-fluid">
        <div class="row">        
            <div class="col-lg-12">     
                <div class="tile">
                    <div class="row d-print-none">
                        <form method="post" action="<?=base_url("produtividade_individual")?>" class="col-12" autocomplete="off">                                    
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label> De: </label>
                                    <div class='input-group date dataDe'>
                                        <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <input id="dataDe" name="dataDe" type='text' class="form-control" value="<?=$dataDe;?>" required/>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label> Até: </label>
                                    <div class='input-group date'>
                                        <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <input id="dataAte" name="dataAte" type='text' class="form-control" value="<?=$dataAte;?>" required/>
                                    </div>
                                </div>
                                <div class="col-sm-3" style="padding-top: 30px">
                                    <button type="submit" name="filtrar" class="form-control btn btn-sm btn-primary"><i class="fa fa-filter"></i> Filtrar</button>
                                </div>
                            </div>                                
                        </form>
                    </div>      
                    <section class="invoice">
                        <div class="col-12">
                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="col-xs-3 col-md-3">
                                        <?php
                                        foreach ($nome_empresa as $empresa) {
                                            if (!empty($empresa->logo_code)) {
                                                ?>
                                                <img class="img-responsive" src="<?=base_url();?>assets/img/logo_empresas/<?=$empresa->logo_code;?>" style="max-width: 200px" alt="<?=$empresa->nome;?>">
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
                                <div class="col-6"> 
                                    <h5 class="text-right">Data: <?=date('d/m/Y');?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="row invoice-info">
                            <div class="col-4">Usuário
                                <?php 
                                foreach ($usuario as $user) {
                                ?>
                                    <address>
                                        <strong><?=$user->nome;?></strong><br>
                                        Cargo: <?=$this->cargosmodel->listar_nome_cargo($user->fk_idcargos);?><br>
                                        Email: <?=$user->email;?>
                                    </address>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="col-4">
                            
                            </div>
                            <div class="col-4">Empresa
                                <?php
                                foreach ($nome_empresa as $empresa) {
                                    ?>
                                    <address>
                                        <strong><?=$empresa->nome;?></strong><br>
                                        Missão: <?=$empresa->missao;?><br>
                                    </address>
                                    <?php
                                }
                                ?>
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
                                                if(!empty($tempomedio)){
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

                                                if($sum_media != 0){

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
                        <div class="row d-print-none mt-2">
                            <div class="col-12 text-right"><a class="btn btn-primary" href="javascript:window.print();" target="_blank"><i class="fa fa-print"></i> Imprimir</a></div>
                        </div>                  
                    </section>
                </div>
            </div>
        </div>
    </div>
</section>
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