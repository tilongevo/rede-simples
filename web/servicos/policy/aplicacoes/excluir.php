<?php
	require_once("../includes/autentica.php");
	require_once("../includes/functions.php");
if (!isset($_SESSION)) {
  session_start();
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST['pol_apl_codigo'])) && ($_POST['pol_apl_codigo'] != "")) {
  $deleteSQL = sprintf("DELETE FROM pol_aplicacao WHERE pol_apl_codigo=%s",
                       GetSQLValueString($_POST['pol_apl_codigo'], "int"));

  mysql_select_db($database_policy, $policy);
  $Result1 = mysql_query($deleteSQL, $policy) or die(mysql_error());

  $deleteGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
$colname_aplicacao = "-1";
if (isset($_GET['pol_apl_codigo'])) {
  $colname_aplicacao = $_GET['pol_apl_codigo'];
}
mysql_select_db($database_policy, $policy);
$query_aplicacao = sprintf("SELECT * FROM pol_aplicacao WHERE pol_apl_codigo = %s", GetSQLValueString($colname_aplicacao, "int"));
$aplicacao = mysql_query($query_aplicacao, $policy) or die(mysql_error());
$row_aplicacao = mysql_fetch_assoc($aplicacao);
$totalRows_aplicacao = mysql_num_rows($aplicacao);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>POLICY</title>
<link rel="stylesheet" type="text/css" href="/e-solution/servicos/policy/includes/policy.css">

<link type="text/css" rel="stylesheet" href="/e-solution/servicos/policy/includes/formulario_data/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="/e-solution/servicos/policy/includes/formulario_data/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript">
	function exibeIcone(nmIcone){
	
		document.getElementById('iconeSelecionado').innerHTML = "&nbsp;<img src='/datafiles/servicos/policy/aplicacoes/"+nmIcone+"'  align='absmiddle' /><input name='pol_apl_icone' id='pol_apl_icone' type='hidden' value='"+nmIcone+"' />";
	}
</script>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:25px;">
  <tr>
    <td align="center" valign="top">
    <table width="777" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" valign="top"><?php require_once('../includes/cabecalho.php'); ?></td>
      </tr>
      
      <tr>
        <td height="20" bgcolor="#FFFFFF"><?php require_once('../includes/menu_horizontal.php'); ?></td>
      </tr>
      <tr>
        <td height="22" align="right" valign="top" bgcolor="#FFFFFF" class="verdana_9">&nbsp;</td>
      </tr>
      <tr>
        <td height="75" align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="92%" align="left" bgcolor="#FFFFFF" class="verdana_11_bold">&nbsp;&nbsp;<img src="/e-solution/servicos/policy/images/icon_lado.gif" alt=" " align="absmiddle"/>&nbsp;Editar Aplica&ccedil;&atilde;o</td>
            <td width="8%" colspan="-2" align="center" valign="middle" class="verdana_11">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
            <td width="8%" colspan="-2" align="center" valign="middle" class="verdana_11">&nbsp;</td>
          </tr>
        </table>
          <form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST"><table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FBFFFB" style="border:1px solid #003300;">
            <tr>
              <td align="right" class="verdana_11">&nbsp;</td>
                <td height="37" align="left" class="verdana_11_vermelho">Tem certeza que deseja excluir esta aplica&ccedil;&atilde;o?</td>
              </tr>
              
              <tr>
                <td height="28" align="right" class="verdana_11">Nome :</td>
                <td align="left" class="verdana_11">&nbsp;<input name="pol_apl_nome" disabled="disabled" type="text" class="verdana_11" id="pol_apl_nome" value="<?php echo $row_aplicacao['pol_apl_nome']; ?>" size="35" maxlength="75" />
                  <input name="pol_apl_codigo" type="hidden" id="pol_apl_codigo" value="<?php echo $row_aplicacao['pol_apl_codigo']; ?>" /></td>
              </tr>
              <tr>
                <td height="26" align="right" class="verdana_11">URL :</td>
                <td align="left" class="verdana_11">&nbsp;<input name="pol_apl_url" type="text" class="verdana_11" id="pol_apl_url" value="<?php echo $row_aplicacao['pol_apl_url']; ?>" size="35" maxlength="75" disabled="disabled" /></td>
              </tr>
              <tr>
                <td height="26" align="right" valign="middle" class="verdana_11">Relev&acirc;ncia : </td>
                <td align="left" class="verdana_11"><label>
                  &nbsp;
                  <select name="pol_apl_relevancia" id="pol_apl_relevancia" class="formulario2" disabled="disabled">
                    <option value="-1" <?php if ($row_aplicacao['pol_apl_relevancia'] == -1 ) { echo 'selected="selected"'; } ?> >Sem valor</option>
                    <option value="0" <?php if ($row_aplicacao['pol_apl_relevancia'] == 0 ) { echo 'selected="selected"'; } ?> >0</option>
                    <option value="1" <?php if ($row_aplicacao['pol_apl_relevancia'] == 1 ) { echo 'selected="selected"'; } ?> >1</option>
                    <option value="2" <?php if ($row_aplicacao['pol_apl_relevancia'] == 2 ) { echo 'selected="selected"'; } ?> >2</option>
                    <option value="3" <?php if ($row_aplicacao['pol_apl_relevancia'] == 3 ) { echo 'selected="selected"'; } ?> >3</option>
                    <option value="4" <?php if ($row_aplicacao['pol_apl_relevancia'] == 4 ) { echo 'selected="selected"'; } ?> >4</option>
                    <option value="5" <?php if ($row_aplicacao['pol_apl_relevancia'] == 5 ) { echo 'selected="selected"'; } ?> >5</option>
                    <option value="6" <?php if ($row_aplicacao['pol_apl_relevancia'] == 6 ) { echo 'selected="selected"'; } ?> >6</option>
                    <option value="7" <?php if ($row_aplicacao['pol_apl_relevancia'] == 7 ) { echo 'selected="selected"'; } ?> >7</option>
                    <option value="8" <?php if ($row_aplicacao['pol_apl_relevancia'] == 8 ) { echo 'selected="selected"'; } ?> >8</option>
                    <option value="9" <?php if ($row_aplicacao['pol_apl_relevancia'] == 9 ) { echo 'selected="selected"'; } ?> >9</option>
                    <option value="10" <?php if ($row_aplicacao['pol_apl_relevancia'] == 10 ) { echo 'selected="selected"'; } ?> >10</option>
                  </select>
                </label></td>
              </tr>
              <tr>
                <td height="26" align="right" valign="top" class="verdana_11">Descri&ccedil;&atilde;o :</td>
                <td align="left" class="verdana_11">&nbsp;<textarea name="pol_apl_descricao" disabled="disabled" cols="60" rows="6" class="verdana_11" id="pol_apl_descricao"><?php echo $row_aplicacao['pol_apl_descricao']; ?></textarea></td>
              </tr>
              <tr>
                <td height="26" align="right">&nbsp;</td>
                <td align="left"><?php
				 
				//lista as op&ccedil;&otilde;es de imagens para gif
				if ($handle = opendir('../../../../datafiles/servicos/policy/aplicacoes/.')) {
					$cont  = 0;
					$dif	= 0;
					while (false !== ($file = readdir($handle))) {
					
				//	$excessao = array_key_exists(str_replace(".png","",$file), $cadaCampo);
					
						if ($file != "." && $file != ".." ) {
							if(strtolower($file)!="thumbs.db"){	
								if($cont==1){
									echo "<br/>";
									$cont=0;
								}
								$alt = explode(".",$file);
								$nmArquivo 	= strtolower($file);
								$icone		= '<img src="/datafiles/servicos/policy/aplicacoes/'.$nmArquivo.'" alt="'.$alt[0].'" title="'.$alt[0].'" border="0" align="absmiddle" />';
								$check="";
								if($row_aplicacao['pol_apl_icone'] == $nmArquivo){
									$check= "checked";
								
								}
								echo '<input name="pol_apl_icone" disabled="disabled" id="icone_'.$dif.'" type="radio" value="'.$nmArquivo.'" '.$check.'>'.$icone;
								$cont++; 
								$dif = $dif + 1;
								//se chegar a 100 ele para
								if ($cont == 250) { die;}	
							}
						}
					}
					closedir($handle);
				}
            ?></td>
              </tr>
              <tr>
                <td height="26" align="right">&nbsp;</td>
                <td align="left"><input name="button2" type="button" class="button" id="button2" onclick="location.href='index.php'" value="Cancelar" />
                  <input name="button" type="submit" class="button" id="button" value="Excluir" /></td>
              </tr>
              <tr>
                <td height="10" colspan="2" align="right">&nbsp;</td>
                </tr>
          </table>
            
            <input type="hidden" name="MM_update" value="form1" />
          </form>
          
          
          
          </td>
      </tr>
      
      <tr>
        <td height="130" bgcolor="#FFFFFF">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>