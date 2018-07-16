        <!-- Breadcrumb-->
          <!--<div class="breadcrumb-holder container-fluid">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?=base_url('home/feriado');?>">Listar Feriados</a></li>
              <li class="breadcrumb-item active">Editar Feriados </li>
            </ul>
          </div>-->
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
                                    <h3 class="h4">Horários</h3>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="<?=base_url('edit_feriado');?>" class="form-horizontal">
                                        
                                        <?php
                                        foreach ($dados_feriado as $feriado) {
                                            ?>
                                            <input type="hidden" name="idferiado" value="<?=$feriado->id;?>">
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Titulo</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="titulo" value="<?=$feriado->titulo;?>">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Dia</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" name="dia" value="<?=$feriado->dia;?>">
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    
                                        <div class="line"></div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 offset-sm-3">
                                                <a href="<?=base_url('home/feriado');?>" class="btn btn-sm btn-secondary"><i class="fa fa-backward"></i> Voltar</a>
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
