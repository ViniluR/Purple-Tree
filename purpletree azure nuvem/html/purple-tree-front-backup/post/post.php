<?php
include '../../db.php';
session_start();

if (!isset($_GET['id'])) {
    die("ID do post não especificado.");
}

$id = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Post</title>
	<link rel="stylesheet" href="../styles.css">
	<script src="../script.js"></script>
</head>
<body>
<header>
        <div class="header">
            <div style='width: 640px; position: relative'>
            	<h1><a style='display: flex;' href='../index.php'><img src='../media/logo.png'></a></h1>
                <?php
                    if (!isset($_SESSION['username'])) {
                ?>

                <div class='conta'>
                    <a href='../login.php'>Entrar</a>
                </div>
                    
                <?php    
                    } else {
                        $username_session = $_SESSION['username'];
                        $pegar_user = "SELECT * from Usuario WHERE username = '$username_session'";
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
                                    <li><a id='sair' href='../back/logout.php'>Sair</a></li>
                                </ul>
                            </div></div>";
                    }
                ?>
            </div>
        </div>
	</header>

	<main style='padding-top: calc(3em + 11px);'>
        <?php
            $pegar_post = "SELECT * from Post WHERE id = '$id'";
            $post = mysqli_query($c, $pegar_post);
            $post = mysqli_fetch_assoc($post);

// ---------------------------------- posts anteriores ----------------------------------------------

            if ($post['id_postcomentado'] != Null) {
                echo "<div id='posts-pais'>";
                $id_pai = $post['id_postcomentado'];
            while (true) {
                $post_pai_script = "SELECT * FROM Post WHERE id = '$id_pai'";
                $post_pai = mysqli_query($c, $post_pai_script);
                $post_pai = mysqli_fetch_assoc($post_pai); 
                $id_pai = $post_pai['id_postcomentado'];
                
                $username = $post_pai['username'];
                $username_sessao = $_SESSION['username'];
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
                        <div onclick='redirectPerfil({$post['username']})' class='foto'></div>
                        <div>
                        <h2 onclick='redirectPerfil({$post['username']})' class='nome'>{$user['nome']}</h2>
                        <h3 onclick='redirectPerfil({$post['username']})' class='username'>@{$user['username']}</h3>
                        </div>";

                if ($post_pai['username'] == $username_session) {
                    echo "
                        <div style='position: absolute; top:5px; right: 5px; display: flex; gap: 1em;'>
                            <button onclick='redirectEdit({$post_pai['id']})' style='cursor:pointer'><img src='..//media/edit.svg'></button>
                            <button onclick='toggleExcluir()' style='cursor:pointer;'><img src='../media/lixo.svg'></button>
                            <div id='pop-excluir' style='display: none'>
                                <div id='pop-excluir-inner' class='inner'>
                                    <p>Deseja mesmo excluir o post?</p>
                                    <button onclick='Excluir({$post_pai['id']})'>Sim</button>
                                    <button onclick='toggleExcluir()'>Não</button>
                                </div>
                            </div>
                        </div>";
                }
            
                echo "</div>
                    <p onclick='redirectPost(event, {$post_pai['id']})' id='conteudo-{$post_pai['id']}' class='conteudo' style='white-space: pre-wrap;'>{$post_pai['conteudo']}</p>
                    <div onclick='redirectPost(event, {$post_pai['id']})' id='botoes-{$post_pai['id']}' class='botoes'>
                        <div>";
                    if (isset($_SESSION['username'])){
                        echo "
                            <button class='like-btn' data-id='{$post_pai['id']}'><img width='20' src='../media/{$arquivo_like}.svg'></button>
                        ";
                    } else {
                        echo "
                            <button onclick='redirectLogin()'><img width='20' src='../media/{$arquivo_like}.svg'></button>
                        ";
                    }
                    echo "
                            <p>{$curtidas}</p>
                        </div>
                        <div>
                            <button onclick='redirectComment({$post_pai['id']})'><img src='../media/coments.svg'></button>
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
            }}

