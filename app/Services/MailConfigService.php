<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailConfigService
{
    public static function applyDynamicSmtpConfig(): void
    {
        $host = Setting::getByKey('smtp_host');

        // Only override config if admin has configured an SMTP host
        if (!empty($host)) {
            $port = Setting::getByKey('smtp_port', '587');
            $encryption = Setting::getByKey('smtp_encryption', 'tls');
            $username = Setting::getByKey('smtp_username');
            $password = Setting::getByKey('smtp_password');
            $fromAddress = Setting::getByKey('smtp_from_address', 'notifications@hostninja.cloud');
            $fromName = Setting::getByKey('smtp_from_name', 'HostNinja Cloud Notifications');

            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', (int) $port);
            Config::set('mail.mailers.smtp.encryption', $encryption);
            Config::set('mail.mailers.smtp.username', $username);
            Config::set('mail.mailers.smtp.password', $password);

            Config::set('mail.from.address', $fromAddress);
            Config::set('mail.from.name', $fromName);
        }
    }
}
