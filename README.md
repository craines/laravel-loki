# Loki

Esse pacote tem a finalidade de enviar logs da sua aplicação para o serviço do Grafana Loki.

Possui suporte para Laravel 8+ e PHP 8.0+

## Instalação

Para instalar o pacote, basta usar o composer:

```bash
composer require williamtome/loki
```
Não é preciso publicar o pacote nos Providers da sua aplicação Laravel. Isso é feito de forma automática pelo auto discovery, durante a instalação.

## Como Usar

Depois de instalado, vá até o arquivo `config/logging.php` da sua aplicação Laravel, na chave de array `channel` e adicione o código a abaixo:

```php
// config/logging.php

'channel' => [
    // outros channels padrão do Laravel...

    'loki' => [
        'driver' => 'custom',
        'level' => env('LOKI_LOGLEVEL', 'info'),
        'via' => Williamtome\Loki\LokiFactory::class,
        'configApi' => [
            'entrypoint' => env('LOKI_ENTRYPOINT', getenv('LOKI_HOST')),
            'globalLabels' => ['job' => env('APP_NAME', 'ProjectName')]
        ]
    ],
]
```

Também é necessário adicionar as seguintes variáveis de ambiente no seu `.env`:

```
CUSTOMER_NAME
NETWORK
LOKI_ENTRYPOINT
LOKI_HOST
```

**Explicação das variáveis:**

- CUSTOMER_NAME - É o nome da empresa na qual está usando a sua aplicação.
- NETWORK - É o nome da rede do container Docker que a sua aplicação está utilizando. Você consegue visualizar isso no `docker-compose.yml` na parte de `network`.
- LOKI_ENTRYPOINT e LOKI_HOST - É a url do serviço do Loki que está sendo executado no seu container Docker. Exemplo: `http://url-do-servico-loki:3100`.

Alterar o valor da variável `APP_NAME` com o nome da sua aplicação.

Altere o valor da variável `LOG_CHANNEL` para `loki`.

```
LOG_CHANNEL=loki
```

## Licença
Loki é um software open source licenciado sob a Licença MIT.
