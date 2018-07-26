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
                        <h3 class="h4">Relatório de tempo médio mensal</h3>
                    </div>

                    <div class="card-body">

                        <form action="<?=base_url('tempo_mensal');?>" method="post">
                            <div class="row col-sm-12">
                                <div class="form-group col-sm-3">
                                    <label>Mês</label>
                                    <select class="form-control" name="filtrar_mes" id="filtro_mes">
                                        <option value="nda"> -- Todos -- </option>
                                        <?php 
                                        foreach($finalizados as $doc){
                                            $idmes = explode("/", $doc->mes_ano);
                                            $mes = mes_extenso($doc->mes_ano);
                                            $mes = explode("/", $mes);

                                            if($mes_filtrado == $idmes[0]){
                                                $sel = "selected=\"selected\"";
                                            } else {
                                                $sel = "";
                                            }
                                            ?>
                                            <option <?=$sel;?> value="<?=$idmes[0];?>"><?=$mes[0];?></option>
                                            <?php
                                        } 
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label>Ano</label>
                                    <select class="form-control" name="filtrar_ano" id="filtro_ano">
                                        <option value="nda"> -- Todos -- </option>
                                        <?php 
                                        foreach($finalizados_ano as $doc){
                                            if($ano_filtrado == $doc->ano){
                                                $sel = "selected=\"selected\"";
                                            } else {
                                                $sel = "";
                                            }
                                            ?>
                                            <option <?=$sel;?> value="<?=$doc->ano;?>"><?=$doc->ano;?></option>
                                            <?php
                                        } 
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label>&nbsp;</label><br/>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-filter"></i> Filtrar</button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">                       

                            <table class="table table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th width="80%">Mês/ano</th>
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
                                            <td><?=mes_extenso($documento->mes_ano);?></td>
                                            <td>
                                                <?php
                                                $this->load->model('timer_model', 'timermodel');

                                                $timer = $this->timermodel->tempo_documento_mensal(transforma_mes_ano($documento->mes_ano));
                                                
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
                                                <p><a class="btn btn-sm btn-warning external" style="color: white;" href="<?=base_url("relatorio_tmensal/".$_SESSION["idempresa"]."/".transforma_mes_ano($documento->mes_ano));?>"><i class="fa fa-print"></i> Imprimir Relatório</a><p/>
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