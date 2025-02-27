<?php

require '../db.php';

session_start();
$username_sessao = $_SESSION['username'];

if (isset($_GET['tab'])) {
    $tab = $_GET['tab'];
    if ($tab == "for-you") {
        $pegar_posts = "SELECT * from Post WHERE id_postcomentado is Null order by Post.id desc";
    } elseif ($tab == "seguindo") {
        $pegar_posts = "SELECT Post.* FROM Post
        INNER JOIN segue ON Post.username = segue.segueusername_2
        WHERE segue.username_1 = '$username_sessao' AND Post.id_postcomentado is Null
        ORDER BY Post.id DESC";
    } else {
        header('Location: ../index.php');
    }
} else {
    header('Location: ../index.php');
}

$posts = mysqli_query($c, $pegar_posts);
foreach ($posts as $post) {
    $username = $post['username'];
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
    <div onclick='redirectPost(event, {$post['id']})' id='{$post['id']}' class='post'>
        <div onclick='redirectPost(event, {$post['id']})' id='header-{$post['id']}' class='header-post' style='position: relative'>
            <div onclick='redirectPerfil(`$username`)' class='foto'></div>
            <div>
                <h2 onclick='redirectPerfil(`$username`)' class='nome'>{$user['nome']}</h2>
                <h3 onclick='redirectPerfil(`$username`)' class='username'>@{$user['username']}</h3>
            </div>";

    if (isset($_SESSION['username'])) {
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
}

?>

<div class="postar">
    <a href="postar.php"><img src='media/+.svg'></a>
</div>
