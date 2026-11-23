# Job Hunter

Agregador pessoal de vagas de emprego. Busca vagas em várias APIs (Adzuna, Jooble e JSearch) a partir de palavras-chave que você define, mostra tudo em um painel único e permite registrar as vagas em que você se candidatou.

Construído com **Laravel 12**, **Livewire 4** e o template **AdminLTE 4** (Bootstrap 5).

<!-- Adicione aqui um print da tela principal: ![Tela principal](docs/screenshot.png) -->

## Funcionalidades

- **Busca multi-fonte**: consulta Adzuna e Jooble em paralelo na mesma tela (JSearch em integração, veja o [Roadmap](#roadmap)).
- **Parâmetros de busca configuráveis**: cadastre palavras-chave (ex.: `Laravel`, `PHP`, `Developer`) com operador lógico `E` / `OU`, direto pela interface.
- **Filtro por país**: Brasil, Canadá, Grã-Bretanha e Irlanda.
- **Paginação por fonte**: 10 vagas por página, independente em cada painel.
- **Cache de 3 horas** nas respostas das APIs, para economizar a cota dos serviços.
- **Candidatura em um clique**: o botão *Candidatar* abre a vaga no site de origem e a registra no banco local.
- **Lista de vagas inscritas** com título, empresa, localização e link.

## Stack

| Camada | Tecnologia |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12 |
| Interface reativa | Livewire 4 (+ Alpine.js embutido) |
| Template | AdminLTE 4 / Bootstrap 5 (arquivos em `public/adminlte`, demais assets via CDN jsDelivr) |
| Banco de dados | SQLite por padrão (qualquer banco suportado pelo Laravel funciona) |
| Cache / sessões | Driver `database` |
| Fontes de vagas | [Adzuna](https://developer.adzuna.com), [Jooble](https://jooble.org/api/about), [JSearch (OpenWeb Ninja)](https://www.openwebninja.com) |

## Pré-requisitos

- PHP **8.2 ou superior** com as extensões `pdo_sqlite` (ou a do seu banco), `mbstring`, `openssl`, `curl`, `xml`, `ctype` e `fileinfo`
- [Composer](https://getcomposer.org) 2
- Chaves de API das fontes que você quiser usar (veja abaixo)
- Conexão com a internet no navegador: o layout carrega CSS/JS do jsDelivr

> Node.js **não é necessário** para rodar a aplicação: as views usam o AdminLTE pronto e não carregam o bundle do Vite.

## Instalação

```bash
# 1. Clonar
git clone https://github.com/Horus2779/Job-Hunt.git
cd Job-Hunt

# 2. Dependências PHP
composer install

# 3. Variáveis de ambiente
cp .env.example .env
php artisan key:generate

# 4. Banco SQLite
touch database/database.sqlite      # Windows (PowerShell): New-Item database/database.sqlite

# 5. Tabelas
php artisan migrate

# 6. (Opcional) dados de exemplo
php artisan db:seed

# 7. Subir o servidor
php artisan serve
```

Acesse **http://localhost:8000**.

## Configuração

Edite o `.env` e preencha as chaves das APIs:

```env
ADZUNA_APP_ID=
ADZUNA_APP_KEY=
JOOBLE_APP_KEY=
JSEARCH_APP_KEY=
```

| Variável | Onde obter |
| --- | --- |
| `ADZUNA_APP_ID`, `ADZUNA_APP_KEY` | Cadastro gratuito em <https://developer.adzuna.com> |
| `JOOBLE_APP_KEY` | Solicitação de chave em <https://jooble.org/api/about> |
| `JSEARCH_APP_KEY` | Conta em <https://www.openwebninja.com> |

Outros pontos do `.env`:

- `DB_CONNECTION=sqlite` é o padrão. Para MySQL/PostgreSQL, ajuste `DB_*` e rode `php artisan migrate`.
- `CACHE_STORE=database` e `SESSION_DRIVER=database` usam as tabelas criadas pelas migrations.
- `QUEUE_CONNECTION`: o projeto ainda não usa filas. Use `sync` se for rodar `composer run dev` (veja [Problemas comuns](#problemas-comuns)).

## Como usar

1. Abra **Vagas → Parâmetros** e cadastre as palavras-chave da sua busca, escolhendo `E` ou `OU` para cada uma.
2. Volte à **Home**, escolha o país e navegue pelas vagas de cada fonte.
3. Clique em **Ver detalhes** para ler a descrição, ou em **Candidatar** para abrir a vaga e registrá-la.
4. Acompanhe o que já enviou em **Vagas → Inscritas**.

Sem nenhum parâmetro cadastrado, nenhuma busca é feita e os painéis aparecem vazios.

## Arquitetura

```
app/
├── Http/Controllers/     # HomeController, OpenJobsController (páginas Blade)
├── Livewire/
│   ├── Home.php          # listagem, paginação por fonte e ação "Candidatar"
│   └── JobParameter.php  # CRUD dos parâmetros de busca
├── Models/               # ParameterJobs, AppliedJobs, ApiSources, AppliedStatus, LocationJobs
└── Services/
    ├── JobsService.php   # agrega as fontes
    ├── AdzumaService.php # Adzuna
    ├── JoobleService.php # Jooble
    └── JSearchService.php# JSearch (em andamento)
resources/views/
├── layouts/              # AdminLTE: admin, topbar, sidebar, css, scripts
├── livewire/             # home, job-parameter
└── components/job-card.blade.php
```

**Fluxo de uma busca:** `Home` (Livewire) → `JobsService::getJobs($country)` → cada `*Service` monta a query a partir de `parameter_jobs`, chama a API, normaliza o resultado para `title / company / location / url / description / source` e guarda em cache por 3 horas (`jobs_{fonte}_{país}`).

Para forçar uma nova busca antes do cache expirar:

```bash
php artisan cache:clear
```

### Banco de dados

| Tabela | Função |
| --- | --- |
| `parameter_jobs` | palavras-chave de busca e operador lógico (`AND` / `OR`) |
| `applied_jobs` | vagas registradas (título, empresa, local, URL, fonte, status) |
| `api_sources` | fontes de vagas |
| `applied_status` | status possíveis de uma candidatura |
| `location_jobs` | países de busca |
| `cache`, `cache_locks`, `sessions` | infraestrutura do Laravel |

## Problemas comuns

| Sintoma | Causa provável | Solução |
| --- | --- | --- |
| `TypeError` ao abrir a Home, citando `appId`, `appKey` ou `api_key` | Chaves de API ausentes no `.env` | Preencha as variáveis da seção [Configuração](#configuração) e rode `php artisan config:clear` |
| `unable to open database file` | Arquivo SQLite não existe | `touch database/database.sqlite` e rode `php artisan migrate` |
| Painéis sempre vazios | Nenhum parâmetro cadastrado, chave inválida ou país não suportado pela fonte | Cadastre parâmetros, confira as chaves e rode `php artisan cache:clear` |
| Tela sem estilo / sem menu | Sem acesso ao jsDelivr | Verifique a conexão ou hospede os assets localmente |
| `composer run dev` encerra logo após iniciar | `queue:listen` não encontra a tabela `jobs` e `--kill-others` derruba os demais processos | Use `QUEUE_CONNECTION=sync` e rode `php artisan serve` separadamente |
| `php artisan pail` falha no Windows | O Pail depende da extensão `pcntl` | Use `php artisan serve` diretamente |

## Testes

```bash
composer test
```

## Roadmap

- [ ] Finalizar a integração com o JSearch (normalização do retorno e painel na Home)
- [ ] Tela de **Vagas Concluídas** e gestão de status das candidaturas
- [ ] Páginas por fonte (menu **Sites**) e tela de configuração das APIs
- [ ] Autenticação (hoje o painel é de usuário único e sem login)
- [ ] Filtros adicionais (modalidade remota, faixa salarial, data de publicação)
- [ ] Testes automatizados para os services (com `Http::fake()`)

## Créditos

- [Laravel](https://laravel.com) e [Livewire](https://livewire.laravel.com)
- Template [AdminLTE](https://adminlte.io) (licença MIT)
- Dados de vagas fornecidos por Adzuna, Jooble e JSearch/OpenWeb Ninja, sujeitos aos respectivos termos de uso

## Autor

**Lucivaldo Junior** — [GitHub](https://github.com/Horus2779) · [LinkedIn](https://www.linkedin.com/in/lucivaldo-junior11)