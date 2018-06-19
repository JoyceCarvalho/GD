
<!-- Breadcrumb-->
<div class="breadcrumb-holder container-fluid">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=base_url("home");?>">Página Inicial</a></li>
        <li class="breadcrumb-item active"> Relatório de Tempo Médio</li>
    </ul>
</div>

<section class="tables">   

    <div class="container-fluid">
        <?php if (validation_errors()) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= validation_errors() ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (isset($error)) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (isset($success)) : ?>
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    <?= $success ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">

            <div class="col-lg-12">
    
                <div class="card">
        
                    <div class="card-header d-flex align-items-center">
                        <h3 class="h4">Relatório de Tempo Médio</h3>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">                       

                            <table class="table table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th width="20%">Protocolo</th>
                                        <th width="30%">Documento<br/>/Grupo</th>
                                        <th width="10%">Tempo médio</th>
                                        <th width="40%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $conta_documentos = count($doc_finalizados);
                                    foreach ($doc_finalizados as $documentos) {
                                        ?>
                                        <tr>
                                            <td><?=$documentos->protocolo;?></td>
                                            <td>
                                                <?=$documentos->documento;?><br/>
                                                <strong><?=$documentos->grupo;?></strong>
                                            </td>
                                            <td>
                                                <?php
                                                $this->load->model('timer_model', 'timermodel');

                                                $timer = $this->timermodel->listar_timer($documentos->idprotocolo);

                                                // Trecho adaptado do 1º gestão de documentos
                                                $seconds = 0;
                                                $sum_media = 0;
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
                                                $mostraNumero = converteHoras($seconds);

                                                echo "<strong>" . $mostraNumero . "</strong>";
                                                ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <p><a class="btn btn-sm btn-warning external" style="color: white;" href="<?=base_url("relatorio_tempo/".$documentos->idprotocolo);?>"><i class="fa fa-print"></i> Imprimir Relatório</a><p/>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <?php
                                if ($doc_finalizados) {
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="center">
                                                <strong><?= "Média: ".converteHoras(round($sum_media/$conta_documentos)); ?></strong>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                    <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</section>