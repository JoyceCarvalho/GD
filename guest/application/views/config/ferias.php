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
                        <h3 class="h4">Férias de Funcionários</h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <a href="<?=base_url('ferias_cad')?>" class="btn btn-sm btn-success">Cadastrar Férias Funcionário</a>
                        </div>

                        <div class="table-responsive">                       
                            <table class="table table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Funcionário</th>
                                        <th>Dia Início</th>
                                        <th>Dia Fim</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($listagem_ferias as $ferias) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php 
                                                    $this->load->model('usuario_model', 'usermodel');
                                                    $user_dados = $this->usermodel->dados_usuario($ferias->fk_idusuario);
                                                    foreach($user_dados as $user){
                                                        echo $user->nome;
                                                    }
                                                    ?>
                                                </td>
                                                <td><?=converte_data($ferias->dia_inicio);?></td>
                                                <td><?=converte_data($ferias->dia_fim);?></td>
                                                <td>
                                                    <?php
                                                    if(($_SESSION["is_admin"] == true) or ($_SESSION["is_coordenador"]) == true){
                                                        ?>
                                                        <form method="post" action="<?=base_url('editar_ferias');?>">
                                                            <input type="hidden" name="idferias" value="<?=$ferias->id?>">
                                                            <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Editar</button>
                                                        </form>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>                                                
                                                <td>
                                                    <?php
                                                    if(($_SESSION["is_admin"] == true) or ($_SESSION["is_coordenador"]) == true){
                                                        ?>
                                                        <button type="button" data-toggle="modal" data-target="#excluirEmpresa_<?=$ferias->id;?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Excluir</button>
                                                        <?php
                                                    }
                                                    ?>
                                                        
                                                    <!-- Modal-->
                                                    <div id="excluirEmpresa_<?=$ferias->id;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
                                                        <div role="document" class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 id="exampleModalLabel" class="modal-title"></h4>
                                                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <form method="post" action="<?=base_url('excluir_ferias');?>">
                                                                    
                                                                    <input name="idferias" value="<?=$ferias->id?>" type="hidden">

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