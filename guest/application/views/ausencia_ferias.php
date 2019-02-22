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
                        <h3 class="h4">Ausência / Férias</h3>
                    </div>
                    <div class="card-body">
                        <div class="col-sm-12 row">  
                            <div class="col-sm-3"></div>                     
                            <div class="col-sm-3">
                                <form action="<?=base_url('ausencia');?>">
                                    <button type="submit" style="color: white;" class="btn btn-warning">Ausência Funcionário</button>
                                </form>
                            </div>
                            <div class="col-sm-3">
                                <form action="<?=base_url('ferias');?>">
                                    <button class="btn btn-danger">Férias Funcionário</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</section>