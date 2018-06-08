<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Rotas referentes a empresa (Controle Administrador)
$route['controle']                  = "admin/controle";
$route['controle/cadempresa']       = "admin/controle/pagina_cadastro";
$route['editar_empresa']            = "admin/controle/editar_empresa";
$route['excluir_empresa']           = "admin/controle/excluir_empresa";
$route['empresa_edit']              = "admin/controle/empresa_editar";
$route['cadastrar_empresa']         = "admin/controle/cadastrar_empresa";
$route['alt_empresa']               = "admin/controle/altera_dadosempresa";

//Rota de alteração de senha (Controller Usuario.php)
$route["alterar_senha"]  = "usuario/alterar_senha";

// Rotas referentes ao login
$route['login_auth'] = "login/login";
$route['logout']     = "login/logout";

// Rotas referentes aos usuarios
$route['cad_usuario']      = "usuario/cadastro_usuario";
$route['editar_usuario']   = "home/editar_usuario";
$route['edit_usuario']     = "usuario/editar_usuario";
$route['excluir_usuario']  = "usuario/excluir_usuario";

// Rotas do referentes aos cargos
$route['cad_cargo']      = "cargos/cadastrar_cargo";
$route['editar_cargo']   = "cargos/editar_cargo_pagina";
$route['edit_cargo']     = "cargos/editar_cargo";
$route['excluir_cargo']  = "cargos/excluir_cargo";

// Rotas referentes aos Horarios
$route['cad_horario']     = "horarios/cadastrar_horario";
$route['editar_horario']  = "horarios/editar_horario_pagina";
$route['edit_horario']    = "horarios/editar_horario";
$route['excluir_horario'] = "horarios/excluir_horario";

// Rotas referentes aos Feriados
$route['cad_feriado']     = "feriados/cadastrar_feriados";
$route['editar_feriado']  = "feriados/editar_feriados_pagina";
$route['edit_feriado']    = "feriados/editar_feriados";
$route['excluir_feriado'] = "feriados/excluir_feriados";

// Rotas referentes aos arquivos da pasta controller conf
$route["grupodocumentos"]            = "conf/grupos";
$route["grupodocumentos_cad"]        = "conf/grupos/cadastro";
$route["cad_grupo"]                  = "conf/grupos/cadastrar_grupo";
$route["editar_grupo"]               = "conf/grupos/editar_grupo_pagina";
$route["edit_grupos"]                = "conf/grupos/editar_grupo";
$route["excluir_grupo"]              = "conf/grupos/excluir_grupo";
$route["etapas"]                     = "conf/etapas";
$route["etapas_cad"]                 = "conf/etapas/cadastro";
$route["cad_etapa"]                  = "conf/etapas/cadastrar_etapas";
$route["editar_etapa"]               = "conf/etapas/editar_etapas_pagina";
$route["edit_etapas"]                = "conf/etapas/editar_etapas";
$route["excluir_etapa"]              = "conf/etapas/excluir_etapas";
$route['documentos']                 = "conf/documento";
$route["documentos_cad"]             = "conf/documento/cadastro";
$route["cad_documento"]              = "conf/documento/cadastrar_documentos";
$route["editar_documento"]           = "conf/documento/editar_documentos_pagina";
$route["edit_documentos"]            = "conf/documento/editar_documentos";
$route["excluir_documento"]          = "conf/documento/excluir_documento";
$route["ausencia_ferias"]            = "home/ausencia_ferias";
$route["ausencia_ferias_cad"]        = "home/ausencia_ferias_cadastro";
$route["ausencia"]                   = "conf/ausencia";
$route["ausencia_cad"]               = "conf/ausencia/cadastro";
$route["cad_ausencia"]               = "conf/ausencia/cadastrar_ausencia";
$route["editar_ausencia"]            = "conf/ausencia/editar_ausencia_pagina";
$route["edit_ausencia"]              = "conf/ausencia/editar_ausencia";
$route["excluir_ausencia"]           = "conf/ausencia/excluir_ausencia";
$route["ferias"]                     = "conf/ferias";
$route["ferias_cad"]                 = "conf/ferias/cadastro";
$route["cad_ferias"]                 = "conf/ferias/cadastrar_ferias";
$route["editar_ferias"]              = "conf/ferias/editar_ferias_pagina";
$route["edit_ferias"]                = "conf/ferias/editar_ferias";
$route["excluir_ferias"]             = "conf/ferias/excluir_ferias";
$route["competencia"]                = "conf/competencia";
$route["competencia_cad"]            = "conf/competencia/cadastro";
$route["tipo_comp/(:any)"]           = "conf/competencia/tipo_competencia/$1";
$route["cad_competencia"]            = "conf/competencia/cadastrar";
$route["erros"]                      = "conf/erros";
$route["erros_cad"]                  = "conf/erros/pagina_cadastro";
$route["cad_erros"]                  = "conf/erros/cadastrar";
$route["editar_erro"]                = "conf/erros/editar_erro_pagina";
$route["edit_erro"]                  = "conf/erros/editar_erro";
$route["excluir_erro"]               = "conf/erros/excluir_erro";
$route["erro_documento"]             = "conf/Erros/erro_documento";
$route["etapa_json/(:any)"]          = "conf/Etapas/etapas_documento_json/$1";
$route["erro_documento_cad"]         = "conf/Erros/erro_documento_cad";
$route["vizualizar_erros/(:any)"]    = "conf/Erros/visualizar_erros_documento/$1";

//Rotas referentes a pasta documentos
$route["novo_documento"]             = "documentos/Documento";
$route["find_doc/(:num)"]            = "documentos/Documento/busca_documentos/$1";
$route["find_steps/(:num)"]          = "documentos/Documento/busca_etapas/$1";
$route["cad_novo_doc"]               = "documentos/Documento/cadastrar_novo_documento";
$route["meusdocumentos"]             = "documentos/Documento/meus_documentos";
$route["get_time"]                   = "documentos/Documento/get_time";
$route["grava_acao"]                 = "documentos/Documento/grava_acao";
$route["proxima_etapa/(:any)"]       = "documentos/Transferencia/transfere_etapa/$1";
$route["meus_documentos/(:any)"]     = "documentos/Documento/meus_documentos_msg/$1";
$route["finalizar_documento/(:any)"] = "documentos/Finalizar/finalizado/$1";
$route["etapa_aterior/(:any)"]       = "documentos/Transferencia/retorna_etapa/$1";
$route["editar_documento/(:any)"]    = "documentos/Documento/editar_documento/$1";
$route["edit_novo_doc"]              = "documentos/Documento/editar_novo_documento";
$route["historico_documento/(:any)"] = "documentos/Documento/historico_documento/$1";
$route["historico/(:any)"]           = "documentos/Documento/historico/$1";
$route["suspender/(:any)"]           = "documentos/Transferencia/suspender_documento/$1";
$route["cancelar_documento"]         = "documentos/Documento/cancelar_documento";
$route["andamento"]                  = "documentos/Relatorios";
$route["erro"]                       = "documentos/Relatorios/documentos_com_erro";