// ---------------------------------- post da página ----------------------------------------------

            $username = $post['username'];
            $username_sessao = $_SESSION['username'];
            $pegar_user = "SELECT * from Usuario WHERE username = '$username'";
            $lista_user = mysqli_query($c, $pegar_user);
            $user = mysqli_fetch_assoc($lista_user);

            $comando_curt = "SELECT COUNT(*) as curtidas from curte WHERE id = '$post[id]'";
            $comando_comm = "SELECT COUNT(*) as comments from Post WHERE id_postcomentado = '$post[id]'";

            $sql_curt = mysqli_query($c, $comando_curt);
            $sql_comm = mysqli_query($c, $comando_comm);

            $curtidas = mysqli_fetch_assoc($sql_curt);
            $comments = mysqli_fetch_assoc($sql_comm);

            if($curtidas['curtidas']!=0) { $curtidas = $curtidas['curtidas']; }
            else { $curtidas = ''; }
            if($comments['comments']!=0) { $comments = $comments['comments']; }
            else { $comments = ''; }

            if (isset($_SESSION['username'])) {
                    $sql_arquivo = "SELECT count(*) as count from curte WHERE username = '$username_sessao' AND id = '$post[id]'";
                    $arquivo_like = mysqli_query($c, $sql_arquivo);
                    $arquivo_like = mysqli_fetch_assoc($arquivo_like);
        
                    if ($arquivo_like['count'] == 1) {
                        $arquivo_like = 'like-preenchido';
                    } else {
                        $arquivo_like = 'curtidas';
                    }
                } else {$arquivo_like = 'curtidas';}

            echo "
            </div>
                <div class='post' id='{$post['id']}'>
                    <div class='header-post' style='position: relative'>
                        <div onclick='redirectPerfil({$post['username']})' class='foto'></div>
                        <div>
                            <h2 onclick='redirectPerfil({$post['username']})' class='nome'>{$user['nome']}</h2>
                            <h3 onclick='redirectPerfil({$post['username']})' class='username'>@{$user['username']}</h3>";
                            ?>
                        </div>
                        <?php
                            if (isset($_SESSION['username'])) {
                                if ($post['username'] == $username_session) {
                                    echo "
                                    <div style='position: absolute; top:5px; right: 5px; display: flex; gap: 1em;'>
                                        <button onclick='redirectEdit({$post['id']})' style='cursor:pointer'><img src='../media/edit.svg'></button>
                                        <button onclick='toggleExcluir()' style='cursor:pointer;'><img src='../media/lixo.svg'></button>
                                        <div id='pop-excluir' style='display: none'>
                                            <div id='pop-excluir-inner' class='inner'>
                                                <p>Deseja mesmo excluir o post?</p>
                                                <button onclick='Excluir({$post['id']})'>Sim</button>
                                                <button onclick='toggleExcluir()'>Não</button>
                                            </div>
                                        </div>
                                    </div>";
                                }
                            }
                        ?>
                    </div>
                    <?php
                    echo "
                    <p class='conteudo' style='white-space: pre-wrap;'>{$post['conteudo']}</p>
                    <div class='botoes'>
                        <div>";
                    if (isset($_SESSION['username'])){
                        echo "
                            <button class='like-btn' data-id='{$post['id']}'><img width='20' src='../media/{$arquivo_like}.svg'></button>
                        ";
                    } else {
                        echo "
                            <button onclick='redirectLogin()'><img width='20' src='../media/{$arquivo_like}.svg'></button>
                        ";
                    }
                    echo "
                            <p>{$curtidas}</p>
                        </div>
                        <div>
                            <button onclick='redirectComment({$post['id']})'><img src='../media/coments.svg'></button>
                            <p>{$comments}</p>
                        </div>
                        <div>
                            <button onclick='copiarlink($post[id])'><img src='../media/share.svg'></button>
                            <p id='copiado-{$post['id']}'></p>
                        </div>
                    </div>
                </div>
                <div style='width: 60%;height: 1px;background-color: #503B78;margin: 1.5em auto 1.5em;'></div>";

