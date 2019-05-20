<section class="tables">   
    <div class="container-fluid">
        <?php if (validation_errors()) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= validation_errors() ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error') == TRUE) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('success') == TRUE) : ?>
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('warning') == TRUE) : ?>
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert">
                    <?= $this->session->flashdata('warning'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error_date') == TRUE): ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?=$this->session->flashdata('error_date');?>
                </div>
            </div>
        <?php endif; ?>
        <div id="data_erro"></div>
        <div class="row">

            <div class="col-lg-12">
    
                <div class="card">
        
                    <div class="card-header d-flex align-items-center">
                        <h3 class="h4">Novo Documento</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?=base_url("cad_novo_doc");?>" method="post">
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Protocolo</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="protocolo" onkeydown="upperCaseF(this)" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Número de atos</label>
                                <div class="col-sm-9">
                                    <input type="number" name="atos" id="numero_atos" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Grupo</label>
                                <div class="col-sm-9">
                                    <select name="grupo" id="idgrupo" class="form-control" required>
                                        <option> --Selecione-- </option>
                                        <?php 
                                        foreach ($grupo_dados as $grupos) {
                                            ?>
                                            <option value="<?=$grupos->id;?>"><?=$grupos->titulo;?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Documento</label>
                                <div class="col-sm-9">
                                    <select name="documento" id="sel_docs" class="form-control" >
                                        <option>Carregar documentos ... </option>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" id="if_prazos" name="prazos" value="0">
                            
                            <div class="form-group row" id="addprazo"></div>

                            <div id="p_doc" class="row" style="display:none;">
                                <label class="col-sm-3 form-control-label">Prazos</label>
                                <div class="col-sm-9" id="botaoprazo">
                                    <a href="javascript:void(0)" id="prazos" class="btn btn-sm btn-success"><i class="fa fa-plus-circle"></i> Adicionar Prazos</a>
                                </div>
                            </div>
                            
                            <br/>
                            <input type="hidden" name="data_atual" id="data_atual" value="<?=date('Y-m-d');?>">
                            <div class="form-group row">
                                <div class="col-sm-6 offset-sm-3">
                                    <a href="<?=base_url('meusdocumentos');?>" class="btn btn-sm btn-secondary"><i class="fa fa-backward"></i> Voltar</a>
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
        $('#idgrupo').change(function(e){
            var idgrupo = $('#idgrupo').val();
            $('#mensagem').html('<span class="mensagem">Aguarde, carregando ...</span>');  
            
            $.getJSON('<?=base_url();?>'+'find_doc/'+idgrupo, function (dados){ 
            
                if (dados.length > 0){	
                    var option = '<option>Selecione o Documento</option>';
                    $.each(dados, function(i, obj){
                        option += '<option value="'+obj.id+'">'+obj.titulo+'</option>';
                    })
                    $('#mensagem').html('<span class="mensagem">Total de estados encontrados.: '+dados.length+'</span>'); 
                }else{
                    var option = '<option>Não foram encontrados documentos cadastrados neste grupo!</option>';
                }
                $('#sel_docs').html(option).show(); 
                $("#p_doc").show();
            })
        });

        // Retorna todas as etapas do documento para a inserção de seus prazos
        $('#prazos').click(function(e){

            var j = 0;

            var iddocumento = $('#sel_docs').val();

            $('#mensagem').html('<span class="mensagem"> Aguarde, carregando ...</span>');

            //$('#botaoprazo').html('<a href="javascript:void(0)" class="btn btn-sm btn-danger" id="rmvPrazo"><i class="fa fa-trash"></i> Remover Prazos</a>');

            $('#p_doc').hide();

            $.getJSON('<?=base_url();?>'+'find_steps/'+iddocumento, function (dados){
                if (dados.length>0) {
                    var time = new Date();
                    console.log(time);
                    
                    var option = '<div class="col-sm-12" id="steps">';
                    $.each(dados, function(i, obj){
                        j++;
                        option += '<div class="form-group row">';
                        option +=   '<input type="hidden" name="etapas['+j+']" value="'+obj.id+'">';
                        option +=   '<label class="col-sm-3 form-control-label">'+obj.titulo+'</label>';
                        //outraData.setDate(time.getDate() + obj.prazo_def);
                        var outraData = new Date(time.getTime() + (obj.prazo_def * 24 * 60 * 60 * 1000));
                        if((outraData.getMonth()+1) < 10){
                            var mes = "0" + (outraData.getMonth()+1);
                        } else {
                            var mes = (outraData.getMonth()+1);
                        }

                        if(outraData.getDate() < 10){
                            var dia = "0" + outraData.getDate();
                        } else {
                            var dia = outraData.getDate();
                        }

                        var data = outraData.getFullYear()+"-"+ mes +"-"+dia;
                        //console.log(outraData);
                        if(obj.prazo_def == null){
                            data = "";
                        }
                        option +=   '<div class="col-sm-9">';
                        option +=       '<input type="date" id="prazo_etapa_'+j+'" value="'+data+'" name="prazo['+j+']" class="form-control">';
                        option +=       '<div id="mensagem_'+j+'"></div>';
                        option +=   '</div>';
                        option += '</div>';
                        time = outraData;
                    });
                    console.log("Total de etapas "+dados.length+"!");
                    
                } else {
                    reset();
                    $('#mensagem').html('<span class="mensagem">Não foram encontrados documentos cadastrados neste grupo!</span>');
                }
                $('#addprazo').html(option).show();

                $.getJSON('<?=base_url();?>'+'find_deadline/'+iddocumento, function (dados){
                    var option = '<div class="col-sm-12" id="deadline">';
                    var dataIni = new Date();
                    if(dados.length > 0){
                        $.each(dados, function(i, obj2){
                            var dataFinal = new Date(dataIni.getTime() + (obj2.prazo_final * 24 * 60 * 60 * 1000)); 
                            console.log(obj2.prazo_final);    
                            if((dataFinal.getMonth()+1) < 10){
                                var mes = "0" + (dataFinal.getMonth()+1);
                            } else {
                                var mes = (dataFinal.getMonth()+1);
                            }
                            if(dataFinal.getDate() < 10){
                                var dia = "0" + dataFinal.getDate();
                            } else {
                                var dia = dataFinal.getDate();
                            }
                            
                            if(obj2.prazo_final == null){
                                var prazo = "";
                            } else {
                                var prazo = dataFinal.getFullYear()+"-"+ mes +"-"+dia;
                            }
                            //console.log(prazo);
                            option += '<div class="line"></div>';
                            option +=   '<hr>';
                            option +=   '<div class="form-group col-sm-12 row">';
                            option +=       '<label class="form-control-label col-sm-3"> <strong>Prazo Final do documento</strong></label>';
                            option +=       '<div class="col-sm-9">';
                            option +=           '<input type="date" name="prazo_final" value="'+prazo+'" id="prazo_final" class="form-control">';
                            option +=           '<div id="mensagem"></div>';
                            option +=       '</div>';
                            option +=   '</div>';
                            option +=   '<input type="hidden" name="prazo_etapa" value="'+dados.length+'">';
                            option += "</div>";
                        });
                    } else {
                        option += '<div class="line"></div>';
                        option +=   '<hr>';
                        option +=   '<div class="form-group row">';
                        option +=       '<label class="form-control-label col-sm-3"> <strong>Prazo Final do documento</strong></label>';
                        option +=       '<div class="col-sm-9">';
                        option +=           '<input type="date" name="prazo_final" id="prazo_final" class="form-control">';
                        option +=           '<div id="mensagem"></div>';
                        option +=       '</div>';
                        option +=   '</div>';
                        option +=   '<input type="hidden" name="prazo_etapa" value="'+dados.length+'">';
                        option += "</div>";
                    }
                    option += "</div>";

                    $('#if_prazos').val('1');

                    var main = document.getElementById('addprazo');
                    main.insertAdjacentHTML('beforeend', option);
                });

                var quantidade = dados.length;
                var data_hoje = $("#data_atual").val();

                var data_erro = '<div class="col-md-12">';
                    data_erro+=     '<div class="alert alert-danger" role="alert">';
                    data_erro+=         'As datas de prazo não podem ser inferior à data de criação do documento!';
                    data_erro+=     '</div>';
                    data_erro+= '</div>';
                
                for (let index = 1; index <= quantidade; index++) {
                    $("#prazo_etapa_"+index).blur(function (){
                        var prazo = $("#prazo_etapa_"+index).val();

                        if(prazo < data_hoje){                            
                            //console.log(data_hoje);
                            //console.log(prazo);
                            $("#data_erro").html(data_erro);
                            $("#prazo_etapa_"+index).css('border-color', '#dc3545');
                            var input = '<div style="color:#dc3545;">Data Inválida!</div>';
                            $("#mensagem_"+index).html(input);
                            //console.log("Deu barros!!!");

                        } else {
                            //console.log(data_hoje);
                            //console.log(prazo);
                            //console.log("Tá beleza");
                            $("#data_erro").html("");
                            $("#prazo_etapa_"+index).css('border-color', '#28a745');
                            var input = '<div style="color:#28a745;">Data Válida!</div>';
                            $("#mensagem_"+index).html(input);
                        }
                        
                    });
                }
                
                $("#prazo_final").blur(function(){
                    var prazo_total = $("#prazo_final").val();

                    if(prazo_total < data_hoje){
                        $("#data_erro").html(data_erro);
                        $("#prazo_final").css('border-color', '#dc3545');
                        var input = '<div style="color:#dc3545;">Data Inválida!</div>';
                        $("#mensagem").html(input);
                    } else {
                        $("#data_erro").html("");
                        $("#prazo_final").css('border-color', '#28a745');
                        var input = '<div style="color:#28a745;">Data Válida!</div>';
                        $("#mensagem").html(input);
                    }

                });
                
            });
        });

        $("#sel_docs").change(function(){
            if (document.querySelectorAll('#steps').length) {
                $('#addprazo').html("").show();   
                $('#if_prazos').val('0');                             
            }

            $("#p_doc").show();

        });

    });

</script>
