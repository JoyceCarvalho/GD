<!-- Forms Section-->
<section class="forms"> 
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
            <!-- Form Elements -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="h4">Férias de Funcionário</h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <a href="<?=base_url('ferias')?>" class="btn btn-sm btn-success">Listar Férias Funcionário</a>
                        </div>

                        <hr><br>

                        <form method="post" action="<?=base_url('cad_ferias');?>" class="form-horizontal">
                            
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Funcionário</label>
                                <div class="col-sm-9">
                                    <select name="funcionario" id="funcionario" class="form-control">
                                        <option> -- Selecione -- </option>
                                        <?php 
                                        foreach ($funcionario_full as $user) {
                                            ?> 
                                            <option value="<?=$user->id;?>"><?=$user->nome;?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Data Início</label>
                                <div class="col-sm-9">
                                    <input type="date" name="dia_inicio" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Data Fim</label>
                                <div class="col-sm-9">
                                    <input type="date" name="dia_fim" class="form-control">
                                </div>
                            </div>
                        
                            <div class="line"></div>
                            <div class="form-group row">
                                <div class="col-sm-6 offset-sm-3">
                                    <a href="<?=base_url('ausencia_ferias_cad');?>" class="btn btn-sm btn-secondary"><i class="fa fa-backward"></i> Voltar</a>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Salvar Informações</button>
                                </div>
                            </div>
                                    
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
