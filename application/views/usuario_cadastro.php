<section class="forms"> 
    <div class="container-fluid">

        <?php if (validation_errors()) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= validation_errors() ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error') == TRUE): ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('success') == TRUE): ?>
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('warning') == TRUE): ?>
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert">
                    <?=$this->session->flashdata('warning');?>
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
                        <form method="post" action="<?=base_url('cad_usuario');?>" class="form-horizontal" enctype="multipart/form-data">

                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Nome Completo</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nome">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fileInput" class="col-sm-3 form-control-label">Email:</label>
                                <div class="col-sm-9">
                                    <input name="email" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Cargo:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="cargo" id="cargo">
                                        <?php 
                                        foreach ($full_cargos as $cargo) {
                                            ?>
                                            <option value="<?=$cargo->id;?>"><?=$cargo->titulo;?></option>
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
                                            $m_e = explode(":", $horas->manha_entrada);
                                            $m_s = explode(":", $horas->manha_saida);
                                            $t_e = explode(":", $horas->tarde_entrada);
                                            $t_s = explode(":", $horas->tarde_saida);
                                            ?>
                                            <option value="<?=$horas->id;?>"><?=$horas->titulo .": ".$m_e[0].":".$m_e[1]."-".$m_s[0].":".$m_s[1]." / ".$t_e[0].":".$t_e[1]."-".$t_s[0].":".$t_s[1];?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div style="text-align: right;">
                                <div class="btn-group" role="group" style="text-align: right;" aria-label="Button group with nested dropdown">
                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Adicionar POP (opcional)
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <a class="dropdown-item" id="campo-texto" href="javascript:void(0)">Link POP SGT - Gestão da Qualidade</a>
                                            <a class="dropdown-item" id="campo-arquivo" href="javascript:void(0)">Carregar Arquivo</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="line"></div>
                            
                            <input type="hidden" name="tipo_pop" id="tipo_pop" value="none">
                            <input type="hidden" name="qnt_pop" id="qnt_pop" value="0">

                            <div class="form-group row" id="novo_campo"></div>
                            <div id='other_inputs'></div>
                            <div class='form-group row' id='botoes'></div>

                            <div class="line"></div>
                                    
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Usuário:</label>
                                <div class="col-sm-9">
                                    <input type="text" id="usuario" name="usuario" class="form-control">
                                    <div id="resposta"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Senha:</label>
                                <div class="col-sm-9">
                                    
                                    <div class="input-group">
                                        <input id="input" type="text" name="senha" class="form-control" aria-describedby="button-addon4">
                                        <div class="input-group-append" id="button-addon4">
                                            <span id="ver_senha" class="btn btn-outline-secondary" type="button"><i class="fa fa-eye"></i></span>
                                            <span id="gerar_senha" class="btn btn-outline-secondary" type="button">Gerar senha</span>
                                        </div>
                                    </div>
                                    <!--<div class="input-group">
                                        <input type="password" name="senha" class="form-control">    
                                        <span class="input-group-text"><i class="fa fa-eye"></i></span>
                                        <span class="input-group-text">Gerar senha</span>
                                    </div>-->
                                    <small style="color:#696969;" class="help-block-none">A senha deve conter pelo menos 6 caracteres. Para uma senha mais segura mescle números, letras e caracteres especiais</small>
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="form-group row">
                                <div class="col-sm-6 offset-sm-3">
                                    <a href="<?=base_url('home/usuario');?>" class="btn btn-sm btn-secondary"><i class="fa fa-backward"></i> Voltar</a>
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
<script src="https://code.jquery.com/jquery.js"></script>
<script>
    $('#usuario').blur(function() { 
        
        $.ajax({ 
            url: '<?=base_url();?>verifica_usuario/', 
            type: 'POST', 
            data:{"usuario" : $('#usuario').val()}, 
            success: function(data) { 
                data = $.parseJSON(data); 
                //console.log(data); 
                if(data.valido == "not"){
                    var input = '<div style="color:#dc3545;">'+data.mensagem+'</div>';
                    $("#resposta").html(input);
                    $("#usuario").css('border-color', '#dc3545');
                } else {
                    var input = '<div style="color:#28a745;">'+data.mensagem+'</div>';
                    $("#resposta").html(input);
                    $("#usuario").css('border-color', '#28a745');
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

    $("#campo-texto").click(function(){

        if (document.querySelectorAll('.pop_file').length) {
     
            var elementos = $(".pop_file").length;

            for (let i = 1; i <= parseInt(elementos); i++) {
                var elemento1 = 'pop_'+i;
                //console.log('label_pop_'+i);
                var oldElements = document.getElementById(elemento1);

                if (oldElements.parentNode) {
                    oldElements.parentNode.removeChild(oldElements);
                }

                var labelElements = document.getElementById('label_pop_'+i);

                if(labelElements.parentNode){
                    labelElements.parentNode.removeChild(labelElements);
                }

                var botoes = document.getElementById('botoes').innerHTML = "";
                
            }
        
        }
        
        var text = "<label class='col-sm-3 form-control-label' id='label_pop_1'>POP:</label>"+
                    "<div class='col-sm-9'><input type='text' name='pop_1' id='pop_1' class='form-control pop_file' maxlength='250'></div>";
        $("#novo_campo").html(text);

        var botao = "<a href='javascript:void(0)' onclick='adiciona_pop()' style='color:white;' class='btn btn-sm btn-warning'>Inserir POP <i class='fa fa-plus'></i></a></div>";                    
        $("#botoes").html(botao);
        
        $("#tipo_pop").val("texto");
        $("#qnt_pop").val(1);

    });

    $("#campo-arquivo").click(function(){
        
        if (document.querySelectorAll('.pop_file').length) {
     
            var elementos = $(".pop_file").length;

            for (let i = 1; i <= parseInt(elementos); i++) {
                
                var elemento1 = 'pop_'+i;
                //console.log('label_pop_'+i);
                var oldElements = document.getElementById(elemento1);

                if (oldElements.parentNode) {
                    oldElements.parentNode.removeChild(oldElements);
                }

                var labelElements = document.getElementById('label_pop_'+i);

                if(labelElements.parentNode){
                    labelElements.parentNode.removeChild(labelElements);
                }

                var botoes = document.getElementById('botoes').innerHTML = "";
                
            }
        
        }
 

        var text = "<label class='col-sm-3 form-control-label' id='label_pop_1'>POP:</label>"+
                    "<div class='col-sm-9'><input type='file' name='pop_1' id='pop_1' class='form-control pop_file'></div>";
        $("#novo_campo").html(text);
        
        var botao = "<a href='javascript:void(0)' onclick='adiciona_pop()' style='color:white;' class='btn btn-sm btn-warning'>Inserir POP <i class='fa fa-plus'></i></a></div>";                    
        $("#botoes").html(botao);
        
        $("#tipo_pop").val("arquivo");
        $("#qnt_pop").val(1);

    });

    function adiciona_pop(){

        /*var elementos = document.getElementsByName('pop').length;*/
        var elementos = $(".pop_file").length;
        var tipo = "pop_"+elementos;
        
        elementos = parseInt(elementos) + 1;
        
        //console.log('Existem ' + elementos);
        

        var pop = document.getElementById(tipo).type;

        if(pop == 'text'){
            
            var div = document.createElement('div');
            div.setAttribute('class', 'form-group row');
            
            var label = document.createElement('label');
            label.setAttribute('class', 'col-sm-3 form-control-label');
            label.setAttribute('id', 'label_pop_'+elementos);

            var txtLabel = document.createTextNode('POP: ');
            label.appendChild(txtLabel);

            var divInput = document.createElement('div');
            divInput.setAttribute('class', 'col-sm-9');
            
            var input = document.createElement('input');
            input.setAttribute('type', 'text');
            input.setAttribute('name', 'pop_'+elementos);
            input.setAttribute('id', 'pop_'+elementos);
            input.setAttribute('class', 'form-control pop_file');
            input.setAttribute('maxlength', '250');

            divInput.appendChild(input);

            div.appendChild(label);
            div.appendChild(divInput);
            
            var panel = document.getElementById("other_inputs");

            panel.appendChild(div);
                                
        } else if(pop == 'file'){

            var div = document.createElement('div');
            div.setAttribute('class', 'form-group row');

            var label = document.createElement('label');
            label.setAttribute('class', 'col-sm-3 form-control-label');
            label.setAttribute('id', 'label_pop_'+elementos);

            var txtLabel = document.createTextNode('POP: ');
            label.appendChild(txtLabel);

            var divInput = document.createElement('div');
            divInput.setAttribute('class', 'col-sm-9');

            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('name', 'pop_'+elementos);
            input.setAttribute('id', 'pop_'+elementos);
            input.setAttribute('class', 'form-control pop_file');

            divInput.appendChild(input);

            div.appendChild(label);
            div.appendChild(divInput);

            var panel = document.getElementById('other_inputs');
            panel.appendChild(div);

        }

        var buttons = "<a href='javascript:void(0)' onclick='adiciona_pop()' style='color:white;' class='btn btn-sm btn-warning'>Inserir POP <i class='fa fa-plus'></i></a>&nbsp;"+
                    "<a href='javascript:void(0)' onclick='remove_pop()' class='btn btn-sm btn-danger'>Remover POP <i class='fa fa-trash'></i></a>";
        
        $('#qnt_pop').val(elementos);
        $("#botoes").html(buttons);

    };

    function remove_pop(){

        var elementos = $('.pop_file').length;
        var remove = "pop_"+elementos;
        console.log(remove);
        //console.log("Deletar: " + elementos);
        
        var oldElements = document.getElementById(remove);

        if (oldElements.parentNode) {
            oldElements.parentNode.removeChild(oldElements);
        }

        var labelElements = document.getElementById('label_pop_'+elementos);

        if(labelElements.parentNode){
            labelElements.parentNode.removeChild(labelElements);
        }

        $('#qnt_pop').val(elementos);

    }

</script>