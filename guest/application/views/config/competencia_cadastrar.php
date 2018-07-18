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
                        <h3 class="h4">Competência Funcionários/Cargos</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?=base_url('cad_competencia');?>" class="form-horizontal">

                            <input type="hidden" name="iddocumento" value="<?=$iddocumento?>">

                            <div class="form-group row">
                                <labe class="col-sm-3 form-control-label">Documento</labe>
                                <div class="col-sm-9">
                                    <?php 
                                    if($documento){
                                        foreach ($documento as $doc) {
                                            ?>
                                            <h4><?=$doc->titulo?></h4>
                                            <?php
                                        }
                                    } else{
                                        ?>
                                        <div class="col-md-12">
                                            <div class="alert alert-warning" role="alert">
                                                Ops! Ocorreu um problema, não conseguimos identificar o documento que você selecionou. Favor entre em contato com o suporte e tente novamente mais tarde.
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Tipo</label>
                                <div class="col-sm-9">
                                    <select name="tipo" id="choice" class="form-control">
                                        <option> -- Selecione -- </option>
                                        <?php 
                                        $sel_1 = "";
                                        $sel_2 = "";
                                        foreach ($competencia_d as $comp) {
                                            if ($comp->tipo == "cargo") {
                                                $sel_2 = "selected=\"selected\"";
                                                $sel_1 = "";
                                            } else {
                                                $sel_1 = "selected=\"selected\"";
                                                $sel_2 = "";
                                            }
                                        }
                                        ?>
                                        <option <?=$sel_1;?> value="funcionario">Funcionários</option>
                                        <option <?=$sel_2;?> value="cargo">Cargos</option>
                                    </select>
                                </div>
                            </div>

                            <div class="line"></div>

                            <h3 class=card-body>Etapas</h3>
                            <?php 
                            if($competencia_d){
                                $i = 0;
                                foreach ($competencia_d as $comp ) {
                                    if ($comp->tipo == "cargo") {
                                        $i++;
                                        ?>
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label"><?=$comp->etapa;?></label>
                                                <input type="hidden" name="etapa_<?=$i?>" value="<?=$comp->fk_idetapa?>">
                                                <div class="col-sm-9">
                                                    <select id="etapa_<?=$i;?>" name="idtipo_<?=$i;?>" class="form-control">
                                                        <?php 
                                                        foreach ($dados_cargo as $cargo ) {
                                                            if($cargo->id == $comp->fk_idcargo){
                                                                $sel = "selected=\"selected\"";
                                                            } else {
                                                                $sel = "";
                                                            }
                                                            ?>
                                                            <option <?=$sel?> value="<?=$cargo->id?>"><?=$cargo->titulo?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    } else {
                                        
                                        $i++;
                                        ?>
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 form-control-label"><?=$comp->etapa;?></label>
                                                <input type="hidden" name="etapa_<?=$i?>" value="<?=$comp->fk_idetapa?>">
                                                <div class="col-sm-9">
                                                    <select id="etapa_<?=$i;?>" name="idtipo_<?=$i;?>" class="form-control">
                                                        <?php
                                                        foreach ($dados_usuario as $user) {
                                                            if ($user->id == $comp->fk_idusuario) {
                                                                $sel = "selected=\"selected\"";
                                                            } else {
                                                                $sel = "";
                                                            }
                                                            ?>
                                                            <option <?=$sel?> value="<?=$user->id?>"><?=$user->nome?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            } else {
                                $i = 0;
                                foreach ($dados_etapa as $etapas) {
                                    $i++;
                                    ?>
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-sm-3 form-control-label"><?=$etapas->etapa;?></label>
                                            <input type="hidden" name="etapa_<?=$i?>" value="<?=$etapas->idetapa?>">
                                            <div class="col-sm-9">
                                                <select id="etapa_<?=$i;?>" name="idtipo_<?=$i;?>" class="form-control">
                                                    <option> - Selecione - </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <input type="hidden" name="quant_etapas" id="quant_etapas" value="<?=$i;?>">
                            <div class="line"></div>
                            <div class="form-group row">
                                <div class="col-sm-6 offset-sm-3">
                                    <a href="<?=base_url('competencia');?>" class="btn btn-sm btn-secondary"><i class="fa fa-backward"></i> Voltar</a>
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

<script  type="text/javascript">

    $(document).ready(function(){
        // Retorna todos os documentos relacionados a aquele grupo
        $('#choice').change(function(e){
            var tipo = $('#choice').val();
            $('#mensagem').html('<span class="mensagem">Aguarde, carregando ...</span>');  
                    
            $.getJSON('<?=base_url();?>'+'tipo_comp/'+tipo, function (dados){ 
                
                if (dados.length > 0){	
                    var option = '<option>Selecione o '+tipo+'</option>';
                    $.each(dados, function(i, obj){
                        option += '<option value="'+obj.id+'">'+obj.nome+'</option>';
                    })
                    $('#mensagem').html('<span class="mensagem">Total de estados encontrados.: '+dados.length+'</span>'); 
                }else{
                    Reset();
                    $('#mensagem').html('<span class="mensagem">Não foram encontrados documentos cadastrados neste grupo!</span>');  
                }
                var total_etapas = $("#quant_etapas").val();
                //console.log(total_etapas);
                for (let i = 0; i <= total_etapas; i++) {
                    $('#etapa_'+i).html(option).show();  
                }
                
            })
        });

    });

</script>
