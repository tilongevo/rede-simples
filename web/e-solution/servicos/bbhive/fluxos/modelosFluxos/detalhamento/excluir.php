<?php require_once('../../../../../../Connections/bbhive.php'); ?>
<?php require_once('includes/functions.php'); ?>
<?php

include($_SESSION['caminhoFisico']."/Connections/bbhive.php");
include($_SESSION['caminhoFisico']."/e-solution/servicos/bbhive/includes/autentica.php");
include($_SESSION['caminhoFisico']."/e-solution/servicos/bbhive/includes/functions.php");


	$idMensagemFinal= 'carregaTipo';
	$infoGet_Post	= '?1=1';//Se envio for POST, colocar nome do formulário
	$Mensagem		= 'Carregando dados...';
	$idResultado	= $idMensagemFinal;
	$Metodo			= '2';//1-POST, 2-GET
	$TpMens			= '2';//1-Troca conteúdo sem apagar anterior , 2-Troca conteúdo apagando o anterior antes
	$homeDestino	= '/e-solution/servicos/bbhive/fluxos/modelosFluxos/escTipo.php';
	$homeDestinoII	= '/e-solution/servicos/bbhive/fluxos/modelosFluxos/detalhamento/excluir.php';
	
	$onClick = "OpenAjaxPostCmd('".$homeDestino."','".$idResultado."','".$infoGet_Post."','".$Mensagem."','".$idMensagemFinal."','".$Metodo."','".$TpMens."')";
	
	$acao = "OpenAjaxPostCmd('".$homeDestinoII."','loadInser','cadatraModelo','Alterando dados...','loadInser','1','".$TpMens."');";
	
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

  $deleteSQL = sprintf("DELETE FROM bbh_campo_detalhamento_fluxo WHERE bbh_cam_det_flu_codigo=%s",
                       GetSQLValueString($bbhive, $_POST['bbh_cam_det_flu_codigo'], "int"));
    list($exec, $rows, $totalRows) = executeQuery($bbhive, $database_bbhive, $deleteSQL, $initResult = false);

  	 echo "<var style='display:none'>LoadSimultaneo('perfil/index.php?perfil=2&menuEsquerda=1|fluxos/modelosFluxos/detalhamento/index.php?bbh_mod_flu_codigo=".$_POST['bbh_mod_flu_codigo']."','menuEsquerda|conteudoGeral')</var>";
	  exit;
}

$codigo_modelo_fluxo = $_GET['bbh_mod_flu_codigo'];

$query_detalhamento = sprintf("SELECT * FROM bbh_detalhamento_fluxo WHERE bbh_mod_flu_codigo = %s", GetSQLValueString($bbhive, $codigo_modelo_fluxo, "int"));
list($detalhamento, $row_detalhamento, $totalRows_detalhamento) = executeQuery($bbhive, $database_bbhive, $query_detalhamento);

$colname_campos_detalhamento = "-1";
if (isset($_GET['bbh_cam_det_flu_codigo'])) {
  $colname_campos_detalhamento = $_GET['bbh_cam_det_flu_codigo'];
}
$query_campos_detalhamento ="SELECT date_format(bbh_cam_det_flu_default,'%d/%m/%Y') as data,bbh_campo_detalhamento_fluxo.* FROM bbh_campo_detalhamento_fluxo WHERE bbh_cam_det_flu_codigo = $colname_campos_detalhamento";
list($campos_detalhamento, $row_campos_detalhamento, $totalRows_campos_detalhamento) = executeQuery($bbhive, $database_bbhive, $query_campos_detalhamento);

	$acao = "OpenAjaxPostCmd('".$homeDestinoII."','loadInser','form1','Cadastrando dados...','loadInser','1','".$TpMens."');";

?>

