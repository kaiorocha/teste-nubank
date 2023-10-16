# Projeto de avaliação técnica - Nubank #

### Objetivo ###

O projeto tem como objetivo a implementação de um programa de linha de comando (CLI) que calcula o imposto a ser pago sobre os lucros ou prejuízos de operações no mercado financeiro de ações.

### Estrutura ###

O desenvolvimento foi realizado no PHP 8.1, por ser a linguagem de programação que tenho maior familiaridade.

Foi utilizado o composer para fazer o autoload das classes seguindo a convenção PSR-4, que vai de acordo com o desenvolvimento moderno em PHP.

#### Para a instalação prévia das dependências do projeto, utilize o seguinte comando no terminal, dentro da raíz do projeto: ####

```
composer install
```

### Testes unitários ###

Para os teste foi utilizado o PHPUnit, que é o principal framework de testes unitários em PHP com base na arquitetura xUnit.

Para executar todos os testes, utilizei o seguinte comando no terminal dentro da raíz do projeto:

```
./vendor/bin/phpunit
```

## Processamento ##

O Programa deve receber uma lista json, onde cada linha representa uma operação do mercado financeiro de ações.

Segue abaixo o exemplo do conteúdo da lista json:

```
[{"operation":"buy", "unit-cost":10.00, "quantity": 10000},
{"operation":"sell", "unit-cost":20.00, "quantity": 5000},
{"operation":"buy", "unit-cost":20.00, "quantity": 10000},
{"operation":"sell", "unit-cost":10.00, "quantity": 5000}]
```

Para executar o programa devemos chamar o arquivo cli.php com o php na linha de comando, na raíz do projeto:

```
php cli.php
```

Será solicitação a inclusão do arquivo para o arquivo json que deseja processar:

```
Por favor, informe o caminho do arquivo que deseja processar: /opt/example.json
```

Caso dê tudo certo, após o processamento do arquivo, o programa retornará as taxas no formato apresentado abaixo:

```
[{"tax":0},{"tax":80000},{"tax":0},{"tax":60000}]
```