// ---------------------------------- comentários ----------------------------------------------

            $pegar_comentarios = "SELECT * FROM Post WHERE id_postcomentado = '$post[id]'";
            $lista_comentarios = mysqli_query($c, $pegar_comentarios);

            foreach ($lista_comentarios as $comentario) {

                $username = $comentario['username'];
                $pegar_user = "SELECT * from Usuario WHERE username = '$username'";
                $lista_user = mysqli_query($c, $pegar_user);
                $user = mysqli_fetch_assoc($lista_user);
            
                $comando_curt = "SELECT COUNT(*) as curtidas from curte WHERE id = '$comentario[id]'";
                $comando_comm = "SELECT COUNT(*) as comments from Post WHERE id_postcomentado = '$comentario[id]'";
            
                $sql_curt = mysqli_query($c, $comando_curt);
                $sql_comm = mysqli_query($c, $comando_comm);
            
                $curtidas = mysqli_fetch_assoc($sql_curt);
                $comments = mysqli_fetch_assoc($sql_comm);
            
                if($curtidas['curtidas']!=0) { $curtidas = $curtidas['curtidas']; }
                else { $curtidas = ''; }
                if($comments['comments']!=0) { $comments = $comments['comments']; }
                else { $comments = ''; }

                if (isset($_SESSION['username'])) {
                    $sql_arquivo = "SELECT count(*) as count from curte WHERE username = '$username_sessao' AND id = '$comentario[id]'";
                    $arquivo_like = mysqli_query($c, $sql_arquivo);
                    $arquivo_like = mysqli_fetch_assoc($arquivo_like);
        
                    if ($arquivo_like['count'] == 1) {
                        $arquivo_like = 'like-preenchido';
                    } else {
                        $arquivo_like = 'curtidas';
                    }
                } else {$arquivo_like = 'curtidas';}
                
                echo "
                <div style='max-width: 540px; margin: 0 auto;' onclick='redirectPost(event, {$comentario['id']})' id='{$comentario['id']}' class='post'>
                    <div onclick='redirectPost(event, {$comentario['id']})' id='header-{$comentario['id']}' class='header-post' style='position: relative; align-items: flex-start;'>
                        <div onclick='redirectPerfil(`$username`)' class='foto'></div>
                        <div>
                        <h2 onclick='redirectPerfil(`$username`)' class='nome'>{$user['nome']}</h2>
                        <h3 onclick='redirectPerfil(`$username`)' class='username'>@{$user['username']}</h3>
                        </div>";
            
                if ($comentario['username'] == $username_session) {
                    echo "
                        <div style='position: absolute; top:5px; right: 5px; display: flex; gap: 1em;'>
                            <button onclick='redirectEdit({$comentario['id']})' style='cursor:pointer'><img src='../media/edit.svg'></button>
                            <button onclick='toggleExcluir()' style='cursor:pointer;'><img src='../media/lixo.svg'></button>
                            <div id='pop-excluir' style='display: none'>
                                <div id='pop-excluir-inner' class='inner'>
                                    <p>Deseja mesmo excluir o post?</p>
                                    <button onclick='Excluir({$comentario['id']})'>Sim</button>
                                    <button onclick='toggleExcluir()'>Não</button>
                                </div>
                            </div>
                        </div>";
                }
            
                echo "</div>
                    <p onclick='redirectPost(event, {$comentario['id']})' id='conteudo-{$comentario['id']}' class='conteudo' style='white-space: pre-wrap;'>{$comentario['conteudo']}</p>
                    <div onclick='redirectPost(event, {$comentario['id']})' id='botoes-{$comentario['id']}' class='botoes'>
                        <div>";
                            if (isset($_SESSION['username'])){
                                echo "
                                <button class='like-btn' data-id='{$comentario['id']}'><img width='20' src='../media/{$arquivo_like}.svg'></button>
                                ";
                            } else {
                                echo "
                                <button onclick='redirectLogin()'><img width='20' src='../media/{$arquivo_like}.svg'></button>
                                ";
                            }
                            echo "
                            <p>{$curtidas}</p>
                        </div>
                        <div>
                            <button onclick='redirectComment({$comentario['id']})'><img width='20' src='../media/coments.svg'></button>
                            <p>{$comments}</p>
                        </div>
                        <div>
                            <button onclick='copiarlink($comentario[id])'><img src='../media/share.svg'></button>
                            <p id='copiado-{$comentario['id']}'></p>
                        </div>
                    </div>
                </div>
                <div style='width: 60%;height: 1px;background-color: #503B78;margin: 1em auto;'></div>";
            
            }

        ?>
	</main>

<script>
function redirectPost(event, id) {
    const div = document.getElementById(`${id}`);
    const botoes = document.getElementById(`botoes-${id}`)
    const conteudo = document.getElementById(`conteudo-${id}`)
    const header = document.getElementById(`header-${id}`)

    if (event.target === div || event.target === botoes || event.target === conteudo || event.target === header) {
        window.location.href = `http://ics-purpletreeweb.freedynamicdns.net/purple-tree/post/post.php?id=${id}`;
    }
}

function redirectComment(id) {
    window.location.href = `http://ics-purpletreeweb.freedynamicdns.net/purple-tree/postar.php?id=${id}`;
}

function redirectEdit(id) {
    window.location.href = `http://ics-purpletreeweb.freedynamicdns.net/purple-tree/post/edit-post.php?id=${id}`;
}

function redirectLogin(){
    window.location.href = "http://ics-purpletreeweb.freedynamicdns.net/purple-tree/login.php";
}

function toggleExcluir() {
    var pop = document.getElementById("pop-excluir");

    if (pop.style.display === "none") {
        pop.style.display = "flex";
    } else {
        pop.style.display = "none";
    }
}

function Excluir(id) {
    window.location.href = `http://ics-purpletreeweb.freedynamicdns.net/purple-tree/post/excluir-post.php?id=${id}`
}

document.addEventListener("DOMContentLoaded", function () {
    document.body.addEventListener("click", function (event) {
        let button = event.target.closest(".like-btn"); 
        if (!button) return;

        let postId = button.getAttribute("data-id");
        let icon = button.querySelector("img");
        let countElement = button.nextElementSibling;

        fetch("../back/like.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "post_id=" + encodeURIComponent(postId)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "liked") {
                icon.src = "../media/like-preenchido.svg";
            } else if (data.status === "unliked") {
                icon.src = "../media/curtidas.svg";
            } else {
                console.error("Resposta inesperada:", data);
                return;
            }

            countElement.textContent = data.likes > 0 ? data.likes : "";
        })
        .catch(error => console.error("Erro na requisição:", error));
    });
});


</script>
</body>
</html>
