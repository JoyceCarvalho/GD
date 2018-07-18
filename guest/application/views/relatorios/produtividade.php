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
                        <h3 class="h4">Produtividade do Grupo</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">                       
                            <table class="table table-striped table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th width="35%">Usuário</th>
                                        <th width="35%">Cargo</th>
                                        <th width="40%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($usuario as $user) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?=$user->nome?>
                                            </td>
                                            <td>
                                                <?=$user->cargo;?>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-warning external" style="color: white" href="<?=base_url('relatorio_produtividade/'.$user->id);?>"><i class="fa fa-print"></i> Produtividade Individual</a>
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