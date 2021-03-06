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
                        <?php
                        echo form_open_multipart('empresa_edit');
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
                                    <div class="col-sm-6">
                                        <input name="logo_cliente" id="logo_cliente" type="file" accept=".jpg" class="form-control-file">
                                        <small class="help-block-none">Apenas imagens jpg.</small>
                                    </div>
                                    <?php if (!empty($empresa->logo_code)): ?>
                                      <div class="col-sm-3">
                                        <div class="avatar">
                                          <img src="./assets/img/logo_empresas/<?=$empresa->logo_code;?>" alt="empresa" class="img-fluid rounded-circle">
                                        </div>
                                      </div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Cliente Code:</label>
                                        <div class="col-sm-9">
                                            <input type="cliente_code" class="form-control" id="cliente_code" name="cliente_code" value="<?=$empresa->cliente_code;?>">
                                            <div id="resposta"></div>
                                            <small class="help-block-none">Código do SGT do cliente (se houver).</small>
                                        </div>
                                    </div>
                                <div class="line"></div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $("#cliente_code").blur(function(){
        $.ajax({
            url: '<?=base_url('verifica_empresa');?>',
            type: 'POST',
            data:{"cliente_code": $("#cliente_code").val()},
            success: function(data) {
                data = $.parseJSON(data);
                //console.log(data);
                if(data.valido == "is"){
                    
                    var input = '<div style="color:#28a745;">'+data.mensagem+'</div>';
                    $("#resposta").html(input);
                    $("#cliente_code").css('border-color', '#28a745');

                } else {

                    var input = '<div style="color:#dc3545">'+data.mensagem+'</div>';
                    $("#resposta").html(input);
                    $('#cliente_code').css('border-color', '#dc3545');
                }
            }
        });
    });
</script>