<?php if(!isset($_SESSION)){session_start();}
include($_SESSION['caminhoFisico']."/Connections/bbhive.php");
include($_SESSION['caminhoFisico']."/e-solution/servicos/bbhive/includes/autentica.php");
include($_SESSION['caminhoFisico']."/e-solution/servicos/bbhive/includes/functions.php");


//SELECTS PARA PESQUISA POR DATA
$ajusteSQL = "";
if(isset($_GET['DataInicio'])){
	$dataBruta = explode("/",$_GET['DataInicio']);
	$data_recebidaI  = "(bbh_men_data_recebida >='".$dataBruta[2]."-".$dataBruta[1]."-".$dataBruta[0]." - 00:00:00'";
	$data_recebidaII = "bbh_men_data_recebida <='".$dataBruta[2]."-".$dataBruta[1]."-".$dataBruta[0]." - 23:59:59')";
	$ajusteSQL = " AND ".$data_recebidaI. " AND ".$data_recebidaII;
}

if(isset($_POST['DataInicio'])){
	$dataBrutaI = explode("/",$_POST['DataInicio']);
	$dataBrutaF = explode("/",$_POST['DataFim']);
	$bbh_men_data_recebida = " bbh_men_data_recebida>='".$dataBrutaI[2]."-".$dataBrutaI[1]."-".$dataBrutaI[0]." - 00:00:00' AND bbh_men_data_recebida <='".$dataBrutaF[2]."-".$dataBrutaF[1]."-".$dataBrutaF[0]." - 23:59:59'";
	$ajusteSQL = " AND ".$bbh_men_data_recebida;
}

	//select para descobrir o total de registros na base
	$query_TotMSGs = "SELECT count(bbh_usu_codigo) as TOTAL, bbh_usuario.bbh_usu_codigo FROM bbh_mensagens INNER JOIN bbh_usuario ON bbh_usuario.bbh_usu_codigo = bbh_mensagens.bbh_usu_codigo_destin OR bbh_usuario.bbh_usu_codigo = bbh_mensagens.bbh_usu_codigo_remet GROUP BY bbh_usu_codigo";
    list($TotMSGs, $row_TotMSGs, $totalRows_TotMSGs) = executeQuery($bbhive, $database_bbhive, $query_TotMSGs);
	
	$page 		= "1";
	$nElements 	= "40";
		if(isset($_GET["page"])){
			$page 	= $_GET["page"];
		}
	$Inicio = ($nElements*$page)-$nElements;
	$homeDestino	= '/e-solution/servicos/bbhive/mensagens/usuarios/index.php?Ts='.$_SERVER['REQUEST_TIME'];
	$exibe			= 'conteudoGeral';
	$pages 			= ceil($row_TotMSGs['TOTAL']/$nElements);

$query_mensagens = "SELECT bbh_usuario.bbh_usu_nome, bbh_usuario.bbh_usu_codigo FROM bbh_mensagens INNER JOIN bbh_usuario ON bbh_usuario.bbh_usu_codigo = bbh_mensagens.bbh_usu_codigo_destin OR bbh_usuario.bbh_usu_codigo = bbh_mensagens.bbh_usu_codigo_remet WHERE 1=1 $ajusteSQL GROUP BY bbh_usu_nome ORDER BY bbh_usu_nome ASC";
list($mensagens, $row_mensagens, $totalRows_mensagens) = executeQuery($bbhive, $database_bbhive, $query_mensagens);


	//Ação FORM
	$TimeStamp 		= time();
	$homeDestino	= '/e-solution/servicos/bbhive/mensagens/usuarios/index.php?Ts='.$TimeStamp;
	$idMensagemFinal= 'conteudoGeral';
	$infoGet_Post	= 'form1';//Se envio for POST, colocar nome do formulário
	$Mensagem		= "Consultando informa&ccedil;&otilde;es...";
	$idResultado	= $idMensagemFinal;//'menHistorico';
	$Metodo			= "1";//1-POST, 2-GET
	$TpMens			= "2";//1-Troca conteúdo sem apagar anterior , 2-Troca conteúdo apagando o anterior antes
	
	$acao = "OpenAjaxPostCmd('".$homeDestino."','".$idResultado."','".$infoGet_Post."','".$Mensagem."','".$idMensagemFinal."','".$Metodo."','".$TpMens."');";
