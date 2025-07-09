# 🏨 Dashboard de Gestão de Reservas - EasyRev

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)

## 📄 Sobre o Projeto

Este projeto é uma aplicação web simples que serve como um dashboard funcional para que um hotel ou pousada possa visualizar e gerenciar suas reservas do dia de forma rápida e eficiente. 

A aplicação é dividida em duas partes principais: um **backend** construído em Laravel, que serve uma API REST para gerenciar os dados, e um **frontend** construído em Vue.js, que consome essa API para apresentar uma interface interativa ao usuário.

## ✨ Funcionalidades

* **Listar Reservas do Dia:** Visualizar em uma tabela todas as reservas com data de check-in para a data atual. 
* **Criar Nova Reserva:** Adicionar uma nova reserva através de um formulário simples. 
* **Atualizar Status da Reserva:** Modificar o status de uma reserva existente (pendente, confirmada, cancelada) com um clique. 
* **Controle Visual de Status:** Os status das reservas são coloridos para fácil identificação. 
* **Validação de Dados:** O sistema possui validação básica para garantir a integridade dos dados. 

## 🛠️ Tecnologias Utilizadas

Este projeto foi construído utilizando as seguintes tecnologias:

* **Backend:**
    * PHP
    * Laravel 
    * MySQL 
    * Docker & Laravel Sail

* **Frontend:**
    * Vue.js 
    * Vite
    * Axios

## ⚙️ Instalação e Execução 

Siga os passos abaixo para configurar e rodar o ambiente de desenvolvimento em qualquer sistema operacional (Windows com WSL2, macOS, ou Linux).

### **Pré-requisitos**
* Git
* Docker Desktop
* Node.js e NPM
* Composer

---

## Siga exatamente os comandos na ordem a abaixo:
### **1. Clonando o repositório (Github)**
```bash
# 1. Clone o repositório para o seu computador
git clone https://github.com/Goguel/easyrev-dashboard easyrev-dashboard
cd easyrev-dashboard
```

---

### **2. Configuração do Backend (Laravel + Sail)**

```bash
# 1. Navegue até a pasta do backend
cd backend

# 2. Copie o arquivo de configuração de ambiente
# Este arquivo guarda senhas e configurações importantes.
cp .env.example .env

# 3. Subir Apenas o Banco de Dados:
# Iniciar apenas o contêiner do banco de dados, que não tem dependências.
docker-compose up -d db
  
# 4. Instalar as Dependências do Backend (Composer):
# Cria um contêiner temporário do `backend` com o único objetivo de instalar as dependências. 
docker-compose run --rm backend composer install

# 5. Iniciar Todos os Serviços:
# Pode subir todos os serviços de forma segura.
docker-compose up -d

# 6. Finalizar a Configuração do Banco de Dados
# Com os contêineres no ar e rodando, execute as migrações do banco de dados.
docker-compose exec backend php artisan migrate

# 7. Gerar a Chave da Aplicação (Garantia):
# O `.env` foi copiado, mas é uma boa prática garantir que a chave da aplicação está gerada para esta instância.
docker-compose exec backend php artisan key:generate
```
Para acessar o backend, acesse `http://localhost:8000`

### **2. Configuração do Frontend (Vue.js)**
```bash
# 1. Navegue até a pasta do frontend
cd ../frontend

# 2. Intalar dependências (Npm):
# Instala todas as dependências requeridas pelo frontend
npm install

# 3. Rodar frontend
# Põe o frontend em execução e pronto para ser acessado
npm run dev
```

Acesse a aplicação no seu navegador no endereço fornecido pelo `npm run dev` (geralmente **`http://localhost:5173`**).<br />


## Endpoints da API

A API do backend expõe as seguintes rotas. O endereço base é `http://localhost/api` (ou `http://localhost:8000/api` se você mudou a porta).

| Método | URI | Ação | Corpo (JSON) |
| :--- | :--- | :--- | :--- |
| `GET` | `/reservations/today` | Retorna uma lista de todas as reservas com check-in hoje. | - |
| `POST`| `/reservations` | Cria uma nova reserva. | `{ "guest_name": "string", "check_in_date": "YYYY-MM-DD", "check_out_date": "YYYY-MM-DD", "guest_count": "integer" }` |
|`PATCH`| `/reservations/{id}`| Atualiza o status de uma reserva existente. | `{ "status": "confirmada" }` (pendente, confirmada, cancelada) |

## Estrutura do Banco de Dados

A aplicação utiliza uma única tabela principal: `reservations`.

| Coluna | Tipo | Descrição |
| :--- | :--- | :--- |
| `id` | BIGINT (Unsigned) | Identificador único da reserva (Chave Primária).  |
| `guest_name` | VARCHAR(255) | Nome do hóspede principal. |
| `check_in_date` | DATE | Data de entrada da reserva.  |
| `check_out_date`| DATE | Data de saída da reserva. |
| `guest_count` | INTEGER | Número de hóspedes na reserva. |
| `status` | VARCHAR(255) | Status atual da reserva ('pendente', 'confirmada', 'cancelada').  |
|`created_at`| TIMESTAMP | Data e hora de criação do registro. |
|`updated_at`| TIMESTAMP | Data e hora da última atualização do registro. 