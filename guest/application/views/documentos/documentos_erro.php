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
                        <h3 class="h4">Documentos com erro</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">                       
                            <table class="table table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Protocolo</th>
                                        <th>Documento<br/>/Grupo</th>
                                        <th>Etapas</th>
                                        <th>Descrição do erro</th>
                                        <th>Data do erro</th>
                                        <th>Quem identificou</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($documentos_erro as $documentos) {
                                            ?>
                                            <tr>
                                                <td><?=$documentos->protocolo;?></td>
                                                <td>
                                                    <?=$documentos->documento;?><br/>
                                                    <strong><?=$documentos->grupo;?></strong>
                                                </td>
                                                <td><?=$documentos->etapa;?></td>
                                                <td>
                                                    <strong><?=$documentos->titulo_erro?></strong><br/>
                                                    <?=$documentos->descricao_erro;?>
                                                </td>
                                                <td><?=$documentos->data_erro;?></td>
                                                <td><?=$documentos->nome_usuario;?></td>
                                                <td style="text-align: center;">
                                                <a href="javascript:void(0)"  data-toggle="modal" data-target="#myModal" onclick="javascript:historico(<?=$documentos->idprotocolo;?>)">Ver Histórico Documento</a><br/>
                                                    <?php
                                                    $this->load->model('documentos_model', 'docmodel');
                                                    if ($documentos->idresponsavel == $_SESSION["idusuario"]) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="apontarObservacao(<?=$documentos->idprotocolo;?>)"> Apontar Observação</a><br/>
                                                        <?php
                                                    }

                                                    if ($this->docmodel->verifica_observacoes($documentos->idprotocolo)) {
                                                        ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="ver_obs(<?=$documentos->idprotocolo;?>)" style="color:green"> Ver Observações</a><br/>
                                                        <?php
                                                    }
                                                    ?>
                                                    
                                                    <input class="id_protocolo" name="id_protocolo" id="id_protocolo" type="hidden" value="<?=$documentos->idprotocolo;?>">
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</section>
<script src="http://code.jquery.com/jquery.js"></script>