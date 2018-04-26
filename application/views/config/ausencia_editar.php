        <!-- Breadcrumb-->
        <div class="breadcrumb-holder container-fluid">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?=base_url('ausencia');?>">Listar Ausência</a></li>
              <li class="breadcrumb-item active">Editar Ausência de Funcionário</li>
            </ul>
          </div>
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
                                    <h3 class="h4">Ausência de Funcionário</h3>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="<?=base_url('edit_ausencia');?>" class="form-horizontal">
                                        
                                        <?php
                                        foreach ($dados_ausencia as $ausencia) {
                                            ?>
                                            <input type="hidden" name="idausencia" value="<?=$ausencia->id;?>">
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Funcionário</label>
                                                <div class="col-sm-9">
                                                    <select name="funcionario" id="funcionario" class="form-control">
                                                        <?php
                                                        foreach ($funcionario_full as $funct) {
                                                            if($funct->id == $ausencia->fk_idusuario){
                                                                $sel = "selected=\"selected\"";
                                                            } else {
                                                                $sel = "";
                                                            }
                                                            ?>
                                                            <option <?=$sel;?> value="<?=$funct->id?>"><?=$funct->nome?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Data Início:</label>
                                                <div class="col-sm-9">
                                                    <input type="datetime-local" name="dia_inicio" value="<?=corrige_datetime($ausencia->dia_inicio)?>" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Data Fim:</label>
                                                <div class="col-sm-9">
                                                    <input type="datetime-local" name="dia_fim" value="<?=corrige_datetime($ausencia->dia_fim)?>" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Motivo</label>
                                                <div class="col-sm-9">
                                                    <textarea name="motivo" id="editor1" cols="30" rows="10"><?=nl2br($ausencia->motivo);?></textarea>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    
                                        <div class="line"></div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 offset-sm-3">
                                                <a href="<?=base_url('ausencia');?>" class="btn btn-sm btn-secondary"><i class="fa fa-backward"></i> Voltar</a>
                                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Salvar Alterações</button>
                                            </div>
                                        </div>
                                                
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
