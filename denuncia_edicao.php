<?php
	//session + banco.
	session_start();
	include("conectar.php");

	//recebendo dados do formulário.
	$id_denuncia        = $_POST['id'];   
	$titulo_denuncia    = $_POST['titulo_denuncia'];
	$anonimato_denuncia = $_POST['anonimato_denuncia'];

	//atualizando no banco.
	$update_denuncia = "UPDATE denuncia 
						SET titulo_denuncia = '$titulo_denuncia',
						    anonimato_denuncia = $anonimato_denuncia
						WHERE id_denuncia = $id_denuncia";
	//executando o camando update.
	$query_denuncia = mysqli_query($conectar, $update_denuncia);

	//fechando a conexão do bd.
	mysqli_close($conectar);

	//redirecionando.
	header("location:ouvidoria_front.php");
?>