<section class="forms"> 
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

        <div class="row">
            <!-- Form Elements -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="h4">Documento</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?=base_url('cad_documento');?>" class="form-horizontal">
                            
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Titulo</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="titulo">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Grupo</label>
                                <div class="col-sm-9">
                                    <select name="grupo" id="grupo" class="form-control">
                                        <option value=""> -- Selecione -- </option>
                                        <?php
                                        foreach ($listar_grupos as $grupo) {
                                            ?>
                                            <option value="<?=$grupo->id?>"><?=$grupo->titulo;?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Documento com prazo final fixo?</label>
                                <div class="col-sm-9" id="prazoDoc">
                                    <div class="toggle-flip">
                                        <label>
                                            <input name="prazo_doc" id="prazo_doc" class="prazo_doc" onclick="javascript:addPrazoDoc()" type="checkbox"><span class="flip-indecator" data-toggle-on="SIM" data-toggle-off="NÃO"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">1ª Etapa</label>
                                <div class="col-sm-9">
                                    <select name="etapa[1]" id="etapa_1" class="form-control">
                                        <option value=""> -- Selecione -- </option>
                                        <?php
                                        foreach ($listar_etapas as $etapa) {
                                            ?>
                                            <option value="<?=$etapa->id;?>"><?=$etapa->titulo?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Etapa com prazo fixo?</label>
                                <div class="col-sm-9" id="prazo_1">
                                    <div class="toggle-flip">
                                        <label>
                                            <input name="prazo_fx[1]" id="prazo_fx_1" class="prazo_fx" onclick="javascript:addPrazos(1)" type="checkbox"><span class="flip-indecator" data-toggle-on="SIM" data-toggle-off="NÃO"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="idetapas"></div>
                            
                            <div class="form-group row" id="add_steps">
                                <input type="hidden" value="1" id="total" name="total">
                                <a href="javascript:void(0)" onclick="javascript:addEtapa()" class="btn btn-sm btn-success"><i class="fa fa-plus-circle"></i> Adicionar Etapas</a>
                            </div>
                        
                            <div class="line"></div>
                            <div class="form-group row">
                                <div class="col-sm-6 offset-sm-3">
                                    <a href="<?=base_url('documentos');?>" class="btn btn-sm btn-secondary"><i class="fa fa-backward"></i> Voltar</a>
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
    //Novo item
    function n_item(){
        return parseInt($('#total').val())+1;
    }

    //item anterior
    function p_item(){
        return parseInt($('#total').val())-1; 
    }

    //Total de itens
    function t_item(){
        return parseInt($('#total').val());
    }

    function addEtapa(){

        var elemento_pai = document.getElementById('idetapas');

        var divMain = document.createElement('div');
        divMain.setAttribute('class', 'form-group row');
        divMain.setAttribute('id', 'elemento_'+n_item());
        
        var label = document.createElement('label');
        label.setAttribute('class', 'col-sm-3 form-control-label');
        var txtLabel = document.createTextNode(n_item()+'ª Etapa');
        label.appendChild(txtLabel);

        var div = document.createElement('div');
        div.setAttribute('class', 'col-sm-9');
        var select = document.createElement('select');
        select.setAttribute('class', 'form-control');
        select.setAttribute('name', 'etapa['+n_item()+']');
        select.setAttribute('id', 'etapa_'+n_item());
        var option = document.createElement('option');
        option.setAttribute('value', 'nda');
        var txtOption = document.createTextNode('-- Selecione --');
        option.appendChild(txtOption);
        select.appendChild(option);
        <?php 
            foreach ($listar_etapas as $etapa) {
            ?>
                var option = document.createElement('option');
                option.setAttribute('value', '<?=$etapa->id;?>');
                var txtOption = document.createTextNode('<?=$etapa->titulo;?>');
                option.appendChild(txtOption);
                select.appendChild(option);
                <?php
            }
        ?>

        divMain.appendChild(label);
        div.appendChild(select);
        divMain.appendChild(div);
        
        var divPrazo = document.createElement('div');
        divPrazo.setAttribute("class", "form-group row");
        divPrazo.setAttribute("id", "prazoetapa_"+n_item());

        var labelPrazos = document.createElement('label');
        labelPrazos.setAttribute('class', "col-sm-3 form-control-label");
        var txtLabelPrazos = document.createTextNode('Etapa com prazo fixo?');
        labelPrazos.appendChild(txtLabelPrazos);

        var divMainPrazos = document.createElement("div");
        divMainPrazos.setAttribute("class", "col-sm-9");
        divMainPrazos.setAttribute("id", "prazo_"+n_item());

        var divToggle = document.createElement('div');
        divToggle.setAttribute('class', 'toggle-flip');
        
        var labelToggle = document.createElement('label');

        var inputPrazos = document.createElement('input');
        inputPrazos.setAttribute('name', "prazo_fx["+n_item()+"]");
        inputPrazos.setAttribute('class', 'prazo_fx');
        inputPrazos.setAttribute('id', 'prazo_fx_'+n_item());
        inputPrazos.setAttribute('onclick', 'addPrazos('+n_item()+')');
        inputPrazos.setAttribute('type', 'checkbox');
        
        var spanPrazos = document.createElement('span');
        spanPrazos.setAttribute('class', 'flip-indecator');
        spanPrazos.setAttribute('data-toggle-on', 'SIM');
        spanPrazos.setAttribute('data-toggle-off', 'NÃO');

        labelToggle.appendChild(inputPrazos);
        labelToggle.appendChild(spanPrazos);

        divToggle.appendChild(labelToggle);
        divMainPrazos.appendChild(divToggle);

        divPrazo.appendChild(labelPrazos);
        divPrazo.appendChild(divMainPrazos);

        elemento_pai.appendChild(divMain);
        elemento_pai.appendChild(divPrazo);

        $("#total").val(n_item());

        newButton();

    }

    function newButton(){
        
        if(document.getElementById('btn_delets')){

            if(t_item() <= 1){

                var main = document.getElementById('add_steps');

                var element = document.getElementById('btn_delets');

                main.removeChild(element);
            }

        } else {

            var main = document.getElementById('add_steps');

            var button = document.createElement('a');
            button.setAttribute('id', 'btn_delets');
            button.setAttribute('style', 'padding-left: 10px');
            button.setAttribute('href', 'javascript:void(0)');
            button.setAttribute('onclick', 'rmvEtapa()');
            button.setAttribute('class', 'btn btn-sm btn-danger');
            var icon = document.createElement('i');
            icon.setAttribute('class', 'fa fa-trash');
            var txtButton = document.createTextNode(' Remover Etapa');
            button.appendChild(icon);
            button.appendChild(txtButton);

            main.appendChild(button);
        }


    }

    function rmvEtapa(){

        var main = document.getElementById('idetapas');

        var old_element = document.getElementById('elemento_'+t_item());
        var old_prazo = document.getElementById("prazoetapa_"+t_item());

        main.removeChild(old_element);
        main.removeChild(old_prazo);

        $("#total").val(p_item());

        newButton();

    }

    function addPrazos(num){

        var selector = 'prazo_fx_'+num;
        //var pacote = document.querySelectorAll('[name='+selector+']:checked');
        var pacote = document.querySelectorAll('[id='+selector+']:checked');
        var values = [];
        for (var i = 0; i < pacote.length; i++) {
            // utilize o valor aqui, adicionei ao array para exemplo
            values.push(pacote[i].value);
        }
        //console.log(values);
        if(values == "on"){

            var dias = "<div id='prazo_fixo_"+num+"' class='row form-group col-sm-12'><input name='prazo["+num+"]' class='form-control col-sm-10' type='number'> &nbsp;dias</div>";
            var main = document.getElementById('prazo_'+num);
            main.insertAdjacentHTML('beforeend', dias);

        } else {

            var elemento = document.getElementById('prazo_fixo_'+num);

            if(elemento.parentNode){
                elemento.parentNode.removeChild(elemento);
            }

        }

    }

    function addPrazoDoc(num){

        var pacote = document.querySelectorAll('[id=prazo_doc]:checked');
        var values = [];
        for (var i = 0; i < pacote.length; i++) {
            // utilize o valor aqui, adicionei ao array para exemplo
            values.push(pacote[i].value);
        }
        //console.log(values);
        if(values == "on"){

            var dias = "<div id='prazo_final' class='row form-group col-sm-12'><input name='prazo_final' class='form-control col-sm-10' type='number'> &nbsp;dias</div>";
            var main = document.getElementById('prazoDoc');
            main.insertAdjacentHTML('beforeend', dias);

        } else {

            var elemento = document.getElementById('prazo_final');

            if(elemento.parentNode){
                elemento.parentNode.removeChild(elemento);
            }

        }

    }
</script> 