<var style="display:none">txtSimples('tagPerfil', 'Cadastro de modelos de  <?php echo $_SESSION['adm_FluxoNome']; ?>')</var>
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="verdana_10">
  <tr>
    <td width="55%" height="26" background="/e-solution/servicos/bbhive/images/barra_horizontal.jpg" class="verdana_11_bold"><img src="/e-solution/servicos/bbhive/images/detalhamento.gif" width="16" height="16" align="absmiddle" /> Gerenciamento de detalhamento do fluxo  <?php echo $_SESSION['adm_FluxoNome']; ?></td>
    <td width="32%" background="/e-solution/servicos/bbhive/images/barra_horizontal.jpg">&nbsp;</td>
    <td width="13%" align="right" background="/e-solution/servicos/bbhive/images/barra_horizontal.jpg"><a href="#@" onClick="showHome('includes/home.php','conteudoGeral', 'perfil/index.php?perfil=1&amp;menuEsquerda=1|fluxos/modelosFluxos/detalhamento/index.php?bbh_mod_flu_codigo=<?php echo $_GET['bbh_mod_flu_codigo']; ?>','menuEsquerda|colCentro');"><span class="verdana_11_bold color">.:&nbsp;Voltar&nbsp;:.</span></a>&nbsp;</td>
  </tr>
  <tr>
    <td height="8" colspan="3"></td>
  </tr>
</table>

<?php require_once('../modelosAtividades/cabecaModelo.php'); ?>
<br />

<div style="position:absolute; margin-left:-2px;" id="carregaTipo"></div>
<div style="position:absolute;" id="loadInser" class="legandaLabel11"></div>

    <?php 
		//Isse bloco verifica se os campos que possuem o atributo tamanho mostra ao iniciar ou não
		$is_float = false;	
		$is_number = false;	
		switch($row_campos_detalhamento['bbh_cam_det_flu_tipo'])
		{
			case "correio_eletronico":
				$tamanho = "";	
				$valor_padrao = "";
				$data = "none";
				$lista = "none";
				$hora = "none";
				$longo = "none";
				$opcoesHidden = "";
			break;

			case "data":
				$tamanho = "none";
				$valor_padrao = "none";			
				$data = "";
				$lista = "none";
				$hora = "none";
				$longo = "none";
				$opcoesHidden = "";
			break;
			
			case "lista_opcoes":
				$tamanho = "none";	
				$valor_padrao = "none";
				$data = "none";
				$lista = "";
				$hora = "none";	
				$longo = "none";
				$opcoesHidden = $row_campos_detalhamento['bbh_cam_det_flu_default'];					
			break;
			
			case "texto_longo":
				$longo = "";
				$tamanho = "none";	
				$valor_padrao = "none";
				$data = "none";	
				$lista = "none";
				$hora = "none";
				$opcoesHidden = "";
			break;
			
			case "horario":
				$tamanho = "none";	
				$valor_padrao = "none";
				$data = "none";	
				$lista = "none";		
				$hora = "";	
				$longo = "none";
				$opcoesHidden = "";
						
			break;
			
			case "endereco_web":
				$tamanho = "";	
				$valor_padrao = "";
				$data = "none";	
				$lista = "none";
				$hora = "none";	
				$longo = "none";
				$opcoesHidden = "";	
			break;
			
			case "numero":
				$tamanho = "";
				$valor_padrao = "";
				$data = "none";
				$lista = "none";
				$hora = "none";	
				$longo = "none";
				$opcoesHidden = "";
				$is_number = true;
			break;
			
			case "numero_decimal":
				$tamanho = "";
				$valor_padrao = "";
				$data = "none";	
				$lista = "none";
				$hora = "none";
				$longo = "none";
				$opcoesHidden = "";
				$is_float = true;
			break;

			case "texto_simples":
				$tamanho = "";
				$valor_padrao = "";
				$data = "none";	
				$lista = "none";
				$hora = "none";
				$longo = "none";
				$opcoesHidden = "";
			break;
			
			default:
				$tamanho = "none";
				$valor_padrao = "none";
				$data = "none";
				$lista = "none";
				$hora = "none";	
				$longo = "none";
				$opcoesHidden = "";
				$opcoesHidden = "";
			break;		
		}

	 ?>

