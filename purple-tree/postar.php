<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Escrever post</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
		<div class="header">
            <div style='width: 640px; position: relative'>
			<h1><a style='display: flex;' href='feed.php'><img src='media/logo.png'></a></h1>
                <?php
					require '../db.php';
					session_start();
					if (!isset($_SESSION['username'])) {
						header("Location: login.php");  
                    } else {
						$username_sessao = $_SESSION['username'];
                        $username = $username_sessao;
                        $pegar_user = "SELECT * from Usuario WHERE username = '$username'";
                        $lista_user = mysqli_query($c, $pegar_user);
                        $user = mysqli_fetch_assoc($lista_user);
                        echo "<div class='conta'>
                                <div style='position:relative; display: flex; gap: 1em'>
                                <div style='text-align:right' onclick='toggleDropdown()'>
                                    <h2 class='nome'>{$user['nome']}</h2>
                                    <h3 class='username'>@{$user['username']}</h3>
                                </div>
                                <div class='foto' onclick='toggleDropdown()'></div>
                    
                                <ul id='dropdownMenu' class='dropdown' style='display:none'>
                                    <li><a href='perfil.php'>Perfil</a></li>
                                    <li><a id='sair' href='back/logout.php'>Sair</a></li>
                                </ul>
                            </div></div>";
                    }
                ?>
            </div>
        </div>
	</header>

	<main style='padding-top: calc(3em + 11px);'>
		<?php
			if (isset($_GET['id'])) {
				echo "<div id='posts-pais'>";
				$id_pai = $_GET['id'];
				while (true) {
					$post_pai_script = "SELECT * FROM Post WHERE id = '$id_pai'";
					$post_pai = mysqli_query($c, $post_pai_script);
					$post_pai = mysqli_fetch_assoc($post_pai); 
					$id_pai = $post_pai['id_postcomentado'];
					
					$username = $post_pai['username'];
            		$pegar_user = "SELECT * from Usuario WHERE username = '$username'";
            		$lista_user = mysqli_query($c, $pegar_user);
            		$user = mysqli_fetch_assoc($lista_user);

            		$comando_curt = "SELECT COUNT(*) as curtidas from curte WHERE id = '$post_pai[id]'";
            		$comando_comm = "SELECT COUNT(*) as comments from Post WHERE id_postcomentado = '$post_pai[id]'";

            		$sql_curt = mysqli_query($c, $comando_curt);
            		$sql_comm = mysqli_query($c, $comando_comm);

            		$curtidas = mysqli_fetch_assoc($sql_curt);
            		$comments = mysqli_fetch_assoc($sql_comm);

            		if($curtidas['curtidas']!=0) { $curtidas = $curtidas['curtidas']; }
            		else { $curtidas = ''; }
            		if($comments['comments']!=0) { $comments = $comments['comments']; }
            		else { $comments = ''; }

					if (isset($_SESSION['username'])) {
                        $sql_arquivo = "SELECT count(*) as count from curte WHERE username = '$username_sessao' AND id = '$post_pai[id]'";
                        $arquivo_like = mysqli_query($c, $sql_arquivo);
                        $arquivo_like = mysqli_fetch_assoc($arquivo_like);
            
                        if ($arquivo_like['count'] == 1) {
                            $arquivo_like = 'like-preenchido';
                        } else {
                            $arquivo_like = 'curtidas';
                        }
                    } else {$arquivo_like = 'curtidas';}

            		echo "
					<div style='width: 1px;height: 2em;background-color: #503B78;margin: 0 auto;'></div>
            		<div style='max-width: 540px; margin: 0 auto;' onclick='redirectPost(event, {$post_pai['id']})' id='{$post_pai['id']}' class='post'>
            		    <div onclick='redirectPost(event, {$post_pai['id']})' id='header-{$post_pai['id']}' class='header-post' style='position: relative; align-items: flex-start;'>
            		        <div onclick='redirectPerfil(`$username`)' class='foto'></div>
            		        <div>
            		        <h2 onclick='redirectPerfil(`$username`)' class='nome'>{$user['nome']}</h2>
            		        <h3 onclick='redirectPerfil(`$username`)' class='username'>@{$user['username']}</h3>
                            </div>
						</div>
            		    <p onclick='redirectPost(event, {$post_pai['id']})' id='conteudo-{$post_pai['id']}' class='conteudo' style='white-space: pre-wrap;'>{$post_pai['conteudo']}</p>
            		    <div onclick='redirectPost(event, {$post_pai['id']})' id='botoes-{$post_pai['id']}' class='botoes'>
            		        <div>
            		            <button class='like-btn' data-id='{$post_pai['id']}'><img width='20' src='media/{$arquivo_like}.svg'></button>
            		            <p>{$curtidas}</p>
            		        </div>
            		        <div>
            		            <button onclick='redirectComment({$post_pai['id']})'><img src='media/coments.svg'></button>
            		            <p>{$comments}</p>
            		        </div>
            		        <div>
                        		<button onclick='copiarlink($post_pai[id])'><img src='../media/share.svg'></button>
                        		<p id='copiado-{$post_pai['id']}'></p>
                    		</div>
            		    </div>
            		</div>";
				
					if ($post_pai['id_postcomentado'] == Null) {
						break;
					}
				}
				
			}
			$username = $_SESSION['username'];
            $pegar_user = "SELECT * from Usuario WHERE username = '$username'";
            $lista_user = mysqli_query($c, $pegar_user);
            $user = mysqli_fetch_assoc($lista_user);
		?>
		</div>
		<div class='post'>
			<div class='header-post' style='position: relative'>
                <div style='cursor:auto' class='foto'></div>
                <div>
                    <?php
                    	echo "
						<h2 style='cursor:auto' class='nome'>{$user['nome']}</h2>
                    	<h3 style='cursor:auto' class='username'>@{$user['username']}</h3>";
                    ?>
                </div>
            </div>
			<?php
				if (!isset($_GET['id'])) {
					echo "<form class='conteudo' action='back/back-postar.php' method='POST'>";
				} else {
					echo "<form class='conteudo' action='back/back-postar.php?id={$_GET['id']}' method='POST'>";
				}
			?>
    		    <textarea required maxlength='500' id="conteudo" name='conteudo' placeholder="Pergunta séria pra vocês..."></textarea>
				<p id='count'>0/500</p>
				<div class='action'>
					<a id='cancel-post' href='feed.php'>Cancelar</a>
					<input id='post-submit' type="submit" value="Postar">
				</div>
			</form>
		</div>
	</main>
	<script src="script.js"></script>
	<script>
		// contador de caracteres

		const textarea = document.getElementById('conteudo');
		const charCount = document.getElementById('count');
		textarea.addEventListener('input', 
		
		function() {
			this.style.height = 'auto'; 
			this.style.height = this.scrollHeight + 'px';
			const currentLength = this.value.length;
			charCount.textContent = `${currentLength}/500`;
			if (currentLength === 500) {
				charCount.style.color = '#50b110';
			} else {
				charCount.style.color = 'f5efff';
			}
		});
	</script>
</body>
</html>