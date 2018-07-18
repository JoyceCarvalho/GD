<?php 
$this->load->model('cargos_model', 'cargosmodel');
?>
<section class="tables">
    <div class="container-fluid">
        
        <div class="row">        
            <div class="col-lg-12">     
                <div class="tile">
                    <section class="invoice">
                        <div class="row mb-4">
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
                            <div class="col-6"> 
                                <h5 class="text-right">Data: <?=date('d/m/Y');?></h5>
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
                                            <th>Quantidade de Documentos Finalizados</th>
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
                                                
                                                $sum_media += $seconds;
                                                $divide = $sum_media / $count;
                                                $mostraNumero = converteHoras($divide);
                                                echo $mostraNumero;
                                                ?>
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