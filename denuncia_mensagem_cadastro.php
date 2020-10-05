<?php
    
    //Sesseion + banco.
	session_start();
	include("conectar.php");

	//recebendo os dados.
	$id_usuario  = $_SESSION['id_usuario'];
	$id_denuncia = $_POST['id_denuncia'];
	$mensagem_denuncia = $_POST['texto_mensagem'];
	$data_mensagem      = date("Y/m/d H:i");
  
    //Se a $_FILES não estiver vazia......
	if(!empty($_FILES['foto']['tmp_name'][0])){


		//inserindo no banco.
		$insert_mensagem = "INSERT INTO mensagem 
							(id_denuncia, id_usuario, data_mensagem, texto_mensagem)
				     VALUES ($id_denuncia, $id_usuario, '$data_mensagem', '$mensagem_denuncia')";
		//executando o comando insert.
		$query_mensagem = mysqli_query($conectar, $insert_mensagem);

      
        //Exetenções de arquivos permitidas,
        $extensoes_permitidas = array("png", "jpeg", "jpg", "mp3", "ogg");

        //Conta quantos arquivos tem na global
        $quantidadeArquivos = count($_FILES['foto']['name']);

        $contador = 0;

        //While que repete até a $quantidadeArquivos
        while($contador < $quantidadeArquivos){

            //Pega a extensao do arquivo
            $extensao = pathinfo($_FILES['foto']['name'][$contador], PATHINFO_EXTENSION);

            //Testa para ver se a extenção do arquivos escolhido pelo usuário está no array
            if(in_array($extensao, $extensoes_permitidas)){

                //Diretório onde as fotos serão armazenadas
                $pasta ="denuncias/";

                //Nome temporário (?)
                $temporario = $_FILES['foto']['tmp_name'][$contador];

                //uniqid faz com que cada foto tenha um nome único e concatena com a extensao
                $foto = uniqid().".$extensao";

                $arquivo_insert = $pasta.$foto;
                // if que testa se manda pra pasta
                    if(move_uploaded_file($temporario, $pasta.$foto)){

                        //echo "Upload feito com sucesso";
                        //Testa a extenção para depois incerir no banco
                        if ($extensao == "png" or $extensao == "jpeg" or $extensao == "jpg"){
        					$tipo = 1;
        				} elseif ($extensao == "mp3" or $extensao =="ogg") {
        					$tipo = 2;
       					}

                        //Incere os arquivos
    				    $insert_arquivo = "INSERT INTO arquivo_denuncia 
    				    				(id_usuario, id_denuncia, data_arquivo_denuncia, endereco_arquivo_denuncia, tipo_arquivo_denuncia) 
    				    			VALUES 
    				    				($id_usuario, $id_denuncia,'$data_mensagem', '$arquivo_insert', $tipo)";
    				    $query_arquivo = mysqli_query($conectar, $insert_arquivo);
            
                    } else {
                        //Se não vai para a pasta
                        echo "não foi possivel fazer o upload.";
                    }

            } else {
                //Se não é das extenções permitidas
                $_SESSION['arquivo_invalido'] = "O formato do arquivo é inválido!";
                echo "Formato inválido";
                header('location:noticias_cadastro_front.php');
                exit;
            }

            $contador++;    
        }

    mysqli_close($conectar);


    header('location:ouvidoria_front.php?id='.$id_denuncia.'');



	} else {

		echo "ola";
		//inserindo no banco.
		$insert_arquivo = "INSERT INTO mensagem 
							(id_denuncia, id_usuario, data_mensagem, texto_mensagem)
				     VALUES ($id_denuncia, $id_usuario, '$data_mensagem', '$mensagem_denuncia')";
		//executando o comando insert.
		$query_arquivo = mysqli_query($conectar, $insert_arquivo);

        //Fechando a conexão.
    	mysqli_close($conectar);

        //Redirecionando.
    	header('location:ouvidoria_front.php?id='.$id_denuncia.'');
	} 
?>