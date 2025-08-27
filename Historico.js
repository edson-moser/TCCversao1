    document.addEventListener("DOMContentLoaded", () => {
    const botaoNovo = document.querySelector(".novo");
    const corpoTabela = document.getElementById("invoice-body");

    botaoNovo.addEventListener("click", (event) => {
      event.preventDefault();

      const nome = prompt("Digite o nome:");
      if (!nome) return;

      const ano = prompt("Digite o ano:");
      if (!ano) return;


      
      const novaLinha = document.createElement("tr");

      novaLinha.innerHTML = `
        <td>${nome}</td>
        <td>${ano}</td>
      `;

      corpoTabela.appendChild(novaLinha);
    });
  });
