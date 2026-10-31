# Questionnaire service

Estrutura de Clean Architecture e DDD preparada para o contexto de questionários.
O serviço ainda não possui entidades, casos de uso ou endpoints de negócio.

    src/
      Domain/Questionnaire/
        Repository/
        ValueObject/
        Exception/
      Application/Questionnaire/
        UseCase/
        DTO/
        Port/
      Infrastructure/
        Persistence/Doctrine/
          Entity/
          Repository/
          Mapping/
          Migrations/
          Fixtures/
        Messaging/
      Presentation/
        Http/Controller/
        Console/Command/
      Kernel.php
    tests/
      Unit/Domain/
      Unit/Application/
      Integration/Infrastructure/
      Functional/Presentation/

## Responsabilidades

- Domain: agregados, entidades, invariantes, objetos de valor e contratos de
  repositório. Classes PHP independentes de Symfony e Doctrine.
- Application: casos de uso, DTOs e portas para integrações externas.
  Depende do domínio e recebe interfaces por injeção.
- Infrastructure: implementa os contratos usando Doctrine e mensageria.
  Entidades Doctrine representam persistência; adaptadores traduzem para o domínio.
- Presentation: controllers e comandos Symfony. Traduzem entradas, chamam casos
  de uso e apresentam resultados; não contêm regras de negócio.

Os contratos de repositório pertencem ao domínio; portas de integração pertencem
à aplicação. Domain e Application não importam Infrastructure ou Presentation.

## Configuração

O autoload App\\ continua apontando para src/. Os caminhos de rotas, entidades
Doctrine e migrations foram atualizados para as novas pastas. Domain, DTOs,
entidades de persistência e migrations não são registrados como serviços.
Controllers recebem a tag controller.service_arguments; comandos e fixtures
usam autoconfiguração. Ao implementar uma porta, declare seu alias em
config/services.yaml.

A fixture vazia foi movida para Infrastructure/Persistence/Doctrine/Fixtures.
Nenhuma fixture ou migration foi executada e nenhuma tabela foi criada.

## Validação

Com PHP e as dependências instaladas na versão exigida pelo composer.lock:

    php bin/console lint:yaml config
    php bin/console lint:container
    php bin/console debug:router
    php bin/console doctrine:mapping:info

O serviço ainda não está definido no docker-compose.yml da raiz.
