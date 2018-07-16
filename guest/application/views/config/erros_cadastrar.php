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
                        <h3 class="h4">Erros</h3>
                    </div>
                    <div class="card-body">

                        <form method="post" action="<?=base_url('cad_erros');?>" class="form-horizontal">
                            
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Título</label>
                                <div class="col-sm-9">
                                    <input type="text" name="titulo" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Tipo Erro</label>
                                <div class="col-sm-9">
                                    <select name="tipo_erro" class="form-control" id="tipo_erro" onchange="javascript:cadastro_tipo_erro()" required>
                                        <?php foreach ($tipo_erros as $tipo ) {
                                            ?>
                                            <option value="<?=$tipo->id;?>"><?=$tipo->titulo?></option>
                                            <?php    
                                        } 
                                        ?>
                                        <option value="0"> -- Outro -- </option>
                                    </select>
                                    <div id="novo_erro"></div>
                                </div>
                            </div>

                            <div class="line"></div>
                            <div class="form-group row">
                                <div class="col-sm-6 offset-sm-3">
                                    <a href="<?=base_url('erros');?>" class="btn btn-sm btn-secondary"><i class="fa fa-backward"></i> Voltar</a>
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

    function cadastro_tipo_erro(){
        var tipo = $("#tipo_erro").val();

        if (tipo == 0) {
            var body = '<hr/>';
            body += '<div class="form-group">';
            body += '<label class="form-control-label">Novo tipo de erro:</label>';
            body += '<input type="text" class="form-control" name="novo_tipo">';
            body += '</div>';

            $("#novo_erro").html(body).show();
        } else {
            $("#novo_erro").hide();
        }
    }
</script>