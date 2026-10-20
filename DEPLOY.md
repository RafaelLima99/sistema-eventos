# Infraestrutura, desenvolvimento e deploy

Este arquivo é o runbook do projeto: descreve como o ambiente é montado e como recriá-lo em outro servidor ou painel.
Tudo o que descreve a infraestrutura está versionado no repositório; **segredos não**.

## Visão geral

| Item | Escolha |
|---|---|
| Aplicação | Laravel, PHP 8.5 (PHP-FPM) |
| Web | Nginx (`nginx-unprivileged`) → PHP-FPM |
| Banco | MySQL 8.4 |
| Cache, sessão e filas | MySQL (driver `database`; sem Redis) |
| Processos | `queue:work` e `schedule:work` (mesma imagem do app) |
| Build | Dockerfile único multi-stage, construído no servidor pelo Coolify (sem registry) |
| Proxy/HTTPS | Fica **fora** do núcleo: painel (Coolify) |

### Arquivos

```
docker/php/Dockerfile        Dockerfile único: base, dev, vendor, web, prod
docker/php/conf.d/           php.ini (app), opcache (prod), xdebug (dev)
docker/php/php-fpm.d/        pool do PHP-FPM (ajustável por variável de ambiente)
docker/php/entrypoint.sh     entrypoint de produção (optimize + migrations opcionais)
docker/nginx/default.conf    Nginx (usado em dev e prod)
compose.yaml                 DEV
compose.prod.yaml            PRODUÇÃO (núcleo portável: sem proxy, sem portas publicadas)
deploy/coolify/README.md     como configurar no Coolify
.env.example                 variáveis de dev
.env.production.example      contrato de variáveis de produção (sem valores reais)
.github/workflows/           ci.yml (Pint + testes)
```

### Stages do Dockerfile

| Stage | Uso |
|---|---|
| `base` | PHP-FPM alpine + extensões (bcmath, exif, gd, intl, opcache, pcntl, pdo_mysql, zip) |
| `dev` | base + Composer + Xdebug (código por bind mount) |
| `vendor` | `composer install --no-dev` + autoload otimizado |
| `web` | Nginx com `public/` (frontend estático: Bootstrap/JS em `public/`) |
| `prod` | imagem final do app (roda como `www-data`; último stage = alvo padrão) |

Versões fixadas por `ARG` no topo do Dockerfile (`PHP_VERSION`, `COMPOSER_VERSION`...). Para atualizar o PHP, altere o `ARG`, rode os testes e faça novo deploy.

## Desenvolvimento

Rode o Docker **dentro do WSL** (projeto em `~/sistema-eventos`), não pelo caminho `\\wsl.localhost`.

```bash
cp .env.example .env            # ajuste DOCKER_UID/DOCKER_GID (`id -u`, `id -g`)
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

| Serviço | URL |
|---|---|
| Aplicação | http://localhost:8000 |
| Mailpit | http://localhost:8025 |
| MySQL | `127.0.0.1:3306` (credenciais do `.env`) |

Comandos comuns:

```bash
docker compose exec app php artisan test --compact
docker compose exec app vendor/bin/pint --dirty
docker compose logs -f queue
```

- Xdebug: `XDEBUG_MODE=debug` no `.env` e `docker compose up -d`; porta 9003.
- Overrides pessoais: `compose.override.yaml` (ignorado pelo git).
- O scheduler não sobe em dev: use `docker compose exec app php artisan schedule:work` quando precisar.

## Produção

### Fluxo

1. Push na `main` → o **CI** (`ci.yml`) valida Pint e testes.
2. O Coolify (Auto Deploy) clona o repositório, constrói as imagens `app` e `web` no servidor e sobe os serviços.
3. Rollback: redeploy de um commit anterior pelo painel.

### Variáveis

Todas estão em `.env.production.example`. Valores reais ficam no painel e/ou no gerenciador de senhas. `APP_KEY`, `DB_PASSWORD` e `DB_ROOT_PASSWORD` são obrigatórios (o compose falha sem eles).

Gerar a chave:

```bash
docker compose -f compose.prod.yaml run --rm --no-deps app php artisan key:generate --show
```

### Coolify

Ver `deploy/coolify/README.md`.

### Migrations

- Manual: `docker compose -f compose.prod.yaml exec app php artisan migrate --force`
- Automático: `RUN_MIGRATIONS=true` (roda no start do serviço `app`; use só com **uma** réplica do `app`).

### Backup

**Pendente de decisão.** Os scripts de backup/restore foram removidos por enquanto: no Coolify os containers pertencem ao projeto gerenciado por ele, e o `docker compose -f compose.prod.yaml exec db ...` não os encontraria. Antes do primeiro dado real, definir como será o backup do MySQL (recurso de banco do Coolify com backup agendado, ou dump via `docker exec` no container) e do volume `storage` (uploads, se `FILESYSTEM_DISK=local`).

### Migrar para outro servidor ou painel

1. Fazer backup do banco e do volume `storage`.
2. No destino, subir `compose.prod.yaml` com as mesmas variáveis (`.env.production.example` lista todas).
3. Restaurar o banco e o `storage`.
4. Trocar o DNS.

Em painel que não aceite Compose: crie um serviço por linha abaixo, todos com a mesma imagem `app` (a `web` é outra imagem), as mesmas variáveis de ambiente e o volume `storage` em `/var/www/html/storage`.

| Serviço | Imagem | Comando |
|---|---|---|
| app | imagem `app` | `php-fpm` (padrão da imagem) |
| web | imagem `web` | padrão da imagem (porta 8080); aponta o FastCGI para o host `app:9000` |
| queue | imagem `app` | `php artisan queue:work --sleep=3 --tries=3 --max-time=3600` |
| scheduler | imagem `app` | `php artisan schedule:work` |

## Decisões e pontos de atenção

- **Build no servidor (Coolify), sem GHCR por enquanto:** simples e sem registry. Consome CPU/RAM da VPS no deploy; se pesar, publicar imagens pelo GitHub Actions e apontar o compose para elas.
- **Sem Laravel Sail e sem build automático (Nixpacks/Railpack):** o ambiente é definido por arquivos do projeto, para ser reproduzível em qualquer lugar.
- **Dockerfile único multi-stage:** dev e prod compartilham a mesma base (PHP e extensões).
- **Nginx separado do PHP-FPM:** padrão mais documentado. `web` e `app` compartilham o volume `storage` (somente leitura no `web`) para servir `public/storage`.
- **Sem Redis:** cache, sessão e fila usam o MySQL (`database`). Se o volume de jobs crescer, suba um Redis e troque `QUEUE_CONNECTION`/`CACHE_STORE`/`SESSION_DRIVER`. O scheduler limpa `failed_jobs` e `job_batches` (ver `routes/console.php`).
- **Sem Node/Vite:** o front usa Bootstrap por CSS/JS em `public/`; não há stage de build de frontend.
- **Configs cacheadas no start** (`artisan optimize` no entrypoint), porque dependem das variáveis de ambiente do servidor.
- **Banco no compose:** conveniente e portável. Em produção séria, considere banco gerenciado/recurso do painel (backup agendado nativo) e remova o serviço `db`.
- **Proxy confiável:** atrás do Coolify o Laravel precisa confiar no proxy para gerar URLs `https`. Falta configurar `$middleware->trustProxies(at: '*')` em `bootstrap/app.php` (alteração de código, ainda não feita).
- **Horizon:** não está instalado. Se for adotado, o serviço `queue` passa a rodar `php artisan horizon`.
