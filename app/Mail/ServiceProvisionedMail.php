<?php

namespace App\Mail;

use App\Models\HostingService;
use App\Models\User;
use App\Services\MailConfigService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ServiceProvisionedMail extends Mailable
{
    use Queueable, SerializesModels;

    public HostingService $service;
    public User $user;
    public string $tempPassword;

    public function __construct(HostingService $service, User $user, string $tempPassword = 'password123')
    {
        MailConfigService::applyDynamicSmtpConfig();
        $this->service = $service;
        $this->user = $user;
        $this->tempPassword = $tempPassword;
    }

    public function build()
    {
        return $this->subject("cPanel & Web Hosting Credentials for {$this->service->domain_name}")
                    ->view('emails.service_provisioned');
    }
}
