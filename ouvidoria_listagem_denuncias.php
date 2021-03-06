<?php 

//PERFIL

	//Id do usuario.
	$id_usuario = $_SESSION['id_usuario'];

	//Pegando informações referentes oa id da sessão.
	$select_usuario      = "SELECT * FROM usuario WHERE id_usuario = $id_usuario";
	$query_usuario       = mysqli_query($conectar, $select_usuario);
	$informacoes_usuario = mysqli_fetch_array($query_usuario);

	//Listando as informações obtidas no select.
	echo '
		<div class="row ">
			<div class="col text-center mb-3 mt-1">
				<i class="far fa-user fa-5x mt-3"></i>
				
			</div>
		</div>
		<div class="row">
			<div class="col text-center texto-corpo">
				'.$informacoes_usuario['nome_usuario'].'
			</div>
		</div>
		<div class="row">
			<div class="col text-center texto-corpo">
				'.$informacoes_usuario['email_usuario'].'
				<hr>
			</div>
		</div>
	';

	//Exibição da session que indica erro na edição;
	if(isset($_SESSION['erros_edicao'])) {
		echo "<div class='alert alert-danger texto-corpo' style='font-size: 15px;' role='alert'>";
			echo $_SESSION['erros_edicao'];
		echo "</div>";
	}


//CADASTRO DA DENUNCIA.

    //Se o usuario é do tipo 2, é um usuário "comum". Isso indica que ele pode cadastrar uma denúncia.
	if($_SESSION['tipo_usuario'] == 2){
		
		//Botão que chama o formulário de cadastro e o próprio formulário.
		echo '
			<!-- Botão que "chama" o formulário de cadastro. -->
			<div class="row">
				<div class="col">
					<button type="button" 
							id="mostrar-form" 
							class="btn rounded-0 btn-lg btn-block texto-login mt-2 mb-2"
							style="background-color: #3CB371;">
						<i class="fas fa-plus mr-2"></i>
					    Cadastrar Denúncia
					</button>
				</div>
			</div>

			<!-- Form de Cadastro. -->
			<form id="cadastrar" action="denuncia_cadastro.php" method="post" style="display:none">
				
				<!-- Título.-->
				<div class="row text-center">
					<div class="col-12">
						<input type="text" 
						       class="form-control mt-2" 
						       placeholder="De um Título a Denúncia" 
						       name="titulo_denuncia" required>
					</div>
			    </div>
								  
				<!-- Anonimato.-->
			    <div class="row justify-content-center">
					<div class="col-5">
						<input type="radio" required name="anonimato_denuncia" value="1"> 
						Anônima
					</div>
					<div class="col-5 text-right">
						<input type="radio" required name="anonimato_denuncia" value="2"> 
							Renomada
					</div>
			    </div>
								    
				<!-- Button. -->
				<div class="row justify-content-center">
					<div class="col-10">					    
						<input type="submit" class="btn btn-block btn-outline-success mt-2 mb-2 texto-login" value="Cadastrar">
						<hr>
					</div>
			    </div>
			</form> ';

//LISTAGEM DAS DENÚNCIA 

		//como o usuário não é adm, só as suas denuncias devem ser selecionadas.
		$select = "SELECT * FROM  denuncia
				   WHERE id_usuario = $id_usuario 
			       ORDER BY data_denuncia DESC";

		//query exeutando o select.
		$query = mysqli_query($conectar, $select);

		//foreach pra conseguir as infos da denuncia.
		foreach ($query as $denuncia) {
			$id              = $denuncia['id_denuncia'];
			$titulo_denuncia = $denuncia['titulo_denuncia'];

		    //Listagem do titulo das denúncias.
			echo '
			<div class="row">
					<div class="col-2 mr-0 pr-0">
						<a  href="#" 
		                    class="btn rounded-0 btn-block texto-login mt-2 mb-2 text-center" 
		                     style="background-color: #716D6D;"
		                    data-toggle="collapse" 
						    data-target="#collapseExample'.$id.'"
			                role="button" 
			                aria-expanded="false" 
						    aria-controls="collapseExample'.$id.'">
						<i class="fas fa-caret-down"></i>
						</a>
		            </div>
						              
					<div class="col-10 ml-0 pl-0">
						<a  href="ouvidoria_front.php?id='.$id.'"
							style="background-color: #716D6D;"
						    class="btn rounded-0 btn-block texto-login mb-2 mt-2 text-center">
						    '.$titulo_denuncia.'
						</a>
		           </div>		
  				</div>

  				<!-- Botões do CRUD. -->	
  				<div class="row mb-2 collapse" id="collapseExample'.$id.'">

	  				<div class="col-6">
						<button type="button" 
								class="btn btn-warning btn-block texto-login text-white"
								data-toggle="modal" 
								data-target="#exampleModal" 
								data-whateverid="'.$id.'"
								data-whatevertitulo="'.$titulo_denuncia.'">
							Editar <i class="fas fa-pen mr-1"></i>
						</button>
					</div>
									
					<div class="col-6">
						<a href="denuncia_excluir.php?id='.$id.'"
						   class="btn btn-block texto-login btn-danger"
						   data-confirm="Tem certeza que deseja excluir essa denúncia?">
						Excluir
						<i class="fas fa-trash ml-1"></i> 
						</a>
					</div>
				</div>';
		}//fim do foreach.
							
		//Se o usuário for adm, ele só terá acesso as denúncias. Não poderá cadastrá-las, editá-las ou excluir-las.
		} else if($_SESSION['tipo_usuario'] == 1){
					
			//Selecionando informações de todas as denúncias.
			$select = "SELECT * FROM denuncia 
			           ORDER BY data_denuncia DESC";
			
			//query executando o select.		
			$query = mysqli_query($conectar, $select);

			//foreache pra obter as informações.
			foreach ($query as $denuncia) {
				$id              = $denuncia['id_denuncia'];
				$titulo_denuncia = $denuncia['titulo_denuncia'];

 				//Listagem dos títulos
				echo '
					<div class="row">
						<div class="col">
							<a  href="ouvidoria_front.php?id='.$id.'" 
						        style="background-color: #716D6D;"
						    	class="btn rounded-0 btn-block texto-login mb-2 mt-2 text-center">
						        '.$titulo_denuncia.'
			                </a>
  						</div>
  					</div>';

			}//fim do foreach.

		}//fim do else if
						
