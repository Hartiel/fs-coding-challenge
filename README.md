# Desafio Técnico: Chat Multicanal (Laravel/Vue/Inertia)

Implementação de uma área de atendimento simulada para gerenciar conversas com contatos através de diferentes canais (WhatsApp, Messenger, Email).

## Stack Utilizada

* **Backend:** Laravel 12
* **Frontend:** Vue 3 (Composition API) + Inertia.js + Tailwind CSS
* **Banco de Dados:** SQLite
* **Processamento Assíncrono:** Laravel Queues (Driver: database)
* **Testes:** Pest PHP

---

## 🚀 Instruções de Instalação e Configuração

**Pré-requisitos:** PHP 8.3+, Composer, Node.js.

1.  **Clonar o repositório:**
    ```bash
    git clone https://github.com/Hartiel/fs-coding-challenge.git
    cd pipelead-challenge
    ```

2.  **Instalar dependências (Backend):**
    ```bash
    composer install
    ```

3.  **Configurar Ambiente:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Instalar dependências (Frontend):**
    ```bash
    npm install
    ```

5.  **Rodar as Migrações e Seeders:**
    ```bash
    php artisan migrate:fresh --seed
    ```

---

## Como Testar

```bash
php artisan test
```

## 🛠️ Como Executar

O projeto utiliza o `concurrently` (via `composer dev`) para iniciar todos os serviços necessários.

1.  **Iniciar os servidores (Backend, Frontend e Queue Worker):**
    ```bash
    composer dev
    ```

2.  **Acessar a aplicação:**
    * Abra o navegador em: `http://127.0.0.1:8000`

---

## ⚙️ Comandos Úteis

### Gerar Mensagens Fake

Para popular o chat com dados de teste.

```bash
# Gera 50 mensagens fake (padrão)
php artisan messages:generate

# Gera 200 mensagens fake
php artisan messages:generate --count=200