        <!-- Breadcrumb-->
        <div class="breadcrumb-holder container-fluid">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?=base_url('controle/');?>">Listar Empresas</a></li>
              <li class="breadcrumb-item active">Editar Empresa </li>
            </ul>
          </div>
          <!-- Forms Section-->
            <section class="forms">
                <div class="container-fluid">

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
                            <?= $success; ?>
                        </div>
                    </div>
                <?php endif; ?>
                    <div class="row">
                        <!-- Form Elements -->
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex align-items-center">
                                    <h3 class="h4">Empresa</h3>
                                </div>
                                <div class="card-body">
                                    <form action="<?=base_url('empresa_edit');?>" method="post" class="form-horizontal">
                                        <?php
                                        foreach ($dados_empresa as $empresa) {
                                            ?>
                                            <input type="hidden" name="id_empresa" value="<?=$empresa->id;?>">
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Nome Empresa</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="empresa" value="<?=$empresa->nome;?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="fileInput" class="col-sm-3 form-control-label">Logo Empresa</label>
                                                <div class="col-sm-9">
                                                    <input name="arquivo[]" id="fileInput" type="file" class="form-control-file">
                                                    <small class="help-block-none">Apenas imagens.</small>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Cliente Code:</label>
                                                    <div class="col-sm-9">
                                                        <input type="cliente_code" class="form-control" name="cliente_code" value="<?=$empresa->cliente_code;?>">
                                                        <small class="help-block-none">Código do SGT do cliente (se houver).</small>
                                                    </div>
                                                </div>
                                            <div class="line"></div>
                                                <?php
                                            }
                                        ?>
                                        <h1>Coordenador</h1>
                                        <?php
                                        $i = 0;
                                        foreach ($dados_coordenador as $coordenador) {
                                          $i++;
                                            ?>
                                            <input type="hidden" name="id_coordenador[<?=$i;?>]" value="<?=$coordenador->id;?>">
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Nome Completo:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="nome[<?=$i;?>]" class="form-control" value="<?=$coordenador->nome;?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Email:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="email[<?=$i;?>]" class="form-control" value="<?=$coordenador->email;?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label">Usuário:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="usuario[<?=$i;?>]" class="form-control" value="<?=$coordenador->usuario;?>">
                                                </div>
                                            </div>

                                            <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-success"><i class="fa fa-key"></i> Alterar Senha</button>

                                            <?php
                                        }
                                        ?>
                                        <div class="line"></div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 offset-sm-3">
                                                <a href="<?=base_url('controle/');?>" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cancelar</a>
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
                                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                                </div>
                                                <form>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Nova Senha</label>
                                                            <input type="password" name="senha" placeholder="Password" class="form-control">
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="modal-footer">
                                                    <button type="button" data-dismiss="modal" class="btn btn-secondary"><i class="fa fa-times"></i> Fechar</button>
                                                    <button type="button" class="btn btn-primary"><i class="fa fa-save"></i> Salvar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- Modal -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
