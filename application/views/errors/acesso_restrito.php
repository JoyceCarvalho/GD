<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Twitter meta-->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:site" content="@pratikborsadiya">
    <meta property="twitter:creator" content="@pratikborsadiya">
    <!-- Open Graph Meta-->
    <meta property="og:type" content="website">
    <title>SGT - Gestão e Tecnologia</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?=base_url("assets/css/main.css");?>">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Icone SGT -->
    <link rel="shortcut icon" href="<?=base_url('assets/img/favicon.ico');?>">
  </head>
  <body>
   
    <!--<main class="app-content">-->
      <div class="page-error tile">
        <h1><i class="fa fa-exclamation-circle"></i> Ops! Página de acesso restrito</h1>
        <p>Você não tem autorização para acessar esta página.</p>
        <p><a class="btn btn-primary" href="<?=base_url("finalizados");?>">Voltar</a></p>
      </div>
    <!-- </main> -->

    <!-- Essential javascripts for application to work-->
    <script src="<?=base_url("assets/js/jquery-3.2.1.min.js");?>"></script>
    <script src="<?=base_url("assets/js/popper.min.js");?>"></script>
    <script src="<?=base_url("assets/js/bootstrap.min.js");?>"></script>
    <script src="<?=base_url("assets/js/main.js");?>"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="<?=base_url("assets/js/plugins/pace.min.js");?>"></script>
    <!-- Page specific javascripts-->
    <!-- Google analytics script-->
    <script type="text/javascript">
      if(document.location.hostname == 'pratikborsadiya.in') {
      	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      	ga('create', 'UA-72504830-1', 'auto');
      	ga('send', 'pageview');
      }
    </script>
  </body>
</html>