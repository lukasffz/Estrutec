# Estrutec

Projeto Integrador desenvolvido para o Curso Técnico em Desenvolvimento de Sistemas do SENAI.

Tema: Grupo 2 – Estruturas e Fundação  
Lema: "Estrutec - Engenharia começa na base."

Este sistema é uma plataforma web (e-commerce e painel administrativo) projetada para a venda e o controle de estoque de materiais de construção pesados, focando nas etapas iniciais de uma obra (fundações e estruturas).

---

## Integrantes do Projeto

* Arthur Floriano Vieira
* Luiz Fillipe Santana Dos Reis
* Lukas Ferreira Faza
* Samuel Henrique Godinho Da Silva
* Ano: 2026 - São Paulo, SP.

---

## Sobre o Sistema

O sistema da Estrutec foi desenvolvido especificamente para lidar com materiais brutos de construção civil, deixando de lado itens de acabamento ou decoração. O foco do banco de dados e do controle de depósito está no gerenciamento de grandes volumes.

O catálogo é composto por:
* Cimento e agregados (areia e brita).
* Vergalhões de aço para armação.
* Blocos estruturais e peças pré-moldadas.

A interface foi desenvolvida em modo escuro (Dark Mode), utilizando tons de azul escuro e cinza, buscando uma identidade visual limpa e profissional para o setor técnico.

### Níveis de Acesso e Permissões

O controle de rotas e páginas é validado por meio de sessões de login no PHP, dividindo-se em:

1. Visitantes: Permite apenas visualizar a página institucional e a listagem de produtos. O carrinho e a finalização de compras ficam bloqueados.
2. Clientes: Usuários cadastrados que podem adicionar materiais ao carrinho, definir o endereço de entrega da obra, fechar pedidos e acompanhar o histórico de compras.
3. Funcionários: Acessam o painel administrativo para registrar a entrada de materiais fornecidos e atualizar o status de entrega dos pedidos (Pendente, Em separação, Concluído).
4. Administrador: Possui controle total sobre o sistema. É responsável pelo cadastro e gerenciamento de funcionários, controle de clientes e visualização do dashboard principal, que exibe relatórios e gráficos de faturamento e produtos mais vendidos em tempo real.

---

## Regras do Banco de Dados e Back-end

Para garantir a consistência das operações comerciais e do estoque físico, foram aplicadas as seguintes regras de negócio no código e no MySQL:

* Impedimento de Estoque Negativo: A coluna de quantidade na tabela de produtos utiliza o atributo UNSIGNED. O sistema barra qualquer tentativa de compra caso o volume solicitado seja maior do que o disponível no depósito.
* Baixa Automatizada: No momento em que o cliente finaliza o pedido, o back-end executa uma instrução UPDATE que desconta as quantidades exatas compradas diretamente na tabela de produtos.
* Histórico de Preços: O valor unitário do produto é gravado diretamente na tabela de itens do pedido no ato da compra. Isso impede que reajustes futuros nos preços do catálogo alterem o valor histórico dos pedidos já fechados.

---

## Tecnologias Utilizadas

* Front-end: HTML5 e CSS3 nativo (Estilização estruturada em Dark Mode).
* Back-end: PHP (Gerenciamento de sessões, validações de segurança e lógica do carrinho).
* Banco de Dados: MySQL (Estrutura relacional com chaves estrangeiras e integridade referencial).
