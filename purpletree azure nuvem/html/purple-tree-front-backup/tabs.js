document.addEventListener("DOMContentLoaded", function () {
    const tabContent = document.getElementById("tab-content");

    function loadTabContent(tabId) {
        fetch(`back/load-tab.php?tab=${tabId}`)
            .then(response => response.text())
            .then(data => {
                tabContent.innerHTML = data;
            })
            .catch(error => console.error("Erro ao carregar a aba:", error));
    }

    document.querySelectorAll(".tab-link").forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();

            let tabId = this.getAttribute("data-tab");

            // Remove a classe "active" de todas as abas
            document.querySelectorAll(".tab-link").forEach(link => link.classList.remove("active"));
            this.classList.add("active");

            // Carrega o conteúdo da aba
            loadTabContent(tabId);
        });
    });

    // Carrega a aba inicial (For You)
    loadTabContent('for-you');
});
