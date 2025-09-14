# @kurkle/color

[![npm](https://img.shields.io/npm/v/@kurkle/color?style=plastic)](https://www.npmjs.com/package/@kurkle/color) [![release](https://img.shields.io/github/release/kurkle/color.svg?style=plastic)](https://github.com/kurkle/color/releases/latest) [![npm bundle size](https://img.shields.io/bundlephobia/minzip/@kurkle/color?style=plastic)](https://www.npmjs.com/package/@kurkle/color) [![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/kurkle/color/ci.yml?style=plastic)](https://github.com/kurkle/color) [![GitHub](https://img.shields.io/github/license/kurkle/color?style=plastic)](https://github.com/kurkle/color/blob/main/LICENSE.md)

## Overview

Fast and small CSS color parsing and manipulation library.

## Parsing

Supported formats:

- named

```text
blue
transparent
```

- hex

```text
#aaa
#bbba
#1A2b3c
#f1f2f388
```

- rgb(a)

```text
rgb(255, 255, 255)
rgb(255, 0, 0, 0.5)
rgb(50%, 50%, 50%, 50%)
rgb(0 0 100% / 80%)
rgba(200, 20, 233, 0.2)
rgba(200, 20, 233, 2e-1)
```

- hsl(a)

```text
hsl(240deg, 100%, 50.5%)
hsl(0deg 100% 50%)
hsla(12, 10%, 50%, .3)
hsla(-1.2, 10.2%, 50.9%, 0.4)
```

- hwb

```text
hwb(240, 100%, 50.5%)
hwb(244, 100%, 100%, 0.6)
```

- hsv

```text
hsv(240, 100%, 50.5%)
hsv(244, 100%, 100%, 0.6)
```

## Docs

[typedocs](https://kurkle.github.io/color/)

**note** The docs are for the ESM module. UMD module only exports the [default export](https://kurkle.github.io/color/modules.html#default)

## Benchmarks

[benchmarks](https://kurkle.github.io/color/dev/bench/)

## Size visualization

[color.min.js](https://kurkle.github.io/color/stats.html)

## License

`@kurkle/color` is available under the [MIT license](https://github.com/kurkle/color/blob/main/LICENSE.md).

#  Naturis - Sistema de Gerenciamento de Pequenas Propriedades

Este projeto tem como objetivo auxiliar pequenos agricultores familiares, com foco em produtores de **tabaco e eucalipto**, no gerenciamento de suas propriedades.  
A aplicação fornece ferramentas simples e eficientes para controle de finanças, tarefas, áreas de plantio e histórico de produção, facilitando a tomada de decisão e o planejamento da lavoura.

---

##  Funcionalidades Principais
- **Controle de Saldo** por cultura (Tabaco / Eucalipto) com CRUD completo.
- **Lista de Tarefas** para organização das atividades do dia a dia.
- **Previsão do Tempo** integrada para melhor planejamento.
- **Gerenciamento de Áreas de Plantio** (quantidade, localização, produtividade).
- **Históricos** de despesas, receitas e atividades agrícolas.

---

##  Tecnologias Utilizadas
- **Frontend:** HTML5, CSS3, JavaScript (fetch/AJAX).
- **Backend:** PHP (CRUD + APIs REST).
- **Banco de Dados:** MySQL/MariaDB.
- **Outros:** JSON para comunicação entre frontend e backend.

---

##  Requisitos
Antes de rodar o sistema, você precisará ter instalado:
- PHP >= 8.0  
- MySQL/MariaDB  
- Servidor local (Apache ou Nginx) → recomendado: [XAMPP](https://www.apachefriends.org/) ou [Laragon](https://laragon.org/)

---

**1) Clonar / copiar os arquivos**
    ```bash
        git clone [<URL_DO_REPOSITORIO>](https://github.com/edson-moser/TCCversao1) naturis
        cd naturis
    ```

---

**2) Configurar a conexão com o banco**

    #Rodar este código no mySQL para gerar o Banco de Dados.

    ```sql
criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
INDEX idx_tabaco_produtor (produtor_idprodutor),
FOREIGN KEY (produtor_idprodutor) REFERENCES produtor(idprodutor) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- tabela area
CREATE TABLE IF NOT EXISTS area (
idarea INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(120) NOT NULL,
qtdPes INT,
hectares DOUBLE,
dataInicio DATE,
dataFim DATE,
variedades VARCHAR(150),
produtos VARCHAR(700),
pragasDoencas VARCHAR(200),
agrotoxicos VARCHAR(500),
mediaFolhas INT,
colheitas INT,
tabaco_idtabaco INT,
criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
INDEX idx_area_tabaco (tabaco_idtabaco),
FOREIGN KEY (tabaco_idtabaco) REFERENCES tabaco(idtabaco) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- tabela eucalipto
CREATE TABLE IF NOT EXISTS eucalipto (
ideucalipto INT AUTO_INCREMENT PRIMARY KEY,
area DOUBLE,
qtdEucalipto INT,
dataPlantio DATE,
dataCorte DATE,
produtor_idprodutor INT,
criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
INDEX idx_euca_produtor (produtor_idprodutor),
FOREIGN KEY (produtor_idprodutor) REFERENCES produtor(idprodutor) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- tabela listatarefas
CREATE TABLE IF NOT EXISTS listatarefas (
idlistaTarefas INT AUTO_INCREMENT PRIMARY KEY,
descricao VARCHAR(250),
conclusao TINYINT(1) DEFAULT 0,
produtor_idprodutor INT,
criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
INDEX idx_lista_produtor (produtor_idprodutor),
FOREIGN KEY (produtor_idprodutor) REFERENCES produtor(idprodutor) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- tabela transacao (financeiro)
CREATE TABLE IF NOT EXISTS transacao (
idsaldo INT AUTO_INCREMENT PRIMARY KEY,
valor DECIMAL(12,2),
sinal VARCHAR(1),
descricao VARCHAR(255),
dataOperacao DATE,
produtor_idprodutor INT,
culturas VARCHAR(100),
seletor VARCHAR(100),
criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
INDEX idx_trans_produtor (produtor_idprodutor),
FOREIGN KEY (produtor_idprodutor) REFERENCES produtor(idprodutor) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

---

**3) Configure o host no seu codigo**

    ```<php
        return [
        'db_host' => '127.0.0.1',
        'db_name' => 'naturis',
        'db_user' => 'root',
        'db_pass' => '',
        ];
    ```

---

**4) Rodar o servidor**
```bash
php -S localhost:8000
```
Abra `http://localhost:8000` no navegador.

---

Autores

Edson Moser
Miguel Franz Marchi

