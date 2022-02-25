
<img src="https://www.planium.io/wordpress/wp-content/uploads/2018/11/logo-Planium-06.svg" width="250" height="100">


Nome: Caio Eduardo Furtado Leite Lanceta Ramos.

Código para realização do teste: https://github.com/Luziarte-Planium/planium-test.

API que permite a inserção de dados dos beneficiários (pessoas participantes/pagantes de um plano de saúde), validando a entrada de dados, caso aconteça um erro durante o preenchimento do formulário, é demonstrado ao usuário o erro cometido, caso não ocorra nenhum erro, o usuário recebe suas informações preenchidas, com o preço de cada beneficiário e o preço total, confirmando os dados o usuário é encaminhado novamente para a página inicial como uma mensagem indicando o sucesso do processo. Quando os dados são confirmados o sistema recebe informações dos arquivos "plans.jon" e "prices.json" para calcular (a partir do plano, idade e quantidade de beneficiários) e enviar as informações socilitadas pelo teste para os arquivos "beneficiarios.json" e "proposta.json".

**Ferramentas e frameworks utilizados:**
- HTML
- CSS
- JavaScript
- PHP
- Laravel
- Bootstrap

**Observações:**
- Os arquivos JSON estão armazenados na pasta: <a href="https://github.com/CaioLr/planium-test/tree/main/planium-api/storage/app/json">planium-test/planium-api/storage/app/json</a>
- Rotas utilizadas se encontram no arquivo: <a href="https://github.com/CaioLr/planium-test/blob/main/planium-api/routes/web.php">planium-test/planium-api/routes/web.php</a>
- O controller se encontra no arquivo: <a href="https://github.com/CaioLr/planium-test/blob/main/planium-api/app/Http/Controllers/PlanoSaudeController.php">planium-test/planium-api/app/Http/Controllers/PlanoSaudeController.php</a>
- Views se encontram na pasta: <a href="https://github.com/CaioLr/planium-test/tree/main/planium-api/resources/views">planium-test/planium-api/resources/views/</a>
- Assets utilizados (arquivos CSS e JavaScript) se encontram na pasta: <a href="https://github.com/CaioLr/planium-test/tree/main/planium-api/public/assets">planium-test/planium-api/public/assets/</a>

## Instalação

### Clone o repositório
    $ git clone https://github.com/CaioLr/planium-test.git
### Acesse a pasta do projeto (planium-api)
    $ cd planium-api
### Instale as dependências
    $ composer install
### Copie o arquivo .env-example para um arquivo .env
    $ cp .env.example .env
### Gere uma chave para a aplicação
    $ php artisan key:generate
### Aplicação instalada, para iniciar o servidor pode utilizar:
    $ php artisan serve
   




