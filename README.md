# Projeto SÓ AR BH – Sistema de Orçamentos

Sistema web para automação de orçamentos de climatização com assinatura digital integrada.

## 🛠️ Tecnologias
- PHP 8.2+
- MySQL 8
- TailwindCSS
- Guzzle (API Integration)
- DomPDF (PDF Generation)
- PHPMailer (Email)

## 📁 Estrutura do Projeto
- `/app`: Controladores, Modelos e Serviços.
- `/config`: Configurações de Banco e API.
- `/public`: Pasta pública (Index, .htaccess).
- `/storage`: Contratos gerados em PDF.
- `/views`: Templates da interface.

## ⚙️ Configuração
1. Importe `config/database.sql` no seu banco de dados.
2. Edite `config/config.php` com suas credenciais.
3. Configure o servidor para apontar para `public/`.
4. Rode `composer install` (se estiver em um novo ambiente).

## 🎯 Fluxo
Admin cria Orçamento -> Cliente recebe E-mail -> Cliente visualiza link -> Cliente Aprova -> Contrato é gerado e enviado para Assinafy -> Cliente assina digitalmente -> Status atualiza no dashboard.

---
Desenvolvido por Frederico Moura.
