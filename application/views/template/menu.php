      <div class="page-content d-flex align-items-stretch"> 
        <!-- Side Navbar -->
        <nav class="side-navbar">
          <!-- Sidebar Header-->
          <div class="sidebar-header d-flex align-items-center">
            <div class="avatar"><img src="<?=base_url('assets/img/logo_sgt.png');?>" alt="Nome empresa" class="img-fluid rounded-circle"></div>
            <div class="title">
              <?php 
                foreach ($nome_empresa as $empresa) {
                  ?>
                  <h1 class="h4"> <?=$empresa->nome;?> </h1>
                  <?php
                }
              ?>
              <p> <?=$_SESSION['nome_user'];?> </p>
            </div>
          </div>
          <!-- Sidebar Navidation Menus-->
          <!-- Adicionar exceção para apenas modificações de coordenadores -->
          <ul class="list-unstyled">
            <li <?=($pg == "Inicial") ? "class='active'" : "" ?> ><a href="<?=base_url('home');?>"> <i class="icon-home"></i>Página Inicial </a></li>
            
            <?php
            if($_SESSION['is_admin']){
              ?>
              <li <?=($pg == "controle") ? "class='active'" : "" ?>>
                <a href="#controle" aria-expanded="false" data-toggle="collapse">
                  <i class="fa fa-check"></i> Controle
                </a>  

                <ul id="controle" class="collapse list-unstyled">
                  <li <?=($submenu == "empresalist") ? "class='active'" : "" ?>> <a href="<?=base_url('controle/');?>"> <i class="fa fa-database"></i> Listar Empresas</a> </li>
                  <li <?=($submenu == "empresacad") ? "class='active'" : "" ?>> <a href="<?=base_url('controle/cadempresa');?>"> <i class="fa fa-user-plus"></i> Cadastrar Empresas</a></li>
                </ul>
              </li>
              <?php
            }
            ?>
            <li <?=($pg == "empresa") ? "class='active'" : "" ?>>
              <a href="#empresa" aria-expanded="false" data-toggle="collapse">
                <i class="fa fa-building"></i> Organização  
              </a>

              <ul id="empresa" class="collapse list-unstyled">
                <li <?=($submenu == "dados") ? "class='active'" : "" ?>><a href="<?=base_url('home/empresa/'.$_SESSION["idempresa"])?>"> <i class="icon-grid"></i>Dados Empresa </a></li>
                
                <li <?=($submenu == "usuario") ? "class='active'" : "" ?>>
                  <a href="#user" aria-expanded="false" data-toggle="collapse"> <i class="fa fa-users"></i>Usuários </a>
                  
                  <ul id="user" class="collapse list-unstyled ">
                    <li><a href="<?=base_url('home/usuario/');?>">Listar</a></li>
                    <li><a href="<?=base_url('home/usuario_cad/');?>">Cadastrar</a></li>
                  </ul>
                </li>

                <li <?=($submenu == "cargos") ? "class='active'" : "" ?>>
                  <a href="#cargos" aria-expanded="false" data-toggle="collapse"> <i class="fa fa-server"></i>Cargos </a>

                  <ul id="cargos" class="collapse list-unstyled">
                    <li><a href="<?=base_url('home/cargos');?>"> Listar</a></li>
                    <li><a href="<?=base_url('home/cargos_cad');?>"> Cadastrar</a></li>
                  </ul>
                </li>

                <li <?=($submenu == "horarios") ? "class='active'" : "" ?>>
                  <a href="#horario" aria-expanded="false" data-toggle="collapse"> 
                    <i class="icon-clock"></i>Horários trabalho 
                  </a>

                  <ul id="horario" class="collapse list-unstyled">
                    <li><a href="<?=base_url('home/horarios');?>"> Listar</a></li>
                    <li><a href="<?=base_url('home/horarios_cad');?>"> Cadastrar</a></li>
                  </ul>
                </li>

                <li <?=($submenu == "feriado") ? "class='active'" : "" ?> data-toggle="collapse">
                  <a href="#feriado" aria-expanded="false" data-toggle="collapse"> 
                    <i class="fa fa-calendar"></i>Feriados 
                  </a>

                  <ul id="feriado" class="collapse list-unstyled">
                    <li><a href="<?=base_url('home/feriado');?>"> Listar</a></li>
                    <li><a href="<?=base_url('home/feriado_cad');?>"> Cadastrar</a></li>
                  </ul>
                </li>
              </ul>
            </li>
        
            <li  <?=($pg == "documentos") ? "class='active'" : "" ?>>
              <a href="#documentos" aria-expanded="false" data-toggle="collapse">
                <i class="fa fa-folder-open"></i> Documentos
              </a>
              <ul id="documentos" class="collapse list-unstyled">
                <li <?=($submenu == "novodoc") ? "class='active'" : "" ?>> <a href="<?=base_url('novo_documento');?>"> <i class="fa fa-cloud-upload"></i>Novo Documentos </a></li>
                <li <?=($submenu == "meusdocs") ? "class='active'" : "" ?>> <a href="<?=base_url('meusdocumentos');?>"> <i class="fa fa-archive"></i>Meus Documentos </a></li>
                <li <?=($submenu == "andamento") ? "class='active'" : "" ?>> <a href="<?=base_url('home/andamento/'.$_SESSION["idempresa"]);?>"> <i class="fa fa-battery-half"></i>Documentos em Andamento </a></li>
                <li <?=($submenu == "erro") ? "class='active'" : "" ?>> <a href="<?=base_url('home/comerro/'.$_SESSION["idempresa"]);?>"> <i class="fa fa-times-circle-o"></i>Documentos com Erro </a></li>
                <li <?=($submenu == "cancelados") ? "class='active'" : "" ?>> <a href="<?=base_url('home/cancelados/'.$_SESSION["idempresa"]);?>"> <i class="fa fa-stop-circle-o"></i>Documentos Cancelados </a></li>
              </ul>
            </li>
          
            <li <?=($pg == "relatorio") ? "class='active'" : "" ?>>
              <a href="#relatorio" aria-expanded="false" data-toggle="collapse">
                <i class="fa fa-list-alt"></i> Relatórios
              </a>
            
              <ul id="relatorio" class="collapse list-unstyled">
                <li <?=($submenu == "geral") ? "class='active'" : "" ?>> <a href="<?=base_url('home/relgeral/'.$_SESSION["idempresa"]);?>"> <i class="fa fa-tags"></i>Geral </a></li>
                <li <?=($submenu == "tempo") ? "class='active'" : "" ?>> <a href="<?=base_url('home/reltempo/'.$_SESSION["idempresa"]);?>"> <i class="fa fa-clock-o"></i>Tempo Médio </a></li>
                <li <?=($submenu == "atendimento") ? "class='active'" : "" ?>> <a href="<?=base_url('home/relatendimento/'.$_SESSION["idempresa"]);?>"> <i class="fa fa-file-text-o"></i>Documentos em Atendimentos por dia </a></li>
                <li <?=($submenu == "atendente") ? "class='active'" : "" ?>> <a href="<?=base_url('home/relatendente/'.$_SESSION["idempresa"]);?>"> <i class="fa fa-address-card"></i>Documentos Atendentes por dia </a></li>
              </ul>
            </li>

            <li <?=($pg == "configuracao") ? "class='active'" : "" ?>>
              <a href="#configuracao" aria-expanded="false" data-toggle="collapse">
                <i class="fa fa-cogs"></i> Configurações
              </a>
              <ul id="configuracao" class="collapse list-unstyled">
                <li <?=($submenu == "grupo") ? "class='active'" : "" ?>> 
                  <a href="#grupos" aria-expanded="false" data-toggle="collapse"> 
                    <i class="fa fa-cubes"></i> Grupos de Documentos
                  </a>

                  <ul id="grupos" class="collapse list-unstyled">
                    <li><a href="<?=base_url('grupodocumentos');?>"> Listar</a></li>
                    <li><a href="<?=base_url('grupodocumentos_cad');?>"> Cadastrar</a></li>
                  </ul>
                </li>
                
                <li <?=($submenu == "etapa") ? "class='active'" : "" ?>> 
                  <a href="#etapas" aria-expanded="false" data-toggle="collapse"> 
                    <i class="fa fa-tasks"></i> Etapas
                  </a>

                  <ul id="etapas" class="collapse list-unstyled">
                    <li><a href="<?=base_url('etapas');?>"> Listar</a></li>
                    <li><a href="<?=base_url('etapas_cad');?>"> Cadastrar</a></li>
                  </ul>
                </li>

                <li <?=($submenu == "documento") ? "class='active'" : "" ?>> 
                  <a href="#documento" aria-expanded="false" data-toggle="collapse"> 
                    <i class="fa fa-folder"></i> Documentos
                  </a>

                  <ul id="documento" class="collapse list-unstyled">
                    <li><a href="<?=base_url('documentos');?>"> Listar</a></li>
                    <li><a href="<?=base_url('documentos_cad');?>"> Cadastrar</a></li>
                  </ul>
                </li>

                <li <?=($submenu == "comp") ? "class='active'" : "" ?>> <a href="<?=base_url('competencia');?>"> <i class="fa fa-black-tie"></i>Competência </a></li>
                
                <!-- adicionar if para somente coordenadores terem esta opção -->
                <li <?=($submenu == "ausencia") ? "class='active'" : "" ?>> 
                  <a href="#ausencia" aria-expanded="false" data-toggle="collapse"> 
                    <i class="fa fa-user-times"></i> Ausência de Funcionário
                  </a>

                  <ul id="ausencia" class="collapse list-unstyled">
                    <li><a href="<?=base_url('ausencia_ferias');?>"> Listar</a></li>
                    <li><a href="<?=base_url('ausencia_ferias_cad');?>"> Cadastrar</a></li>
                  </ul>
                </li>
              </ul>
            </li>
          </ul>
          
        </nav>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom"><?=$pagina;?></h2>
            </div>
          </header>