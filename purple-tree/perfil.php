<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
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
            ?>

            <div class='conta'>
                <a href='login.php'>Entrar</a>
                <!-- <a href='cadastro.php'>Cadastrar-se</a> -->
            </div>
            
            <?php    
            } else {
                $username_sessao = $_SESSION['username'];
                $pegar_user = "SELECT * from Usuario WHERE username = '$username_sessao'";
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
                        </div>
                    </div>";
            }
            ?>
            </div>
        </div>
        <?php
        if (isset($_GET['username'])) {
            $username = $_GET['username'];
        } elseif (isset($_SESSION['username'])){
            $username = $_SESSION['username'];
        } else {
            header("Location: login.php");
            exit;
        }

        $pegar_user = "SELECT * from Usuario WHERE username = '$username'";
        $lista_user = mysqli_query($c, $pegar_user);
        $user = mysqli_fetch_assoc($lista_user);

        $quantos_posts = "SELECT count(*) as count from Post WHERE id_postcomentado is Null and username = '$username'";
        $quantos_posts = mysqli_query($c, $quantos_posts);
        $quantos_posts = mysqli_fetch_assoc($quantos_posts);
        
        $seguidores = "SELECT count(*) as count from segue WHERE segueusername_2 = '$username'";
        $seguidores = mysqli_query($c, $seguidores);
        $seguidores = mysqli_fetch_assoc($seguidores);

        $seguindo = "SELECT count(*) as count from segue WHERE username_1 = '$username'";
        $seguindo = mysqli_query($c, $seguindo);
        $seguindo = mysqli_fetch_assoc($seguindo);


        echo "
            <div id='perfil'>
                <div style='padding: 0 1em 1em 0'>
                    <div class='foto-grande'></div>
                    <div class='perfil-tits'>
                        <p class='tit-perfil'>Seguidores</p>
                        <p>{$seguidores['count']}</p>
                    </div>
                    <div class='perfil-tits'>
                        <p class='tit-perfil'>Seguindo</p>
                        <p>{$seguindo['count']}</p>
                    </div>
                    <div class='perfil-tits'>
                        <p class='tit-perfil'>Posts</p>
                        <p>{$quantos_posts['count']}</p>
                    </div>
                </div>
                <div style='justify-content: flex-start; align-items: flex-end; gap: .5em; position: relative'>
                    <h2 class='nome-grande'>{$user['nome']}</h2>
                    <h3 class='username-grande'>@{$user['username']}</h3>";

                    if (!isset($_SESSION['username'])) {
                        echo "<a href='login.php' id='btn-segue' style='color: #110A29;' class='seguir'>Seguir</a>";
                    } else {
                        if ($username != $username_sessao){
                            $ja_segue = "SELECT count(*) as count from segue where username_1 = '$username_sessao' AND segueusername_2 = '$username'";
                            $ja_segue = mysqli_query($c, $ja_segue);
                            $ja_segue = mysqli_fetch_assoc($ja_segue);
                            if ($ja_segue['count']){
                                echo "<a href='back/back-seguir.php?id1={$username_sessao}&id2={$username}' id='btn-segue'>Seguindo</a>";
                            } else {
                                echo "<a href='back/back-seguir.php?id1={$username_sessao}&id2={$username}' id='btn-segue' style='color: #110A29;' class='seguir'>Seguir</a>";
                            }
                        }
                    }
                echo "
                </div>
                <p style='padding: 1em 0 0;'>{$user['biografia']}</p>
            </div>
        ";
        ?>
    </header>

    <main>

        <?php
        if ($quantos_posts['count'] == 0){
            echo "<div id='0-posts'>
            <p>Nenhum post por enquanto</p>
            </div>";
        }
        
        else{
        echo "<h2 style='font-size: 24px; margin-bottom: 1em;'>Posts</h2>";
        $pegar_posts = "SELECT * from Post WHERE id_postcomentado is Null and username = '$username' order by Post.id desc";
        $posts = mysqli_query($c, $pegar_posts);

        foreach ($posts as $post) {
            
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
            <div onclick='redirectPost(event, {$post['id']})' id='{$post['id']}' class='post' style='max-width: 540px; margin: 0 auto'>
                <div onclick='redirectPost(event, {$post['id']})' id='header-{$post['id']}' class='header-post' style='position: relative'>
                    <div style='cursor: auto' class='foto'></div>
                    <div>
                        <h2 style='cursor: auto' class='nome'>{$user['nome']}</h2>
                        <h3 style='cursor: auto' class='username'>@{$user['username']}</h3>
                    </div>";

                if ($username == $_SESSION['username']) {
                    echo "
                    <div style='position: absolute; top:5px; right: 5px; display: flex; gap: 1em;'>
                            <button onclick='redirectEdit({$post['id']})' style='cursor:pointer'><img src='media/edit.svg'></button>
                            <button onclick='toggleExcluir()' style='cursor:pointer;'><img src='media/lixo.svg'></button>
                            <div id='pop-excluir' style='display: none'>
                                <div id='pop-excluir-inner' class='inner'>
                                    <p>Deseja mesmo excluir o post?</p>
                                    <button onclick='Excluir({$post['id']})'>Sim</button>
                                    <button onclick='toggleExcluir()'>Não</button>
                                </div>
                            </div>
                        </div>";
                }

            echo "</div>
                <p onclick='redirectPost(event, {$post['id']})' id='conteudo-{$post['id']}' class='conteudo' style='white-space: pre-wrap;'>{$post['conteudo']}</p>
                <div onclick='redirectPost(event, {$post['id']})' id='botoes-{$post['id']}' class='botoes'>
                    <div>";
                    if (isset($_SESSION['username'])){
                        echo "
                        <button class='like-btn' data-id='{$post['id']}'><img width='20' src='media/{$arquivo_like}.svg'></button>
                        ";
                    } else {
                        echo "
                        <button onclick='redirectLogin()'><img width='20' src='media/{$arquivo_like}.svg'></button>
                        ";
                    }
                    echo "
                        <p>{$curtidas}</p>
                    </div>
                    <div>
                        <button onclick='redirectComment($post[id])'><img src='media/coments.svg'></button>
                        <p>{$comments}</p>
                    </div>
                    <div>
                        <button onclick='copiarlink($post[id])'><img src='../media/share.svg'></button>
                        <p id='copiado-{$post['id']}'></p>
                    </div>
                </div>
            </div>
            <div style='width: 60%;height: 1px;background-color: #503B78;margin: 1.5em auto 1.5em;'></div>";
        }}
        ?>

    </main>
    <script src="script.js"></script>
</body>
</html>
