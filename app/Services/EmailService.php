<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $config;

    public function __construct()
    {
        $configModel = new \App\Models\Configuracao();
        $config = $configModel->all();

        $this->config = [
            'host' => $config['mail_host'] ?? '',
            'port' => $config['mail_port'] ?? 587,
            'user' => $config['mail_user'] ?? '',
            'pass' => $config['mail_pass'] ?? '',
            'from_name' => $config['mail_from_name'] ?: ($config['company_name'] ?? 'SÓ AR BH - Climatização'),
            'secure' => $config['mail_secure'] ?? 'tls',
            'from_email' => $config['mail_from_email'] ?: ($config['mail_user'] ?? ''),
            'company_name' => $config['company_name'] ?? 'SÓ AR BH',
            'company_logo' => !empty($config['company_logo']) ? $config['company_logo'] : null
        ];
    }

    private function getEmailHeader()
    {
        $name = htmlspecialchars($this->config['company_name']);
        return "
            <div style='background: #0f172a; padding: 32px; text-align: center;'>
                <h1 style='color: white; margin: 0;'>{$name}</h1>
            </div>
        ";
    }

    private function getEmailFooter()
    {
        $name = htmlspecialchars($this->config['company_name']);
        return "
            <div style='background: #f8fafc; padding: 16px; text-align: center; font-size: 12px; color: #94a3b8;'>
                &copy; " . date('Y') . " {$name} - Todos os direitos reservados.
            </div>
        ";
    }

    public function sendQuote($toEmail, $toName, $quoteNumber, $publicLink)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $this->config['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->config['user'];
            $mail->Password   = $this->config['pass'];
            $mail->SMTPSecure = ($this->config['secure'] === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->config['port'];
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = "Seu Orçamento {$this->config['company_name']} [{$quoteNumber}]";
            
            $body = "
                <div style='font-family: sans-serif; color: #334155; max-width: 600px; margin: auto; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;'>
                    " . $this->getEmailHeader() . "
                    <div style='padding: 32px;'>
                        <h2 style='color: #1e293b; margin-top: 0;'>Olá, {$toName}!</h2>
                        <p>Recebemos sua solicitação e preparamos um orçamento detalhado para você.</p>
                        <p>Número: <strong>{$quoteNumber}</strong></p>
                        <div style='margin-top: 32px; text-align: center;'>
                            <a href='{$publicLink}' style='background: #2563eb; color: white; padding: 16px 32px; text-decoration: none; border-radius: 8px; font-weight: bold;'>Visualizar Orçamento</a>
                        </div>
                        <p style='margin-top: 32px; font-size: 14px; color: #64748b;'>Este link é seguro e exclusivo para você. Caso tenha dúvidas, responda este e-mail.</p>
                    </div>
                    " . $this->getEmailFooter() . "
                </div>
            ";

            $mail->Body = $body;

            return $mail->send();
        } catch (Exception $e) {
            error_log("Email sending failed: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function sendContract($toEmail, $toName, $quoteNumber, $signLink)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $this->config['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->config['user'];
            $mail->Password   = $this->config['pass'];
            $mail->SMTPSecure = ($this->config['secure'] === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->config['port'];
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = "Assinatura de Contrato - Orçamento [{$quoteNumber}] - {$this->config['company_name']}";
            
            $body = "
                <div style='font-family: sans-serif; color: #334155; max-width: 600px; margin: auto; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;'>
                    " . $this->getEmailHeader() . "
                    <div style='padding: 32px;'>
                        <h2 style='color: #1e293b; margin-top: 0;'>Olá, {$toName}!</h2>
                        <p>Seu orçamento foi aprovado! Agora, precisamos da sua assinatura digital no contrato para formalizarmos o serviço.</p>
                        <p>Orçamento: <strong>{$quoteNumber}</strong></p>
                        <div style='margin-top: 32px; text-align: center;'>
                            <a href='{$signLink}' style='background: #10b981; color: white; padding: 16px 32px; text-decoration: none; border-radius: 8px; font-weight: bold;'>Assinar Contrato Agora</a>
                        </div>
                        <p style='margin-top: 32px; font-size: 14px; color: #64748b;'>Este link redirecionará você para o nosso Portal do Cliente. A assinatura é realizada de forma eletrônica, rápida e segura.</p>
                    </div>
                    " . $this->getEmailFooter() . "
                </div>
            ";

            $mail->Body = $body;

            return $mail->send();
        } catch (Exception $e) {
            error_log("Contract email sending failed: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function sendSignedContractNotification($toEmail, $toName, $quoteNumber, $pdfPath)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $this->config['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->config['user'];
            $mail->Password   = $this->config['pass'];
            $mail->SMTPSecure = ($this->config['secure'] === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->config['port'];
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($toEmail, $toName);
            
            // CC the company (using from_email)
            $mail->addCC($this->config['from_email'], $this->config['from_name']);

            if (file_exists($pdfPath)) {
                $mail->addAttachment($pdfPath);
            }

            $mail->isHTML(true);
            $mail->Subject = "Contrato Assinado - Orçamento [{$quoteNumber}] - {$this->config['company_name']}";
            
            $body = "
                <div style='font-family: sans-serif; color: #334155; max-width: 600px; margin: auto; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;'>
                    <div style='background: #10b981; padding: 32px; text-align: center;'>
                        <h1 style='color: white; margin: 0;'>{$this->config['company_name']}</h1>
                    </div>
                    <div style='padding: 32px;'>
                        <h2 style='color: #1e293b; margin-top: 0;'>Tudo pronto, {$toName}!</h2>
                        <p>Seu orçamento foi assinado com sucesso e o contrato já está formalizado.</p>
                        <p>Orçamento: <strong>{$quoteNumber}</strong></p>
                        <p>Anexamos uma cópia do contrato assinado (PDF) contendo o carimbo de data/hora e IP para sua segurança.</p>
                        <p style='margin-top: 32px; font-size: 14px; color: #64748b;'>Nossa equipe entrará em contato em breve para alinhar os próximos passos da execução do serviço.</p>
                    </div>
                    " . $this->getEmailFooter() . "
                </div>
            ";

            $mail->Body = $body;

            return $mail->send();
        } catch (Exception $e) {
            error_log("Signed contract email failed: {$mail->ErrorInfo}");
            return false;
        }
    }
}