<form method="POST" action="<?php echo $editFormAction; ?>" name="form1">
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="0" class="borderAlljanela">
  <tr class="legandaLabel11">
    <td height="26" colspan="2" background="/e-solution/servicos/bbhive/images/barra_horizontal2.jpg" class="verdana_11_bold">Exclus&atilde;o do  campo de detalhamento</td>
  </tr>
  <tr class="legandaLabel11">
    <td width="175" height="25" align="right" class="verdana_11_bold">Titulo :&nbsp;</td>
    <td align="left"><input name="bbh_cam_det_flu_titulo" type="text" class="back_Campos" id="bbh_cam_det_flu_titulo" value="<?php echo $row_campos_detalhamento['bbh_cam_det_flu_titulo']; ?>" size="60" disabled></td>
  </tr>
  <tr class="legandaLabel11">
    <td height="25" align="right" class="verdana_11_bold">Tipo :&nbsp;</td>
    <td align="left">
    <select name="bbh_cam_det_flu_tipo" id="bbh_cam_det_flu_tipo" class="back_Campos" onChange="showCampos(this);" disabled>
      <option value="" <?php if (!(strcmp("", $row_campos_detalhamento['bbh_cam_det_flu_tipo']))) {echo "selected=\"selected\"";} ?>>Escolha</option>
      <option value="correio_eletronico" <?php if (!(strcmp("correio_eletronico", $row_campos_detalhamento['bbh_cam_det_flu_tipo']))) {echo "selected=\"selected\"";} ?>>Correio eletr&ocirc;nico</option>
      <option value="data" <?php if (!(strcmp("data", $row_campos_detalhamento['bbh_cam_det_flu_tipo']))) {echo "selected=\"selected\"";} ?>>Data</option>
      <option value="time_stamp" <?php if (!(strcmp("time_stamp", $row_campos_detalhamento['bbh_cam_det_flu_tipo']))) {echo "selected=\"selected\"";} ?>>Data / Hora atual</option>
      <option value="horario_editavel" <?php if (!(strcmp("horario_editavel", $row_campos_detalhamento['bbh_cam_det_flu_tipo']))) {echo "selected=\"selected\"";} ?>>Data / Hora edit&aacute;vel</option>
      <option value="endereco_web" <?php if (!(strcmp("endereco_web", $row_campos_detalhamento['bbh_cam_det_flu_tipo']))) {echo "selected=\"selected\"";} ?>>Endere&ccedil;o web</option>
      <option value="lista_opcoes" <?php if (!(strcmp("lista_opcoes", $row_campos_detalhamento['bbh_cam_det_flu_tipo']))) {echo "selected=\"selected\"";} ?>>Lista de op&ccedil;&otilde;es</option>
      <option value="numero" <?php if (!(strcmp("numero", $row_campos_detalhamento['bbh_cam_det_flu_tipo']))) {echo "selected=\"selected\"";} ?>>N&uacute;mero</option>
      <option value="numero_decimal" <?php if (!(strcmp("numero_decimal", $row_campos_detalhamento['bbh_cam_det_flu_tipo']))) {echo "selected=\"selected\"";} ?>>N&uacute;mero decimal</option>
      <option value="texto_longo" <?php if (!(strcmp("texto_longo", $row_campos_detalhamento['bbh_cam_det_flu_tipo']))) {echo "selected=\"selected\"";} ?>>Texto longo</option>
      <option value="texto_simples" <?php if (!(strcmp("texto_simples", $row_campos_detalhamento['bbh_cam_det_flu_tipo']))) {echo "selected=\"selected\"";} ?>>Texto simples</option>
      <option value="json" <?php if (!(strcmp("json", $row_campos_detalhamento['bbh_cam_det_flu_tipo']))) {echo "selected=\"selected\"";} ?>>Integração de sistemas (JSON)</option>
    </select>    </td>
  </tr>
  <tr style="display:none;background:#F7F7F7;background:#F7F7F7;" id="Tamanho">
    <td align="right" class="verdana_11_bold">*Tamanho :&nbsp; </td>
    <td align="left" class="verdana_11"><label>
      <input name="bbh_cam_det_flu_tamanho" type="text" class="back_Campos" id="bbh_cam_det_flu_tamanho" value="<?php echo $row_campos_detalhamento['bbh_cam_det_flu_tamanho']; ?>" disabled />
    </label></td>
  </tr>
  <tr style="display:<?php echo $data; ?>;background:#F7F7F7;" id="VlPadraoData">
    <td align="right" class="verdana_11_bold">Valor padr&atilde;o :&nbsp;</td>
    <td align="left" class="verdana_11"><input name="theDate" type="text" class="back_Campos" id="theDate" value="<?php echo $row_campos_detalhamento['data']; ?>" size="13" readonly="readonly" disabled/>
        <input type="button" style="width:23px;height:21px;" class="botao_calendar" onClick="displayCalendar(document.form1.theDate,'dd/mm/yyyy',this)" disabled/></td>
  </tr>
  <tr style="display:<?php echo $valor_padrao; ?>;background:#F7F7F7;" id="VlPadrao">
    <td align="right" class="verdana_11_bold">Valor padr&atilde;o :&nbsp; </td>
    <td align="left" class="verdana_11"><label>
    <?php
			if($is_float == true)
			{
				$valor_default = Real($row_campos_detalhamento['bbh_cam_det_flu_default']); ?>
				      <input name="bbh_cam_det_flu_default" type="text" class="back_Campos" id="bbh_cam_det_flu_default" size="30" value="<?php echo $valor_default; ?>" onKeyPress="return(MascaraMoeda(this,'.',',',event))" disabled>

 		 <?php }else if($is_number == true){ 
         	$valor_default = $row_campos_detalhamento['bbh_cam_det_flu_default']; ?>
          <input name="bbh_cam_det_flu_default" type="text" class="back_Campos" id="bbh_cam_det_flu_default" size="30" value="<?php echo $valor_default; ?>" onkeyup="SomenteNumerico(this);" disabled/>

			<?php }else{
				$valor_default = $row_campos_detalhamento['bbh_cam_det_flu_default']; ?>
                      <input name="bbh_cam_det_flu_default" type="text" class="back_Campos" id="bbh_cam_det_flu_default" size="30" value="<?php echo $valor_default; ?>" disabled/>

			<?php }
	?>
    </label></td>
  </tr>
  <tr style="display:none;background:#F7F7F7;" id="listagem">
    <td align="right" class="verdana_11_bold" >Valor  a ser adicionado :&nbsp; </td>
    <td align="left" class="verdana_11"><label>
      <input name="listagemI" type="text" class="back_Campos" id="listagemI" size="20" maxlength="50" onKeyUp="return cadListagem();" disabled>
      </label></td>
  </tr>
  <tr style="display:<?php echo $lista; ?>;background:#F7F7F7;" id="listagemCriada">
    <td align="right" class="verdana_11_bold" ><span class="verdana_11">Lista atual :&nbsp;</span></td>
    <td align="left" class="verdana_11"><select name="menuCriado" id="menuCriado" class="back_Campos" style="position:relative;width:200px;" disabled>
      <?php
      		$options = explode("|",$row_campos_detalhamento['bbh_cam_det_flu_default']); 
			if(count($options) > 1)
			{
				for($x = 0; $x < count($options)-1; $x++)
				{
					echo "<option>".$options[$x]."</option>";
				}
			}else{
      
      ?>
       <option id="lista_vazia" value="lista_vazia">Listagem vazia</option>
      <?php } ?>
      </select>
      <input type="hidden" name="menuCriadoValores" id="menuCriadoValores" /></td>
  </tr>
      <?php 
		$linhaColuna = explode("|",$row_campos_detalhamento['bbh_cam_det_flu_tamanho']);
		$linha = 0;
		$coluna = 0;
		if(isset($linhaColuna[0]))
		{
			$linha = $linhaColuna[0];
		}
		
		if(isset($linhaColuna[1]))
		{
			$coluna = $linhaColuna[1];
		}


	?>
  <tr style="display:<?php echo $longo; ?>;background:#F7F7F7;" id="texto_longoLinha">
    <td align="right" class="verdana_11_bold" >Caracteres por linha :&nbsp;</td>
    <td align="left" class="verdana_11"><input type="text" name="texto_longoLinhaI" id="texto_longoLinhaI" class="back_Campos" onKeyUp="SomenteNumerico(this);" value="<?php echo $linha; ?>" disabled></td>
    </tr>
  <tr style="display:<?php echo $longo; ?>;background:#F7F7F7;" id="texto_longoColuna">
    <td align="right" class="verdana_11_bold" >Caracteres por coluna :&nbsp;</td>
    <td align="left" class="verdana_11"><input type="text" name="texto_longoColunaI" id="texto_longoColunaI" class="back_Campos" onKeyUp="SomenteNumerico(this);" value="<?php echo $coluna; ?>" disabled/></td>
  </tr>
  <tr style="display:<?php echo $longo; ?>;background:#F7F7F7;" id="texto_longoDefault">
    <td align="right" valign="top" class="verdana_11_bold">Valor padr&atilde;o :&nbsp;</td>
    <td align="left" class="verdana_11"><textarea name="bbh_cam_det_flu_defaultLongo" id="bbh_cam_det_flu_defaultLongo" cols="30" rows="3" class="back_Campos" disabled><?php echo $row_campos_detalhamento['bbh_cam_det_flu_default']; ?></textarea></td>
  </tr>
  
  
  
  <tr class="legandaLabel11">
    <td height="25" align="right" class="verdana_11_bold">Curinga :&nbsp;</td>
    <td align="left"><input type="text" name="bbh_cam_det_flu_curinga" id="bbh_cam_det_flu_curinga" class="back_Campos" value="<?php echo $row_campos_detalhamento['bbh_cam_det_flu_curinga']; ?>" disabled></td>
  </tr>
    <tr class="legandaLabel11">
        <td height="25" align="right" class="verdana_11_bold">Apelido :&nbsp;</td>
        <td align="left"><input type="text" name="bbh_cam_det_flu_apelido" id="bbh_cam_det_flu_apelido" class="back_Campos" value="<?php echo $row_campos_detalhamento['bbh_cam_det_flu_apelido']; ?>" disabled></td>
    </tr>
  <tr class="legandaLabel11">
    <td height="25" align="right" class="verdana_11_bold">Observa&ccedil;&atilde;o :&nbsp;</td>
    <td align="left"><textarea name="bbh_cam_det_flu_descricao" id="bbh_cam_det_flu_descricao" cols="45" rows="5" class="back_Campos" disabled><?php echo $row_campos_detalhamento['bbh_cam_det_flu_descricao']; ?></textarea></td>
  </tr>
  <tr class="legandaLabel11">
    <td height="25" align="right" class="verdana_11_bold">&nbsp;</td>
    <td align="left">
      <input name="button" type="button" class="back_input" id="button" value="Cancelar"  onClick="LoadSimultaneo('perfil/index.php?perfil=2|fluxos/modelosFluxos/detalhamento/index.php?bbh_mod_flu_codigo=<?php echo $_GET['bbh_mod_flu_codigo']; ?>','menuEsquerda|conteudoGeral');" style="cursor:pointer; background-image:url(/e-solution/servicos/bbhive/images/search.gif); color:#FFFFFF"/>
      <input name="cadastra" type="button" class="back_input" id="cadastra" value="Excluir campo" style="cursor:pointer; background-image:url(/e-solution/servicos/bbhive/images/search.gif); color:#FFFFFF" onClick="return validaForm('form1', 'bbh_cam_det_flu_titulo|Escolha o t&iacute;tulo do campo, bbh_cam_det_flu_tipo|Escolha o tipo do campo', document.getElementById('acaoForm').value)"/>
      &nbsp;&nbsp;&nbsp; <input type="hidden" name="tipoCampo" id="tipoCampo" />
      <input name="bbh_det_flu_codigo" type="hidden" id="bbh_det_flu_codigo" value="<?php echo $row_detalhamento['bbh_det_flu_codigo']; ?>">
      <input name="bbh_mod_flu_codigo" type="hidden" id="bbh_mod_flu_codigo" value="<?php echo $_GET['bbh_mod_flu_codigo']; ?>" />
      <input type="hidden" name="bbh_cam_det_flu_codigo" id="bbh_cam_det_flu_codigo" value="<?php echo $_GET['bbh_cam_det_flu_codigo']; ?>"/></td>
  </tr>
  <tr class="legandaLabel11">
    <td height="25" align="right" class="verdana_11_bold">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>

  <tr>
    <td height="1" background="/corporativo/servicos/bbhive/images/separador.gif"></td>
    <td width="420" background="/corporativo/servicos/bbhive/images/separador.gif"></td>
  </tr>
 <tr class="legandaLabel11">
    <td height="22" align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="MM_insert" value="form1" />
<input type="hidden" name="acaoForm" id="acaoForm" value="<?php echo $acao; ?>" />
</form>
<?php
mysqli_free_result($detalhamento);

mysqli_free_result($campos_detalhamento);
?>
