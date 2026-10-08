# Coolify

O Coolify é só o **executor**: a definição do ambiente vem do repositório (`compose.prod.yaml`).

## Criar a aplicação

1. **New Resource → Docker Compose** (origem: este repositório, branch `main`).
2. **Compose file:** `compose.prod.yaml`.
3. Em **Environment Variables**, cadastre as variáveis de `.env.production.example` (valores reais; `APP_KEY`, `DB_PASSWORD` e `DB_ROOT_PASSWORD` são obrigatórios).
4. Em **Domains**, associe o domínio ao serviço `web` na porta `8080`. O Coolify cuida do HTTPS.

## Build e deploy

O Coolify clona o repositório (deploy key ou app do GitHub), **constrói as imagens no próprio servidor** a partir do `compose.prod.yaml` (há `build:` nos serviços) e sobe os containers. O build consome CPU/RAM da VPS; em servidor de 1 GB ele pode falhar.

Ative o **Auto Deploy**: o Coolify configura o webhook no GitHub e cada push na `main` dispara um novo deploy. Rollback: redeploy de um commit anterior.

Futuro: se o build no servidor pesar, dá para publicar imagens pelo GitHub Actions (GHCR) e apontar o compose para elas.

## Banco

O `compose.prod.yaml` já inclui o MySQL. Alternativa: criar um banco como recurso do Coolify (com backup agendado para S3), remover o serviço `db` do compose e ajustar `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD`.

## Migrations

`RUN_MIGRATIONS=true` nas variáveis (roda no start do serviço `app`) ou o comando pós-deploy `php artisan migrate --force` no serviço `app`.

## Cuidados

- Não use variáveis mágicas do Coolify (`SERVICE_FQDN_*`, `SERVICE_PASSWORD_*`) no compose: o arquivo precisa continuar funcionando fora dele.
- Não publique portas (`ports:`) no `web`: o proxy do Coolify acessa a rede interna.
- Faça backup da configuração do próprio Coolify e dos volumes (`storage`, `dbdata`).
