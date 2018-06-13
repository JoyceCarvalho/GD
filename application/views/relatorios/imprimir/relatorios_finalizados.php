<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>SGT - Gestão e Tecnologia</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Joyce Carvalho">
        <!-- Bootstrap CSS-->
        <link rel="stylesheet" href="<?=base_url('assets/vendor/bootstrap/css/bootstrap.min.css');?>">
        <!-- Font Awesome CSS-->
        <link rel="stylesheet" href="<?=base_url('assets/vendor/font-awesome/css/font-awesome.min.css');?>">
        <!-- Fontastic Custom icon font-->
        <link rel="stylesheet" href="<?=base_url('assets/css/fontastic.css');?>">
        <!-- Google fonts - Poppins -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700">
        <!-- theme stylesheet-->
        <link rel="stylesheet" href="<?=base_url('assets/css/style.blue.css');?>" id="theme-stylesheet">
        <!-- Custom stylesheet - for your changes-->
        <link rel="stylesheet" href="<?=base_url('assets/css/custom.css');?>">
        <!-- Favicon-->
        <link rel="shortcut icon" href="<?=base_url('assets/img/favicon.ico');?>">
        <!-- jQuery -->
        <script type="text/javascript" src="http://code.jquery.com/jquery-3.3.1.min.js"></script>

        <!-- CSS para impressão -->
        <link rel="stylesheet" href="<?=base_url("assets/css/imprimir.css");?>">
    </head>
    <body>
        <?php
        foreach ($informacoes_documento as $documento) {
            echo "Protocolo: " . $documento->protocolo;
            echo "<br/> Nome Documento: " . $documento->nome_documento;
            echo "<br/> Grupo Documento: " . $documento->nome_grupo;
            echo "<br/> Data de Criação: " . $documento->data_criacao;
            echo "<br/> Criado por: " . $documento->usuario_nome;
        }

        foreach ($etapas_documento as $etapas) {
            if ($etapas->descricao != "CRIADO") {
                echo "<br/> Descrição: " . $etapas->descricao . "<br/>";
                echo "Responsável pela etapa: " . $etapas->nome . "<br/>";
                echo "Data e hora do recebimento do documento: " . $etapas->data . " - " . $etapas->hora . "<br/>";
                echo "Etapa: " . $etapas->etapa . "<br/>";

                $this->load->model("etapas_model", "etapasmodel");
                $prazo = $this->etapasmodel->prazo_etapa($etapas->idprotocolo, $etapas->idetapa);
                if (!empty($prazo)) {
                    echo "Prazo para finalização: " . converte_data($prazo) . "<br/>";
                }

                $this->load->model("DocEtapas_model", "docetapamodel");

                $ordem_etapa_atual = $this->docetapamodel->etapa_atual($id_documento, $etapas->idetapa);

                $proxima_etapa_documento = $ordem_etapa_atual + 1;

                echo $proxima_etapa = $this->docetapamodel->proxima_etapa($id_documento, $proxima_etapa_documento);
            }
        }
        ?>
    </body>
</html>