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
                        <h3 class="h4">Empresa</h3>
                    </div>
                    <div class="card-body">

                        <?=form_open_multipart('cadastrar_empresa');?>

                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Nome Empresa</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="empresa" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fileInput" class="col-sm-3 form-control-label">Logo Empresa</label>
                                <div class="col-sm-9">
                                    <input name="logo_cliente" id="logo_cliente" type="file" accept=".jpg" class="form-control-file">
                                    <small class="help-block-none">Apenas imagens jpg.</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Cliente Code:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="cliente_code" name="cliente_code" required>
                                        <div id="resposta"></div>
                                        <small class="help-block-none">Código do SGT do cliente (se houver).</small>
                                    </div>
                                </div>
                            <div class="line"></div>

                            <h1>Coordenador</h1>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Nome Completo:</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nome" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Email:</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Usuário:</label>
                                <div class="col-sm-9">
                                    <input type="text" name="usuario" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Senha:</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input id="input" type="text" name="senha" class="form-control" aria-describedby="button-addon4" required>
                                        <div class="input-group-append" id="button-addon4">
                                            <span id="ver_senha" class="btn btn-outline-secondary" type="button"><i class="fa fa-eye"></i></span>
                                            <span id="gerar_senha" class="btn btn-outline-secondary" type="button">Gerar senha</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="form-group row">
                                <div class="col-sm-6 offset-sm-3">
                                    <a href="<?=base_url('controle/');?>" class="btn btn-sm btn-secondary"><i class="fa fa-backward"></i> Voltar</a>
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

    $("#ver_senha").click(function(){
        var senha = document.getElementById('input').type;
        
        if (senha == "text") {

            document.getElementById('input').type = "password";
            $("#ver_senha").html('<i class="fa fa-eye-slash"></i>');

        } else {

            document.getElementById('input').type = "text";
            $("#ver_senha").html('<i class="fa fa-eye"></i>');

        }
    });

    $("#gerar_senha").click(function(){
        
        var gerar = '<?=gerar_senha()?>';
        $('#input').val(gerar);

    });
</script>