?>

	<!-- Modal da Edição do título da denuncia. -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			  	<div class="modal-header">
			  		<h4 class="modal-title texto-buttons" id="exampleModalLabel">EDITAR TÍTULO</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			    </div>
			  	<div class="modal-body">
					<form method="POST" action="denuncia_edicao.php" enctype="multipart/form-data">
				  		
						<!-- Título -->
				  		<div class="form-group">
							<label for="recipient-name" class="control-label">Titulo:</label>
							<input name="titulo" type="text" class="form-control" id="titulodenuncia">
				 		</div>
				  
				  		<!-- id -->
				  		<?php 
				  		//Se houver algo no GET, armazena.
				  		if (isset($_GET['id'])){
							echo '<input name="id" 
										 type="hidden" 
										 class="form-control" 
										 id="iddenuncia" 
										 value="'.$_GET["id"].'">';
				  			unset($_SESSION['erros_edicao']);
				  		//Se não há, armazena um espaço em branco. Caso isso não fosse feito, haveriam erros na edição.
				  		} else {
				  			echo '
				  				<input name="id" 
				  					   type="hidden" 
				  					   class="form-control" 
				  					   id="iddenuncia" 
				  					   value=""> ';
				  		}
				  		?>
						
						<button type="button" data-dismiss="modal" class="btn texto-buttons text-white" style="background-color: #3CB371">CANCELAR</button>
						<button type="submit" class="btn texto-buttons text-white" style="background-color: #f35753">EDITAR</button>
					</form>
			    </div>
			</div>
		</div>
	</div>
  	
  	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

	<script>
		//FORMULÁRIO DE CAD
		$("#mostrar-form").click(function () {
			$("#cadastrar").toggle();
		})

		//MODAL EXCLUSÃO
		  $(document).ready(function(){
			$('a[data-confirm]').click(function(ev){
				var href = $(this).attr('href');
				if(!$('#confirm-delete').length){
					$('body').append('<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header texto-buttons">EXCLUIR ITEM<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body texto-corpo">Tem certeza de que deseja excluir esta denúncia?</div><div class="modal-footer"><button type="button" class="btn texto-buttons text-white" style="background-color: #3CB371" data-dismiss="modal">CANCELAR</button><a class="btn texto-buttons text-white" style="background-color: #f35753"  id="dataComfirmOK">APAGAR</a></div></div></div></div>');
				}
				$('#dataComfirmOK').attr('href', href);
		        $('#confirm-delete').modal({show: true});
				return false;
				
			});
		});
  
		/*MODAL EDIÇÃO
		$('#exampleModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var recipient = button.data('whateverid') // Extract info from data-* attributes
		  var recipienttitulo = button.data('whatevertitulo')
		  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		  var modal = $(this)
		  modal.find('.modal-title').text('ID ' + recipient)
		  modal.find('#iddenuncia').val(recipient)
		  modal.find('#titulodenuncia').val(recipienttitulo)
		}) */

		

    </script>