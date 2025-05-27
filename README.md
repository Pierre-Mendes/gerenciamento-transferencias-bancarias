<img src="https://github.com/Pierre-Mendes/First-Challenge-Bootcamp-Java-DIO/assets/63386178/da4a13ca-375c-4546-99e5-034786980e47" alt="Banner" style="width:100%;" />

---

# 💸 Gerenciamento de Transferências Bancárias
![PHP Version](https://img.shields.io/badge/PHP-8.4-blue)
![Laravel](https://img.shields.io/badge/Laravel-12-red)
![License](https://img.shields.io/badge/license-MIT-green)
![Status](https://img.shields.io/badge/status-finalizado-brightgreen)

Este repositório contém a solução para um sistema de gerenciamento de transferências bancárias, com arquitetura moderna, desacoplada e uso de mensageria assíncrona com Kafka.

> **PHP · Laravel · Kafka · Docker · SQL · SOLID · Design Patterns · Boas práticas**

## 🎯 Desafio
Criação de endpoints para um sistema bancário robusto, com processamento assíncrono de transferências:
🔹 CRUD de Usuários
🔹 CRUD de Contas (um usuário pode ter várias contas)
🔹 CRUD e processamento de Transferências (depósito, saque, transferência entre contas)
🔹 Integração com Kafka para processamento assíncrono das transferências

### Requisitos:
- Processamento de transferências via fila Kafka (producer/consumer)
- Operações de depósito, saque e transferência entre contas (inclusive entre contas do mesmo usuário)
- Atualização automática dos saldos após processamento
- Boas práticas de arquitetura, SOLID, DTOs, Services, Repository Pattern

---

## 🚀 Tecnologias e Ferramentas
- **Linguagem:** PHP `^8.2` (Usei a versão `8.4`)
- **Framework:** Laravel 12
- **Banco de dados:** MySQL
- **Containers:** Docker + Docker Compose
- **Mensageria:** Apache Kafka (via Docker)

### 📦 Bibliotecas
- [`mateusjunges/laravel-kafka`](https://github.com/mateusjunges/laravel-kafka)
- [`http-message-util`](https://github.com/php-fig/http-message-util)

### 📁 Estrutura do projeto:
- `/app/DTOs:` Data Transfer Objects
- `/app/Repositories:` Repositórios (Repository Pattern)
- `/app/Services:` Serviços de responsabilidade única
- `/app/Http/Requests`: Form Requests para validação
- `/app/Console/Commands`: Consumers Kafka
- `/app/Http/Controllers`/Api: Controllers RESTful
- `/database/migrations:` Migrations do banco

---

### ⚙️ Requisitos:
- Docker e Docker Compose instalados

### 🧭 Passo a Passo

- Clone Repositório
```sh
git clone -b https://github.com/Pierre-Mendes/gerenciamento-transferencias-bancarias.git gerenciamento-transferencias-bancarias
```
```sh
cd gerenciamento-transferencias-bancarias
```

- Suba os containers do projeto
```sh
docker-compose up -d
```

- Crie o Arquivo .env
```sh
cp .env.example .env
```

- Para acessar o container docker execute:
```sh
docker exec -it gerenciamento-transferencias-bancarias_app_1 bash
```

- No terminal instale as dependências do projeto
```sh
composer install
```

- Gere a key do projeto Laravel
```sh
php artisan key:generate
````

- Dentro do `container docker`, execute as migrações para configurar o banco de dados:
```sh
php artisan migrate
```
- Dentro do `container docker`, ajuste as permissões do diretório `storage/` e `bootstrap/cache/`:
```sh
chmod 777 -Rf storage/ bootstrap/cache
```

## 🌐 Acesso
- API: http://localhost:8000/api

- PhpMyAdmin: http://localhost:8080

# 👨‍💻 Autor
Feito por [`Pierre Mendes Salatiel`](https://github.com/Pierre-Mendes)