      <div class="page-content d-flex align-items-stretch">
        <!-- Side Navbar -->
        <nav class="side-navbar">
          <!-- Sidebar Header-->
          <div class="sidebar-header d-flex align-items-center">
            <?php
              foreach ($nome_empresa as $empresa) {
                ?>
                <?php if (!empty($empresa->logo_code)): ?>
                  <div class="avatar">
                    <img src="<?=base_url();?>assets/img/logo_empresas/<?=$empresa->logo_code;?>" alt="<?=$empresa->nome;?>" class="img-fluid rounded-circle">
                  </div>
                <?php else: ?>
                  <div class="avatar">
                    <img src="<?=base_url('assets/img/logo_sgt.png');?>" alt="<?=$empresa->nome;?>" class="img-fluid rounded-circle">
                  </div>
                <?php endif; ?>
                <div class="title">

                  <h1 class="h4"> <?=$empresa->nome;?> </h1>
                  <?php
                }
              ?>
              <p> <?=$_SESSION['nome_user'];?></p>
            </div>
          </div>
          <!-- Sidebar Navidation Menus-->
          <ul class="list-unstyled">

            <li <?=($pg == "Inicial") ? "class='active'" : "" ?> ><a href="<?=base_url('home');?>"> <i class="icon-home"></i>Página Inicial </a></li>

            <!-- Adicionar exceção para apenas modificações de Administrador Master(adminSGT do SGT) -->
            <?php
            if($_SESSION['sgt_admin']){
              ?>
              <li <?=($pg == "controle") ? "class='active'" : "" ?>>
                <a href="#controle" aria-expanded="<?=($pg == "controle") ? "true" : "false" ?>" data-toggle="collapse">
                  <i class="fa fa-check"></i> Controle
                </a>

                <ul id="controle" class="collapse list-unstyled <?=($pg == "controle") ? "show" : "" ?>">
                  <li <?=($submenu == "empresalist") ? "class='active'" : "" ?>> <a href="<?=base_url('controle/');?>"> <i class="fa fa-database"></i> Listar Empresas</a> </li>
                  <li <?=($submenu == "empresacad") ? "class='active'" : "" ?>> <a href="<?=base_url('controle/cadempresa');?>"> <i class="fa fa-user-plus"></i> Cadastrar Empresas</a></li>
                </ul>
              </li>
              <?php
            }
            ?>
            <li>
              <a href="#empresa" aria-expanded="<?=($pg == "empresa") ? "true" : "false" ?>" data-toggle="collapse">
                <i class="fa fa-building"></i> Organização
              </a>

              <ul id="empresa" class="collapse list-unstyled <?=($pg == "empresa") ? "show" : "" ?>">
                
                <li <?=($submenu == "dados") ? "class='active'" : "" ?>><a href="<?=base_url('home/empresa')?>"> <i class="icon-grid"></i>Dados Empresa </a></li>

                <li <?=($submenu == "usuario") ? "class='active'" : "" ?>>
                  <a href="#user" aria-expanded="<?=($submenu == "usuario") ? "true" : "false" ?>" data-toggle="collapse"> 
                    <i class="fa fa-users"></i>Usuários 
                  </a>

                  <ul id="user" class="collapse list-unstyled <?=($submenu == "usuario") ? "show" : "" ?>">
                    <li <?=((isset($sub)) && ($sub == "usuariolist")) ? "class='active'" : "" ?> ><a href="<?=base_url('home/usuario/');?>">Listar</a></li>
                    <?php if(($_SESSION["is_admin"] == true) or ($_SESSION["is_coordenador"] == true)): ?>
                      <li <?=((isset($sub)) && ($sub == "usuariocad")) ? "class='active'" : "" ?> ><a href="<?=base_url('home/usuario_cad/');?>">Cadastrar</a></li>
                    <?php endif; ?>
                  </ul>
                </li>

                <?php if(($_SESSION["is_admin"] == true) or ($_SESSION["is_coordenador"] == true)): ?>
                  <li <?=($submenu == "cargos") ? "class='active'" : "" ?>>
                    <a href="#cargos" aria-expanded="<?=($submenu == "cargos") ? "true" : "false" ?>" data-toggle="collapse"> 
                      <i class="fa fa-server"></i> Cargos
                    </a>

                    <ul id="cargos" class="collapse list-unstyled <?=($submenu == "cargos") ? "show" : "" ?>">
                      <li <?=((isset($sub)) && ($sub == "cargoslist")) ? "class='active'" : "" ?>><a href="<?=base_url('home/cargos');?>"> Listar</a></li>
                      <li <?=((isset($sub)) && ($sub == "cargoscad")) ? "class='active'" : "" ?>><a href="<?=base_url('home/cargos_cad');?>"> Cadastrar</a></li>
                    </ul>
                  </li>

                  <li <?=($submenu == "horarios") ? "class='active'" : "" ?>>
                    <a href="#horario" aria-expanded="<?=($submenu == "horarios") ? "true" : "false" ?>" data-toggle="collapse">
                      <i class="icon-clock"></i>Horários trabalho
                    </a>

                    <ul id="horario" class="collapse list-unstyled <?= ($submenu == "horarios") ? "show" : "" ?>">
                      <li <?=((isset($sub)) && ($sub == "horalist")) ? "class='active'" : "" ?>><a href="<?=base_url('home/horarios');?>"> Listar</a></li>
                      <li <?=((isset($sub)) && ($sub == "horacad")) ? "class='active'" : "" ?>><a href="<?=base_url('home/horarios_cad');?>"> Cadastrar</a></li>
                    </ul>
                  </li>

                  <li <?=($submenu == "feriado") ? "class='active'" : "" ?> data-toggle="collapse">
                    <a href="#feriado" aria-expanded="<?=($submenu == "feriado") ? "true" : "false" ?>" data-toggle="collapse">
                      <i class="fa fa-calendar"></i>Feriados
                    </a>

                    <ul id="feriado" class="collapse list-unstyled <?=($submenu == "feriado") ? "show" : "" ?>">
                      <li <?=((isset($sub)) && ($sub == "feriadolist")) ? "class='active'" : "" ?>><a href="<?=base_url('home/feriado');?>"> Listar</a></li>
                      <li <?=((isset($sub)) && ($sub == "feriadocad")) ? "class='active'" : "" ?>><a href="<?=base_url('home/feriado_cad');?>"> Cadastrar</a></li>
                    </ul>
                  </li>
                <?php endif; ?>
              </ul>
            </li>

            <li>
              <a href="#configuracao" aria-expanded="<?=($pg == "configuracao") ? "true" : "false" ?>" data-toggle="collapse">
                <i class="fa fa-cogs"></i> Configurações
              </a>
              <ul id="configuracao" class="collapse list-unstyled <?=($pg == "configuracao") ? "show" : "" ?>">
                <li <?=($submenu == "grupo") ? "class='active'" : "" ?>>
                  <a href="#grupos" aria-expanded="<?=($submenu == "grupo") ? "true" : "false" ?>" data-toggle="collapse">
                    <i class="fa fa-cubes"></i> Grupos de Documentos
                  </a>

                  <ul id="grupos" class="collapse list-unstyled <?=($submenu == "grupo") ? "show" : "" ?>">
                    <li <?=((isset($sub)) && ($sub == "grupolist")) ? "class='active'" : "" ?>><a href="<?=base_url('grupodocumentos');?>"> Listar</a></li>
                    <li <?=((isset($sub)) && ($sub == "grupocad")) ? "class='active'" : "" ?>><a href="<?=base_url('grupodocumentos_cad');?>"> Cadastrar</a></li>
                  </ul>
                </li>

                <li <?=($submenu == "etapa") ? "class='active'" : "" ?>>
                  <a href="#etapas" aria-expanded="<?=($submenu == "etapa") ? "true" : "false" ?>" data-toggle="collapse">
                    <i class="fa fa-tasks"></i> Etapas
                  </a>

                  <ul id="etapas" class="collapse list-unstyled <?=($submenu == "etapa") ? "show" : "" ?>">
                    <li <?=((isset($sub)) && ($sub == "etapalist")) ? "class='active'" : "" ?>><a href="<?=base_url('etapas');?>"> Listar</a></li>
                    <li <?=((isset($sub)) && ($sub == "etapacad")) ? "class='active'" : "" ?>><a href="<?=base_url('etapas_cad');?>"> Cadastrar</a></li>
                  </ul>
                </li>

                <li <?=($submenu == "documento") ? "class='active'" : "" ?>>
                  <a href="#documento" aria-expanded="<?=($submenu == "documento") ? "true" : "false"?>" data-toggle="collapse">
                    <i class="fa fa-folder"></i> Documentos
                  </a>

                  <ul id="documento" class="collapse list-unstyled <?=($submenu == "documento") ? "show" : ""?>">
                    <li <?=((isset($sub)) && ($sub == "doclist")) ? "class='active'" : "" ?> ><a href="<?=base_url('documentos');?>"> Listar</a></li>
                    <li <?=((isset($sub)) && ($sub == "doccad")) ? "class='active'" : "" ?> ><a href="<?=base_url('documentos_cad');?>"> Cadastrar</a></li>
                  </ul>
                </li>

                <li <?=($submenu == "erro") ? "class='active'" : ""; ?>>
                  <a href="#erro" aria-expanded="<?=($submenu == "erro") ? "true" : "false" ?>" data-toggle="collapse">
                    <i class="fa fa-times-circle"></i> Erros
                  </a>

                  <ul id="erro" class="collapse list-unstyled <?=($submenu == "erro") ? "show" : "" ?>">
                    <li <?=((isset($sub)) && ($sub == "errolist")) ? "class='active'" : "" ?>><a href="<?=base_url('erros');?>"> Listar</a></li>
                    <li <?=((isset($sub)) && ($sub == "errocad")) ? "class='active'" : "" ?>><a href="<?=base_url('erros_cad');?>"> Cadastrar</a></li>
                  </ul>
                </li>

                <li <?=($submenu == "comp") ? "class='active'" : "" ?>> 
                  <a href="<?=base_url('competencia');?>"> 
                    <i class="fa fa-black-tie"></i>Competência 
                  </a>
                </li>

                <!-- Somente acesso de administrador ou coordenador -->
                <?php if(($_SESSION["is_admin"] == true) or ($_SESSION["is_coordenador"] == true)): ?>
                  <li <?=($submenu == "ausencia") ? "class='active'" : "" ?>>
                    <a href="#ausencia" aria-expanded="<?=($submenu == "ausencia") ? "true" : "false" ?>" data-toggle="collapse">
                      <i class="fa fa-user-times"></i> Ausência de Funcionário
                    </a>

                    <ul id="ausencia" class="collapse list-unstyled <?=($submenu == "ausencia") ? "show" : "" ?>">
                      <li <?=((isset($sub)) && ($sub == "ausencialist")) ? "class='active'" : "" ?>><a href="<?=base_url('ausencia_ferias');?>"> Listar</a></li>
                      <li <?=((isset($sub)) && ($sub == "ausenciacad")) ? "class='active'" : "" ?>><a href="<?=base_url('ausencia_ferias_cad');?>"> Cadastrar</a></li>
                    </ul>
                  </li>
                <?php endif;?>
              </ul>
            </li>

            <li <?=($submenu == "novodoc") ? "class='active'" : "" ?>> <a href="<?=base_url('novo_documento');?>"> <i class="fa fa-cloud-upload"></i>Novo Documentos </a></li>

            <li>
              <a href="#documentos" aria-expanded="<?=($pg == "documentos") ? "true" : "false" ?>" data-toggle="collapse">
                <i class="fa fa-folder-open"></i> Documentos
              </a>
              <ul id="documentos" class="collapse list-unstyled <?=($pg == "documentos") ? "show" : "" ?>">
                <li <?=($submenu == "meusdocs") ? "class='active'" : "" ?>> <a href="<?=base_url('meusdocumentos');?>"> <i class="fa fa-archive"></i>Meus Documentos </a></li>
                <li <?=($submenu == "andamento") ? "class='active'" : "" ?>> <a href="<?=base_url('andamento');?>"> <i class="fa fa-battery-half"></i>Em Andamento </a></li>
                <li <?=($submenu == "com_erro") ? "class='active'" : "" ?>> <a href="<?=base_url('erro');?>"> <i class="icon-bill"></i> Documentos com Erro </a></li>
                <li <?=($submenu == "cancelados") ? "class='active'" : "" ?>> <a href="<?=base_url('cancelados');?>"> <i class="fa fa-ban"></i>Documentos Cancelados </a></li>
                <li <?=($submenu == "suspensos") ? "class='active'" : "" ?>> <a href="<?=base_url('suspenso');?>"> <i class="fa fa-exclamation-triangle"></i>Aguardando exigência </a></li>
                <?php if(($_SESSION["is_admin"] == true) or ($_SESSION["is_coordenador"] == true)): ?>
                  <li <?=($submenu == "pendente") ? "class='active'" : "" ?>><a href="<?=base_url('pendentes');?>"><i class="fa fa-hourglass-half"></i> Documentos Pendentes</a></li>
                <?php endif; ?>
              </ul>
            </li>

            <li>
              <a href="#relatorio" aria-expanded="<?=($pg == "relatorio") ? "true" : "false" ?>" data-toggle="collapse">
                <i class="fa fa-list-alt"></i> Relatórios
              </a>

              <ul id="relatorio" class="collapse list-unstyled <?=($pg == "relatorio") ? "show" : "" ?>">
                <?php if(($_SESSION["is_admin"] == true) or ($_SESSION["is_coordenador"] == true)): ?>
                  <li <?=($submenu == "finalizado") ? "class='active'" : "" ?>> <a href="<?=base_url('finalizados');?>"> <i class="fa fa-tags"></i>Finalizados </a></li>
                  
                  <li <?=($submenu == "tempo") ? "class='active'" : "" ?>> 
                    <a href="#tempo_medio" aria-expanded="<?=($submenu == "tempo") ? "true" : "false" ?>" data-toggle="collapse"> 
                      <i class="fa fa-clock-o"></i>Tempo Médio 
                    </a>

                    <ul id="tempo_medio" class="collapse list-unstyled <?=($submenu == "tempo") ? "show" : "" ?>">
                      <li <?=((isset($sub)) && ($sub == "tempgeral")) ? "class='active'" : "" ?>><a href="<?=base_url("tempo_medio");?>"> Geral</a></li>
                      <li <?=((isset($sub)) && ($sub == "tempmensal")) ? "class='active'" : "" ?>><a href="<?=base_url("tempo_mensal");?>"> Mensal</a></li>
                    </ul>
                  </li>
                <?php endif; ?>
                <li <?=($submenu == "produtividade") ? "class='active'" : "" ?>>
                  <a href="#produtividade" aria-expanded="<?=($submenu == "produtividade") ? "true" : "false" ?>" data-toggle="collapse">
                    <i class="fa fa-line-chart"></i> Produtividade
                  </a>

                  <ul id="produtividade" class="collapse list-unstyled <?=($submenu == "produtividade") ? "show" : "" ?>">
                    <?php if((($_SESSION["is_admin"] == true) or ($_SESSION["is_coordenador"] == true))): ?>
                      <li <?=((isset($sub)) && ($sub == "prod_grupo")) ? "class='active'" : "" ?>><a href="<?=base_url("produtividade_grupo");?>"> Grupo</a></li>
                    <?php else: ?>
                      <li <?=((isset($sub)) && ($sub == "individual")) ? "class='active'" : "" ?>><a href="<?=base_url("produtividade_indivudual");?>"> Individual</a></li>
                    <?php endif; ?>
                  </ul>
                </li>
                <?php if(($_SESSION["is_admin"] == true) or ($_SESSION["is_coordenador"] == true)): ?>
                  <li <?=($submenu == "prazos") ? "class='active'" : "";?>><a href="<?=base_url('prazos_documentos');?>"><i class="fa fa-list-alt"></i> Documentos em atraso</a></li>
                <?php endif; ?>
              </ul>
            </li>
          </ul>

        </nav>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <!--<h2 class="no-margin-bottom"><?//=$pagina;?></h2>-->              
                <div class="app-title">
                  <h1><?=$pagina?></h1>
                </div>
                <?php if($submenu != ""): ?>
                  <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><a href="<?=base_url('home');?>"><i class="fa fa-home fa-lg"></i></a></li>
                    <li class="breadcrumb-item"><?=$pagina?></li>
                  </ul>
                <?php endif; ?>
            </div>
          </header>
        
