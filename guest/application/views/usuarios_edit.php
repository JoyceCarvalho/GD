        <!-- Breadcrumb-->
          <!--<div class="breadcrumb-holder container-fluid">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?=base_url("home/usuario/".$_SESSION["idempresa"]);?>">Listar Usuários</a></li>
              <li class="breadcrumb-item active">Editar Usuário </li>
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
                                    <h3 class="h4">Usuário</h3>
                                </div>
                                <div class="card-body">
                                    <?php
                                    foreach ($usuario as $user) {
                                        ?>
                                        <form method="post" action="<?=base_url('edit_usuario');?>" class="form-horizontal">
                                            <input type="hidden" name="idusuario" value="<?=$user->id?>" >
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Nome Completo</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="nome" value="<?=$user->nome;?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="fileInput" class="col-sm-3 form-control-label">Email:</label>
                                                <div class="col-sm-9">
                                                    <input name="email" type="email" class="form-control" value="<?=$user->email;?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Cargo:</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="cargo" id="cargo">
                                                        <?php 
                                                        foreach ($full_cargos as $cargo) {
                                                            if($user->fk_idcargos == $cargo->id){
                                                                $sel = "selected=\"selected\"";
                                                            } else {
                                                                $sel = "";
                                                            }
                                                            ?>
                                                            <option <?=$sel;?> value="<?=$cargo->id;?>"><?=$cargo->titulo;?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Horário Trabalhado:</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="horas" id="horas">
                                                        <?php 
                                                        foreach ($full_horarios as $horas) {
                                                            if ($user->fk_horariotrab == $horas->id) {
                                                                $sel = "selected=\"selected\"";
                                                            } else {
                                                                $sel = "";
                                                            }
                                                            $m_e = explode(":", $horas->manha_entrada);
                                                            $m_s = explode(":", $horas->manha_saida);
                                                            $t_e = explode(":", $horas->tarde_entrada);
                                                            $t_s = explode(":", $horas->tarde_saida);
                                                            ?>
                                                            <option <?=$sel;?> value="<?=$horas->id;?>"><?=$horas->titulo .": ".$m_e[0].":".$m_e[1]."-".$m_s[0].":".$m_s[1]." / ".$t_e[0].":".$t_e[1]."-".$t_s[0].":".$t_s[1];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="line"></div>
                                                
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Usuário:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="usuario" class="form-control" value="<?=$user->usuario;?>">
                                                </div>
                                            </div>

                                            <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-success"><i class="fa fa-key"></i> Alterar Senha</button>

                                            
                                            <div class="line"></div>
                                            <div class="form-group row">
                                                <div class="col-sm-6 offset-sm-3">
                                                    <a href="<?=base_url('home/usuario/');?>" class="btn btn-sm btn-secondary"><i class="fa fa-backward"></i> Voltar</a>
                                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Salvar Alterações</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- Modal -->
                                        <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
                                            <div role="document" class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 id="exampleModalLabel" class="modal-title">Alterar Senha</h4>
                                                        <a type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></a>
                                                    </div>
                                                    <form action="<?=base_url("alterar_senha");?>" method="post">
                                                        <input type="hidden" name="usuario" value="<?=$user->id;?>">
                                                        <div class="modal-body">                                                
                                                            <div class="form-group">
                                                                <label>Nova Senha</label>
                                                                <input type="password" name="senha" placeholder="Nova senha" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a type="button" data-dismiss="modal" class="btn btn-secondary"><i class="fa fa-times"></i> Fechar</a>
                                                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Salvar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div><!-- Modal -->
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
