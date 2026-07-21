<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>cPanel & Service Login Credentials — HostNinja Cloud</title>
</head>
<body style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f7f9fb; margin: 0; padding: 30px; color: #191c1e;">
    <div style="max-w: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);">
        
        <!-- Header -->
        <div style="background-color: #0d1c32; padding: 32px; text-align: center; color: #ffffff;">
            <div style="font-size: 28px; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 6px;">HostNinja Cloud</div>
            <div style="font-size: 12px; color: #00F5FF; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">cPanel & Service Activation Notice</div>
        </div>

        <!-- Body -->
        <div style="padding: 32px;">
            <p style="font-size: 15px; font-weight: 700; margin-top: 0; color: #0f172a;">Hello {{ $user->name }},</p>
            <p style="font-size: 13px; color: #475569; line-height: 1.6;">
                Great news! Your Web Hosting package for <strong>{{ $service->domain_name }}</strong> has been automatically provisioned and activated on our NVMe SSD Cloud infrastructure.
            </p>

            <!-- cPanel Login Credentials Box -->
            <div style="background-color: #0f172a; color: #ffffff; border-radius: 16px; padding: 24px; margin: 24px 0;">
                <div style="font-size: 14px; font-weight: 800; color: #00F5FF; border-bottom: 1px solid #334155; padding-bottom: 10px; margin-bottom: 16px;">
                    🔑 Your cPanel Access Credentials
                </div>

                <table style="width: 100%; border-collapse: collapse; font-size: 12px; font-family: monospace;">
                    <tr>
                        <td style="padding: 6px 0; color: #94a3b8;">Primary Domain:</td>
                        <td style="padding: 6px 0; font-weight: 700; text-align: right; color: #ffffff;">{{ $service->domain_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #94a3b8;">cPanel Username:</td>
                        <td style="padding: 6px 0; font-weight: 700; text-align: right; color: #38bdf8;">{{ $service->username }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #94a3b8;">Temporary Password:</td>
                        <td style="padding: 6px 0; font-weight: 700; text-align: right; color: #f59e0b;">{{ $tempPassword }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #94a3b8;">cPanel Direct URL:</td>
                        <td style="padding: 6px 0; font-weight: 700; text-align: right; color: #38bdf8;">https://{{ $service->domain_name }}:2083</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #94a3b8;">Server IP Address:</td>
                        <td style="padding: 6px 0; font-weight: 700; text-align: right; color: #ffffff;">197.248.60.102</td>
                    </tr>
                </table>

                <div style="font-size: 11px; color: #94a3b8; border-top: 1px solid #334155; padding-top: 12px; margin-top: 16px;">
                    <strong>Default Nameservers:</strong><br>
                    ns1.hostninja.cloud (197.248.60.102)<br>
                    ns2.hostninja.cloud (197.248.60.103)
                </div>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center; margin: 32px 0 16px 0;">
                <a href="{{ route('dashboard') }}" style="display: inline-block; background-color: #10b981; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 13px; padding: 14px 28px; border-radius: 12px; box-shadow: 0 4px 12px rgba(16,185,129,0.25);">
                    Log In to cPanel via Portal &rarr;
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f1f5f9; padding: 20px 32px; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0;">
            &copy; {{ date('Y') }} HostNinja Cloud Infrastructure. All rights reserved.
        </div>
    </div>
</body>
</html>
