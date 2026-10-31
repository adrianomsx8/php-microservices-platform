# Clean Architecture e DDD

Cada servi?o ? uma fronteira independente. O contexto de identidade pertence ao
user-service; notifica??es ao email-service. O gateway comp?e transporte HTTP e
n?o precisa de entidades de neg?cio artificiais.

- Domain: regras de neg?cio e contratos de reposit?rio, sem Symfony ou Doctrine.
- Application: casos de uso e portas para servi?os externos, sem framework.
- Infrastructure: adaptadores Doctrine, senha Symfony, RabbitMQ, Mailer e HTTP.
- Controller e Command: adaptadores de entrada Symfony.

As depend?ncias apontam para dentro: Infrastructure -> Application -> Domain.
Os aliases em config/services.yaml conectam as portas aos adaptadores.

No cadastro, RegisterUser cria o usu?rio de dom?nio, solicita a persist?ncia e
publica user.created. A entidade App\Entity\User permanece como registro Doctrine
e identidade de seguran?a para preservar banco, login JWT e perfil. O adaptador
Doctrine traduz o dom?nio para esse registro. N?o h? migra??o de banco nesta mudan?a.

O email-service recebe o mesmo envelope {event, payload} e chama SendWelcomeEmail.
O gateway preserva as rotas, corpos e c?digos HTTP do servi?o de usu?rios.
JSON ou campos inv?lidos no cadastro retornam 400.

A persist?ncia e a publica??o ainda s?o opera??es separadas: se RabbitMQ falhar ap?s
o commit, o usu?rio permanece salvo sem evento publicado. Uma evolu??o para outbox
transacional exige pol?tica de reentrega e consumidor idempotente. Emails duplicados
continuam sujeitos ? constraint existente do banco.

O handler Messenger legado permanece independente do fluxo AMQP atual.
Questionnaire-service n?o est? presente neste checkout.
