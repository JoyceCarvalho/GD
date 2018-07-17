
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
                        <h3 class="h4">Relatório de tempo médio por cargo</h3>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">                       

                            <table class="table table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th width="80%">Cargo</th>
                                        <th width="20%">Tempo médio</th>
                                        <th width="20%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $media = 0;
                                    $conta_documentos = count($doc_finalizados);
                                    foreach ($doc_finalizados as $documento) {
                                        ?>
                                        <tr>
                                            <td><?=$documento->cargo;?></td>
                                            <td>
                                                <?php
                                                $this->load->model('timer_model', 'timermodel');

                                                $timer = $this->timermodel->tempo_documento_cargo($documento->idcargo);
                                                
                                                // Trecho adaptado do 1º gestão de documento
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
                                                $media += $sum_media;
                                                $mostraNumero = converteHoras($seconds);

                                                echo "<strong>" . $mostraNumero . "</strong>";
                                                ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <p><a class="btn btn-sm btn-warning external" style="color: white;" href="<?=base_url("relatorio_tcargo/".$documento->idcargo);?>"><i class="fa fa-print"></i> Imprimir Relatório</a><p/>
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
                                            <td class="center">
                                                <strong><?= "Média: ".converteHoras(round($media/$conta_documentos)); ?></strong>
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