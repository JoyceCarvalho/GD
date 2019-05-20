<section class="tables">   
    <div class="container-fluid">
        <?php if (validation_errors()) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= validation_errors() ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata("error")) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata("error"); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata("success")) : ?>
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata("success"); ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="h4">Documentos Não Iniciados</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">                       
                            <table class="table table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th width="10%">Protocolo</th>
                                        <th width="25%">Documento<br/>/Grupo</th>
                                        <th width="10%">Prazos</th>
                                        <th width="10%">Etapas</th>
                                        <th width="15%">Responsável</th>
                                        <th width="30%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($documentos_n_iniciados as $documentos) {
                                        ?>
                                        <tr>
                                            <td><?=$documentos->protocolo;?></td>
                                            <td>
                                                <?=$documentos->documento;?><br/>
                                                <strong><?=$documentos->grupo;?></strong>
                                            </td>
                                            <td>
                                                <?php
                                                if(!empty($documentos->prazo)){
                                                    echo "Documento: ".converte_data($documentos->prazo);
                                                    ?>
                                                    <br/>
                                                    <strong>
                                                        <?php
                                                        $this->load->model('etapas_model', 'etapasmodel');
                                                        $prazo = $this->etapasmodel->prazo_etapa($documentos->idprotocolo, $documentos->idetapa);
                                                        if(!empty($prazo)){
                                                            echo "Etapa: ".converte_data($prazo);
                                                        }
                                                        ?>
                                                    </strong>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <strong>Documento sem prazo!</strong>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <td><?=$documentos->etapa;?></td>
                                            <td>
                                                <?php
                                                if(!empty($documentos->nome_usuario)){
                                                    echo $documentos->nome_usuario;
                                                } else {
                                                    if($documentos->descricao == "PENDENTE"){
                                                        echo "<strong>Documento Pendente</strong> - Sem responsável";
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="javascript:historico(<?=$documentos->idprotocolo;?>)">Ver Histórico Documento</a><br/>
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="transfere(<?=$documentos->idprotocolo;?>)">Transferir Documento</a><br/>
                                                <?php 
                                                $this->load->model('erros_model', 'errosmodel');
                                                
                                                $contador = $this->errosmodel->conta_erros($documentos->idprotocolo);

                                                if ($contador > 0) {
                                                    ?>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="vizualizar_erro(<?=$documentos->idprotocolo;?>)" style="color:red;">Ver Erros</a><br/>
                                                    <?php
                                                }

                                                if($documentos->idresponsavel == $_SESSION["idusuario"]){
                                                    ?>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="apontarObservacao(<?=$documentos->idprotocolo;?>)"> Apontar Observação</a><br/>
                                                    <?php
                                                }
                                                $this->load->model('documentos_model', 'docmodel');

                                                if ($this->docmodel->verifica_observacoes($documentos->idprotocolo) > 0) {
                                                    ?>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="ver_obs(<?=$documentos->idprotocolo;?>)" style="color:green"> Ver Observações</a><br/>
                                                    <?php
                                                }
                                                ?>
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
                <?php 
                foreach ($documentos_n_iniciados as $documentos) {
                    echo '<input class="id_protocolo" name="id_protocolo" id="id_protocolo" type="hidden" value="'.$documentos->idprotocolo.'">';
                }
                ?>
            </div>
        </div>    
    </div>
</section>
<script src="http://code.jquery.com/jquery.js"></script>