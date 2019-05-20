<section class="tables">   
    <div class="container-fluid">
        <?php if (validation_errors()) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= validation_errors() ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('success')) : ?>
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">

            <div class="col-lg-12">
    
                <div class="card">
        
                    <div class="card-header d-flex align-items-center">
                        <h3 class="h4">Documentos</h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <a href="<?=base_url('documentos_cad');?>" class="btn btn-sm btn-success">Cadastrar Documentos</a>
                        </div>

                        <div class="table-responsive">                       
                            <table class="table table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th>Grupo</th>
                                        <th>Etapas</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($listagem_documento as $documento) {
                                            ?>
                                            <tr>
                                                <td><?=$documento->titulo;?></td>
                                                <td><?=$documento->grupo;?></td>
                                                <td>
                                                    <?php
                                                    $i = 1;
                                                    $this->load->model('DocEtapas_model', 'docetapamodel');
                                                    $docetapa = $this->docetapamodel->listar_documento_etapa($documento->id);
                                                    foreach($docetapa as $docet){
                                                        echo $i . "º " . $docet->etapa . "<br/>";
                                                        $i++;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <form method="post" action="<?=base_url('editar_documento');?>">
                                                        <input type="hidden" name="iddocumento" value="<?=$documento->id;?>">
                                                        <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Editar</button>
                                                    </form>
                                                </td>                                                
                                                <td>
                                                    <?php
                                                    if(($_SESSION["is_admin"] == true) or ($_SESSION["is_coordenador"]) == true){
                                                        ?>
                                                        <button type="button" data-toggle="modal" data-target="#excluirEmpresa_<?=$documento->id;?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Excluir</button>
                                                        <?php
                                                    }
                                                    ?>
                                                        
                                                    <!-- Modal-->
                                                    <div id="excluirEmpresa_<?=$documento->id;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
                                                        <div role="document" class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 id="exampleModalLabel" class="modal-title"></h4>
                                                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <form action="<?=base_url('excluir_documento');?>" method="post">
                                                                    <input type="hidden" value="<?=$documento->id?>" name="iddocumento">
                                                                    <div class="modal-body">                                                
                                                                        <div class="form-group">
                                                                            <p> Tem certeza que deseja excluir esta informação? </p>
                                                                        </div>
                                                                    </div>
                                                                
                                                                    <div class="modal-footer">
                                                                        <button type="button" data-dismiss="modal" class="btn btn-secondary"><i class="fa fa-times"></i> Fechar</button>
                                                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Excluir</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div><!-- Modal -->
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