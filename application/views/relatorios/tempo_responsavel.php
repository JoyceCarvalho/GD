
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
                        <h3 class="h4">Relatório de tempo médio por responsável</h3>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">                       

                            <table class="table table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th width="40%">Nome</th>
                                        <th width="25%">Cargo</th>
                                        <th width="10%">Tempo médio</th>
                                        <th width="25%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $conta_responsavel = count($resp_docs);
                                    $media = 0;
                                    foreach ($resp_docs as $resp) {
                                        ?>
                                        <tr>
                                            <td><?=$resp->nome;?></td>
                                            <td>
                                                <?=$resp->cargo;?><br/>
                                            </td>
                                            <td>
                                                <?php
                                                $this->load->model('timer_model', 'timermodel');

                                                $timer = $this->timermodel->tempo_documento_usuario($resp->id);

                                                $id         = 0;
                                                $aux        = 0;
                                                $count      = 0;

                                                //trecho de codigo adaptado do 1º Gestão de Documentos (contém várias modificações)
                                                $seconds    = 0;
                                                $sum_media  = 0;
                                                foreach ($timer as $tempo) {
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
                                                $media += $sum_media;
                                                
                                                if($sum_media > 0){
                                                    
                                                    $divide = $sum_media / $count;
                                                    $mostraNumero = converteHoras($divide);

                                                } else {

                                                    $mostraNumero = "00:00:00";

                                                }
                                                echo "<strong>" . $mostraNumero . "</strong>";
                                                ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <p><a class="btn btn-sm btn-warning external" style="color: white;" href="<?=base_url("relatorio_tresp/".$resp->id);?>"><i class="fa fa-print"></i> Imprimir Relatório</a><p/>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <?php
                                if ($resp_docs) {
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="center">
                                                <strong><?= "Média: ".converteHoras(round($media/$conta_responsavel)); ?></strong>
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