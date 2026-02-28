-- Database for SÓ AR BH Quote System

-- Usuarios Table
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nivel ENUM('admin') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Clientes Table
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20),
    cpf_cnpj VARCHAR(20),
    endereco VARCHAR(255),
    numero VARCHAR(20),
    complemento VARCHAR(100),
    bairro VARCHAR(100),
    cep VARCHAR(10),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Orcamentos Table
CREATE TABLE IF NOT EXISTS orcamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    numero VARCHAR(20) UNIQUE NOT NULL,
    data_emissao DATE NOT NULL,
    validade_dias INT DEFAULT 7,
    valor_total DECIMAL(10,2) DEFAULT 0.00,
    desconto DECIMAL(10,2) DEFAULT 0.00,
    valor_final DECIMAL(10,2) DEFAULT 0.00,
    forma_pagamento VARCHAR(255),
    observacoes TEXT,
    status ENUM(
        'criado',
        'enviado',
        'visualizado',
        'aprovado',
        'contrato_enviado',
        'assinado',
        'cancelado'
    ) DEFAULT 'criado',
    token_publico VARCHAR(255) UNIQUE,
    assinafy_doc_id VARCHAR(100),
    link_assinatura TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Itens Orcamento Table
CREATE TABLE IF NOT EXISTS itens_orcamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orcamento_id INT NOT NULL,
    categoria VARCHAR(100),
    descricao VARCHAR(255),
    quantidade INT DEFAULT 1,
    valor_unitario DECIMAL(10,2) DEFAULT 0.00,
    valor_total DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (orcamento_id) REFERENCES orcamentos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Logs Table
CREATE TABLE IF NOT EXISTS logs_orcamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orcamento_id INT,
    acao VARCHAR(100),
    ip VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (orcamento_id) REFERENCES orcamentos(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Inserir Admin (senha: admin123)
INSERT INTO usuarios (id, nome, email, senha, nivel) 
VALUES (1, 'Administrador', 'admin@soarbh.com.br', '$2y$10$7hXKKcX45LpPjrZdD27KmOt1qhR5jbT2I9qG037CrG6caskMslF/W', 'admin')
ON DUPLICATE KEY UPDATE id=id;

-- Templates Table
CREATE TABLE IF NOT EXISTS templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL,
    conteudo TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Initial Template
INSERT INTO templates (nome, conteudo) VALUES ('contrato_padrao', '
<style>
    body { font-family: Arial, sans-serif; line-height: 1.6; color: #1e293b; }
    .header { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 20px; }
    .content { padding: 40px; }
    .footer { text-align: center; font-size: 12px; color: #64748b; margin-top: 50px; }
    .section { margin-bottom: 25px; }
</style>
<div class="header">
    <h1>CONTRATO DE PRESTAÇÃO DE SERVIÇOS</h1>
    <p>SÓ AR BH - Climatização</p>
</div>
<div class="content">
    <div class="section">
        <p><strong>CONTRATADA:</strong> SÓ AR BH - Climatização, com sede em Belo Horizonte/MG.</p>
        <p><strong>CONTRATANTE:</strong> {{cliente_nome}}, CPF/CNPJ: {{cliente_cpf_cnpj}}, residente em {{cliente_endereco}}.</p>
    </div>
    <div class="section">
        <h3>1. OBJETO</h3>
        <p>O presente contrato tem por objeto a prestação dos seguintes serviços de climatização:</p>
        <p>{{descricao_servicos}}</p>
    </div>
    <div class="section">
        <h3>2. VALOR E PAGAMENTO</h3>
        <p>O valor total dos serviços é de <strong>R$ {{valor_total}}</strong>.</p>
        <p>Forma de pagamento acordada: {{forma_pagamento}}.</p>
    </div>
    <div class="section">
        <h3>3. PRAZO E GARANTIA</h3>
        <p>Os serviços serão realizados conforme agendamento prévio. A garantia dos serviços é de 90 dias a contar da data de conclusão.</p>
    </div>
    <p style="margin-top: 40px;">Belo Horizonte, {{data_atual}}.</p>
</div>
<div class="footer">
    Este documento é gerado automaticamente e requer assinatura digital para validade jurídica.
</div>
') ON DUPLICATE KEY UPDATE id=id;
-- Settings Table
CREATE TABLE IF NOT EXISTS configuracoes_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT,
    descricao VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default SMTP Settings
INSERT INTO configuracoes_sistema (chave, valor, descricao) VALUES 
('mail_host', 'mail.soarcondicionadobh.com.br', 'Servidor SMTP'),
('mail_port', '465', 'Porta SMTP'),
('mail_user', 'noreply@soarcondicionadobh.com.br', 'Usuário/E-mail de envio'),
('mail_pass', 'OTGX(kj_IL,Ai#R)', 'Senha do e-mail'),
('mail_from_name', 'SÓ AR BH - Climatização', 'Nome exibido no remetente'),
('mail_secure', 'ssl', 'Segurança (tls/ssl)'),
('assinafy_api_key', 'IclOq0miwtZ13F3Up8tzVQaZMVotCrj0lxIEt8Nq9ml2Q3FYnTSCxXgLV4GQd4uy', 'Chave de API Assinafy'),
('assinafy_base_url', 'https://api.assinafy.com.br/v1/', 'URL Base API Assinafy')
ON DUPLICATE KEY UPDATE valor=VALUES(valor);
