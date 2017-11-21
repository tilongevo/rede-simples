<?php
 if(isset($_GET['acao'])){
//	include_once("classes/GeraGraficoBarra.php");
	require_once("../../includes/grafico/classes/GeraGraficoLinhas.php");
	require_once("grafico.php");
	header("Content-type: image/png");
				
	// ------ definição dos dados ----------
	$texto_linha 	= array("Acessos");
	$texto_coluna 	= $texto_coluna;//array("04/08/2009","06/08/2009","07/08/2009");
	$valores 		= $valores;//array(105,17,80);
	
	$nomeTituloEixoX= "Eventos cadastrados";
	$nomeTituloEixoY= "Número de acesso";
	$nmTituloGrafico= "Equipe Backsite";
	$exibir_legenda	= "sim";//s - Sim // n - Não
		
	$GGL = new GeraGraficoLinha($texto_linha,$texto_coluna, $valores,$nomeTituloEixoX,$nomeTituloEixoY,$nmTituloGrafico,$exibir_legenda);
	exit;
 }
 
 require_once("grafico.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Gr&aacute;ficos do Policy</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
	color: #333;
}
-->
</style></head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<b><big><big><?php echo $row_dadosPolitica['pol_pol_titulo']; ?></big></big></b>
<hr>
<i>Visualiza&ccedil;&atilde;o por gr&aacute;fico de linha e estat&iacute;stas</i>
<p>&nbsp;</p>
<p>Gr&aacute;fico: <big><b><?php echo $row_dadosGrafico['pol_graf_titulo']; ?></b></big></p>
<iframe allowtransparency="true" src="final.php?pol_graf_codigo=<?php echo $_GET['pol_graf_codigo']; ?>&acao=true" name="autPOLICY" height="540" width="750" frameborder="0"></iframe><br>
<p>Informa&ccedil;&atilde;o: <big><b>Dados Estat&iacute;sticos</b></big></p>
<table border="1" align="left" cellpadding="3" cellspacing="0">
  <tr>
    <td align="left"><b>Data</b></td>
    <td align="center"><b>Acessos</b></td>
    <td align="center"><b>%</b></td>
  </tr>
  <?php for($a=0;$a<count($valores);$a++){?>
  <tr>
    <td align="left"><?php echo $texto_coluna[$a]; ?></td>
    <td align="center"><?php echo $valores[$a]; ?></td>
    <td align="center"><?php echo round(($valores[$a]/$total)*100,2); ?>%</td>
  </tr>
  <?php } ?>
  <tr>
    <td align="left"><b>Acessos</b></td>
    <td align="center"><?php echo $total; ?></td>
    <td align="center">100.00%</td>
  </tr>
</table>
</body>
</html>