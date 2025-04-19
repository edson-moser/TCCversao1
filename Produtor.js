function editarPerfil() {
    function editarPerfil() {
        console.log('Função editarPerfil chamada');
        
        // Alterar conteúdo para campos editáveis
        document.getElementById("nome").innerHTML = `<input type="text" id="inputNome" value="João Silva">`;
        document.getElementById("cargo").innerHTML = `<input type="text" id="inputCargo" value="Produtor de Café">`;
        document.getElementById("cnpj").innerHTML = `<input type="text" id="inputCnpj" value="00.000.000/0001-00">`;
        document.getElementById("localizacao").innerHTML = `<input type="text" id="inputLocalizacao" value="Fazenda Boa Vista - Zona Rural, MG">`;
        document.getElementById("area-cultivada").innerHTML = `<input type="text" id="inputAreaCultivada" value="20 hectares">`;
        document.getElementById("producao-anual").innerHTML = `<input type="text" id="inputProducaoAnual" value="50 toneladas">`;
        document.getElementById("principais-culturas").innerHTML = `<input type="text" id="inputPrincipaisCulturas" value="Café, Milho, Feijão">`;
        document.getElementById("telefone").innerHTML = `<input type="text" id="inputTelefone" value="(31) 99999-9999">`;
        document.getElementById("email").innerHTML = `<input type="text" id="inputEmail" value="joao@fazendabv.com">`;
      
        // Trocar o botão de "Editar Perfil" por "Salvar"
        document.getElementById("editarButton").innerText = "Salvar Alterações";
        document.getElementById("editarButton").setAttribute("onclick", "salvarPerfil()");
      }
      
      function salvarPerfil() {
        console.log('Função salvarPerfil chamada');
        
        // Capturar os valores dos campos editados
        const nome = document.getElementById("inputNome").value;
        const cargo = document.getElementById("inputCargo").value;
        const cnpj = document.getElementById("inputCnpj").value;
        const localizacao = document.getElementById("inputLocalizacao").value;
        const areaCultivada = document.getElementById("inputAreaCultivada").value;
        const producaoAnual = document.getElementById("inputProducaoAnual").value;
        const principaisCulturas = document.getElementById("inputPrincipaisCulturas").value;
        const telefone = document.getElementById("inputTelefone").value;
        const email = document.getElementById("inputEmail").value;
      
        // Atualizar as informações visíveis
        document.getElementById("nome").innerText = nome;
        document.getElementById("cargo").innerText = cargo;
        document.getElementById("cnpj").innerText = cnpj;
        document.getElementById("localizacao").innerText = localizacao;
        document.getElementById("area-cultivada").innerText = areaCultivada;
        document.getElementById("producao-anual").innerText = producaoAnual;
        document.getElementById("principais-culturas").innerText = principaisCulturas;
        document.getElementById("telefone").innerText = telefone;
        document.getElementById("email").innerText = email;
      
        // Trocar o botão de "Salvar" por "Editar"
        document.getElementById("editarButton").innerText = "Editar Perfil";
        document.getElementById("editarButton").setAttribute("onclick", "editarPerfil()");
        
        alert("Alterações salvas com sucesso!");
      }
    }