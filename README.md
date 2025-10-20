# Aurora CMS

Aurora CMS é um projeto em PHP inspirado na arquitetura do Laravel (MVC), desenvolvido para rodar em hospedagens compartilhadas como a Hostinger. Ele oferece um painel administrativo leve para gerenciar posts e categorias, com foco em SEO e desempenho.

## Recursos principais

- Estrutura MVC enxuta em PHP 8.1+
- Banco de dados SQLite pronto para uso (ideal para ambientes sem MySQL)
- Painel administrativo para posts e categorias
- Campos de SEO (meta title e meta description) com geração automática de slug
- Front-end responsivo com componentes otimizados para Core Web Vitals
- Scripts de migração e seed para popular dados de exemplo

## Requisitos

- PHP 8.1 ou superior com extensões `pdo_sqlite` e `mbstring`
- Servidor web Apache ou Nginx apontando para a pasta `public/`

## Instalação

1. Faça o clone do projeto em seu servidor:

   ```bash
   git clone <repositorio> aurora-cms
   cd aurora-cms
   ```

2. Execute as migrações (e opcionalmente os dados de exemplo):

   ```bash
   php scripts/migrate.php --seed
   ```

   O script criará o arquivo `storage/database.sqlite` automaticamente.

3. Aponte o DocumentRoot do seu domínio/subdomínio para a pasta `public/`. Em hospedagens como a Hostinger, basta enviar o conteúdo da pasta `public` para o diretório público e manter o restante do projeto fora do alcance público.

4. Acesse o site:
   - Front-end: `https://seusite.com/`
   - Painel: `https://seusite.com/admin`

   > Obs.: Este projeto não inclui autenticação por padrão. Adicione proteção via `.htpasswd` ou camada adicional conforme necessidade.

## Estrutura de diretórios

```
├── bootstrap/          # Autoloader e bootstrap da aplicação
├── config/             # Configurações centrais (nome, URL, SEO)
├── database/           # Migrações e seeders
├── public/             # DocumentRoot com index.php e assets
├── resources/views/    # Templates Blade-like (Blade simplificado)
├── routes/             # Definição de rotas web e administrativas
├── scripts/            # Scripts de manutenção (migrações)
├── src/                # Código fonte (Controllers, Models e Core)
└── storage/            # Banco SQLite e arquivos gerados
```

## Personalização de SEO

- Título e descrição padrão: edite `config/app.php`.
- Cada post possui campos dedicados para `meta_title` e `meta_description`.
- Slugs são gerados automaticamente a partir do título, com verificação de duplicidade.

## Próximos passos sugeridos

- Implementar autenticação básica (ex.: HTTP Auth) para o painel administrativo.
- Configurar cache de página em servidores com suporte a Redis/Memcached.
- Adicionar sitemap XML e integração com Google Analytics/Search Console.

## Licença

Distribuído sob a licença MIT. Sinta-se à vontade para adaptar às necessidades do seu projeto.
