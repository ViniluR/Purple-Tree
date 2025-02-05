document.addEventListener('click', 
    
    function(event) {
        var dropdown = document.getElementById("dropdownMenu");
        var conta = document.querySelector('.conta');

        if (!conta.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });

function toggleDropdown() {
        var dropdown = document.getElementById("dropdownMenu");
        if (dropdown.style.display === "none") {
            dropdown.style.display = "flex";
        } else {
            dropdown.style.display = "none";
}}

// post

function redirectPost(event, id) {
    const div = document.getElementById(`${id}`);
    const botoes = document.getElementById(`botoes-${id}`);
    const conteudo = document.getElementById(`conteudo-${id}`);
    const header = document.getElementById(`header-${id}`);

    if (event.target === div || event.target === botoes || event.target === conteudo || event.target === header) {
        window.location.href = `post/post.php?id=${id}`;
    }
}

function redirectComment(id) {
    window.location.href = `postar.php?id=${id}`;
}

function redirectEdit(id) {
    window.location.href = `post/edit-post.php?id=${id}`;
}

function toggleExcluir(){
    var pop = document.getElementById("pop-excluir");

    if (pop.style.display === "none") {
        pop.style.display = "flex";
    } else {
        pop.style.display = "none";
    }
}

function Excluir(id) {
    window.location.href = `post/excluir-post.php?id=${id}`
}

function copiarlink(id) {
    var link = `http://purple-tree.com.br/post/post.php?id=${id}`;
    var p = document.getElementById(`copiado-${id}`);

    var textarea = document.createElement("textarea");
    textarea.value = link;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand("copy");
    document.body.removeChild(textarea);

    p.innerText = "Copiado!";
    setTimeout(() => p.innerText = "", 1000);
}

// like

document.addEventListener("DOMContentLoaded", function () {
    document.body.addEventListener("click", function (event) {
        let button = event.target.closest(".like-btn"); 
        if (!button) return;

        let postId = button.getAttribute("data-id");
        let icon = button.querySelector("img");
        let countElement = button.nextElementSibling;

        fetch("back/like.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "post_id=" + encodeURIComponent(postId)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "liked") {
                icon.src = "media/like-preenchido.svg";
            } else if (data.status === "unliked") {
                icon.src = "media/curtidas.svg";
            } else {
                console.error("Resposta inesperada:", data);
                return;
            }
            countElement.textContent = data.likes > 0 ? data.likes : "";
        })
        .catch(error => console.error("Erro na requisição:", error));
    });
});

function redirectLogin(){
    window.location.href = "login.php";
}

// perfil

function redirectPerfil(id) {
    window.location.href = `http://purple-tree.com.br/perfil.php?username=${id}`;
}

// feed 