?>
<var style="display:none">txtSimples('tagPerfil', '<?php echo $_SESSION['adm_MsgNome']; ?>')</var>
<form id="form1" name="form1">
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="verdana_10">
  <tr>
    <td width="75%" height="26" colspan="3" background="/e-solution/servicos/bbhive/images/barra_horizontal.jpg" class="verdana_11_bold"><img src="/e-solution/servicos/bbhive/images/ger-mensagens-16px.gif" width="16" height="16" align="absmiddle" /> Gerenciamento de <?php echo $_SESSION['adm_MsgNome']; ?></td>
    <td width="21%" align="right" background="/e-solution/servicos/bbhive/images/barra_horizontal.jpg"><a href="#@" onClick="LoadSimultaneo('perfil/index.php?perfil=1|principal.php','menuEsquerda|conteudoGeral');"><span class="verdana_11_bold color">.:&nbsp;Voltar&nbsp;:.</span></a>&nbsp;</td>
  </tr>
  <tr>
    <td height="8" colspan="4"></td>
  </tr>
  
    <tr>
      <td colspan="4" class="verdana_11"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="verdana_11">
        <tr>
          <td class="verdana_11"><label class="verdana_9" style="margin-top:2px;">De :
            <input name="DataInicio" type="text" class="verdana_9" id="DataInicio" value="<?php 
          if(isset($_GET['DataInicio'])){
			$DataInicio = explode("/", $_GET['DataInicio']);
            $DataInicio = $DataInicio[0]."/".$DataInicio[1]."/".$DataInicio[2];
			echo $DataInicio;
          } elseif(isset($_POST['DataInicio'])){
			$DataInicio = explode("/", $_POST['DataInicio']);
            $DataInicio = $DataInicio[0]."/".$DataInicio[1]."/".$DataInicio[2];
			echo $DataInicio;
		  } else {
            $DataInicio = date("d") . "/" . date("m") . "/" . date("Y");
            echo $DataInicio;
          }
           ?>" size="13" onKeyPress="MascaraData(event, this)" maxlength="10"/>
                <input type="button" style="width:23px;height:21px;" class="botao_calendar" onClick="displayCalendar(document.form1.DataInicio,'dd/mm/yyyy',this)"/>
            </label>
              <label> At&eacute; :
                <input name="DataFim" type="text" class="verdana_9" id="DataFim" value="<?php
          if(isset($_GET['DataFim'])){
			$DataFim = explode("/", $_GET['DataFim']);
            $DataFim = $DataFim[0]."/".$DataFim[1]."/".$DataFim[2];
			echo $DataFim;
          } elseif(isset($_POST['DataFim'])){
			$DataInicio = explode("/", $_POST['DataFim']);
            $DataInicio = $DataInicio[0]."/".$DataInicio[1]."/".$DataInicio[2];
			echo $DataInicio;
		  } else {
            $DataFim = $DataInicio;
            echo $DataFim;
          }

           ?>" size="13" onKeyPress="MascaraData(event, this)" maxlength="10"/>
              <input type="button" style="width:23px;height:21px;" class="botao_calendar" onClick="displayCalendar(document.form1.DataFim,'dd/mm/yyyy',this)"/>
              <input class="formulario2" id="web" type="button" value="Pesquisar" name="web"  style="cursor:pointer; background-image:url(/e-solution/servicos/bbhive/images/search.gif); color:#FFFFFF" onclick="return <?php echo $acao; ?>"/>
              </label>
              <?php
    $DataAtual = explode("/", $DataInicio);
    
    $Proximo  = addDayIntoDate($DataAtual[2].$DataAtual[1].$DataAtual[0], 1);//adiciona um dia
    $Proximo  = substr($Proximo,6,2)."/".substr($Proximo,4,2)."/".substr($Proximo,0,4);
    
    $Anterior = subDayIntoDate($DataAtual[2].$DataAtual[1].$DataAtual[0], 1);//remove um dia
    $Anterior  = substr($Anterior,6,2)."/".substr($Anterior,4,2)."/".substr($Anterior,0,4);
    
    ?>
              <label style="float:right; "> <a href="#@" onClick="return showHome('includes/home.php','conteudoGeral', 'perfil/index.php?perfil=2&perfis=1|mensagens/usuarios/index.php?TimeStamp=<?php echo time(); ?>&DataInicio=<?php echo $Anterior; ?>','menuEsquerda|conteudoGeral');"> <img src="/e-solution/servicos/bbhive/images/voltar.gif" border="0" align="absmiddle"> Dia anterior </a> | <a href="#@" onClick="return showHome('includes/home.php','conteudoGeral', 'perfil/index.php?perfil=2&perfis=1|mensagens/usuarios/index.php?TimeStamp=<?php echo time(); ?>&DataInicio=<?php echo $Proximo; ?>','menuEsquerda|conteudoGeral');"> Pr&oacute;ximo dia <img src="/e-solution/servicos/bbhive/images/proximoII.gif" border="0" align="absmiddle"> </a> </label>          </td>
        </tr>
      </table></td>
    </tr>
    
    
    <tr>
      <td height="28" colspan="4" valign="bottom" class="verdana_9">Agrupar por:</td>
    </tr>
    <tr class="verdana_9_bold">
      <td width="25%" height="27" style="border-left:1px solid #CCCC99; border-top:1px solid #CCCC99; background-image:url(/e-solution/servicos/bbhive/images/barra_horizontal2.jpg)">&nbsp;<a href="#@" onclick="LoadSimultaneo('perfil/index.php?perfil=2|mensagens/index.php','menuEsquerda|conteudoGeral');"><img src="/e-solution/servicos/bbhive/images/calendar.gif" width="12" height="12" border="0" align="absmiddle" /> Hoje</a></td>
      <td width="25%" style="border-top:1px solid #CCCC99; background-image:url(/e-solution/servicos/bbhive/images/barra_horizontal2.jpg)"><a href="#@" onclick="LoadSimultaneo('perfil/index.php?perfil=2|mensagens/usuarios/index.php','menuEsquerda|conteudoGeral');"><img src="/e-solution/servicos/bbhive/images/equipe-operacional2.gif" alt="" width="12" height="12" border="0" align="absmiddle" /> Profissionais</a></td>
      <td width="25%" style="border-top:1px solid #CCCC99; background-image:url(/e-solution/servicos/bbhive/images/barra_horizontal2.jpg)"><a href="#@" onclick="LoadSimultaneo('perfil/index.php?perfil=2|mensagens/fluxos/index.php','menuEsquerda|conteudoGeral');"><img src="/e-solution/servicos/bbhive/images/tabelaDinamica.gif" alt="" width="12" height="12" border="0" align="absmiddle" /> <?php echo $_SESSION['adm_FluxoNome']; ?></a></td>
      <td width="25%" style="border-top:1px solid #CCCC99; border-right:1px solid #CCCC99; background-image:url(/e-solution/servicos/bbhive/images/barra_horizontal2.jpg)"><a href="#@" onclick="LoadSimultaneo('perfil/index.php?perfil=2|mensagens/departamentos/index.php','menuEsquerda|conteudoGeral');"><img src="/e-solution/servicos/bbhive/images/ger-departamento-16px.gif" alt="" width="12" height="12" border="0" align="absmiddle" /> <?php echo $_SESSION['adm_deptoNome']; ?></a></td>
    </tr>
      <tr>
        <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr style="font-weight:bold;">
            <td height="20" colspan="4">&nbsp;</td>
          </tr>
          <tr style="font-weight:bold; background-image:url(/e-solution/servicos/bbhive/images/barra_horizontal.jpg)">
            <td height="25" colspan="4">&nbsp;<img src="/e-solution/servicos/bbhive/images/equipe-operacional2.gif" alt="" width="12" height="12" border="0" align="absmiddle" /> Agrupamento por profissionais</td>
          </tr>
    <?php if ($totalRows_mensagens > 0) { // Show if recordset not empty ?>
          <tr style="font-weight:bold;">
            <td width="26%" height="24" valign="bottom" style="border-bottom:1px solid #000000;">Profissional</td>
            <td width="28%" valign="bottom" style="border-bottom:1px solid #000000;">Departamento</td>
            <td width="35%" align="center" valign="bottom" style="border-bottom:1px solid #000000;">Enviou / Recebeu</td>
            <td width="11%" align="center" valign="bottom" style="border-bottom:1px solid #000000;">Total</td>
          </tr>
          <?php
		   do {

	$query_cdestin = "SELECT COUNT(bbh_men_codigo) AS dtotal, MAX(bbh_men_data_recebida) as data_recebida, bbh_usu_codigo, bbh_usu_nome FROM bbh_mensagens INNER JOIN bbh_usuario ON bbh_usuario.bbh_usu_codigo = bbh_mensagens.bbh_usu_codigo_destin WHERE bbh_usu_nome = '".$row_mensagens['bbh_usu_nome']."' GROUP BY bbh_usuario.bbh_usu_nome";
	list($cdestin, $row_cdestin, $totalRows_cdestin) = executeQuery($bbhive, $database_bbhive, $query_cdestin);

	$query_cremet = "SELECT COUNT(bbh_men_codigo) AS rtotal, bbh_usu_codigo, bbh_usu_nome FROM bbh_mensagens INNER JOIN bbh_usuario ON bbh_usuario.bbh_usu_codigo = bbh_mensagens.bbh_usu_codigo_remet WHERE bbh_usu_nome = '".$row_mensagens['bbh_usu_nome']."' GROUP BY bbh_usuario.bbh_usu_nome";
	list($cremet, $row_cremet, $totalRows_cremet) = executeQuery($bbhive, $database_bbhive, $query_cremet);

	$query_depto = "SELECT bbh_dep_nome FROM bbh_departamento INNER JOIN bbh_usuario ON bbh_usuario.bbh_dep_codigo = bbh_departamento.bbh_dep_codigo WHERE bbh_usu_codigo = ".$row_mensagens['bbh_usu_codigo'];
	list($depto, $row_depto, $totalRows_depto) = executeQuery($bbhive, $database_bbhive, $query_depto);
		  
		  ?>
          <?php
		  $codUsu = $row_mensagens['bbh_usu_codigo'];
		  ?>
          <tr onClick="LoadSimultaneo('perfil/index.php?perfil=2|/mensagens/usuarios/detalhes.php?bbh_usu_codigo=<?php echo $codUsu; ?>','menuEsquerda|conteudoGeral');" id="u<?php echo $codUsu; ?>" onmouseover="ativaCor('u<?php echo $codUsu; ?>');" onmouseout="desativaCor('u<?php echo $codUsu; ?>');">
            <td style="border-bottom:1px dotted #666666;" height="22">
			<?php
            if(strlen($row_mensagens['bbh_usu_nome'])>19){
				echo substr($row_mensagens['bbh_usu_nome'],0,16)."...";
			}else{
				echo $row_mensagens['bbh_usu_nome'];
			}
			?>            </td>
            <td class="verdana_10" style="border-bottom:1px dotted #999999;"><?php echo $row_depto['bbh_dep_nome']; ?></td>
            <td align="center" class="verdana_10" style="border-bottom:1px dotted #999999;"><img src="/e-solution/servicos/bbhive/images/enviadas.gif" alt="" width="8" height="11" align="absmiddle" />
                <?php if($row_cremet['rtotal']>0){echo $row_cremet['rtotal']; }else{ echo "0"; } ?>
              /
  <?php if($row_cdestin['dtotal']>0){ echo $row_cdestin['dtotal']; }else{ echo "0"; }?>
  <img src="/e-solution/servicos/bbhive/images/recebidas.gif" alt="" width="8" height="11" align="absmiddle" /></td>
            <td align="center" style="border-bottom:1px dotted #666666;"><?php echo $row_cremet['rtotal']+$row_cdestin['dtotal']; ?></td>
          </tr>
            <?php } while ($row_mensagens = mysqli_fetch_assoc($mensagens)); ?>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
                    </table></td>
      </tr>
      <?php } else {// Show if recordset not empty ?>

<tr>
        <td class="color" colspan="4">N&atilde;o h&aacute; mensagens no momento.</td>
      </tr>
      <?php } ?>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>
<?php require_once('../../includes/paginacao/paginacao.php');?>
</form>
<?php
mysqli_free_result($mensagens);
?>
