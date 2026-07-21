<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt — HostNinja Cloud</title>
</head>
<body style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f7f9fb; margin: 0; padding: 30px; color: #191c1e;">
    <div style="max-w: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);">
        
        <!-- Header -->
        <div style="background-color: #0d1c32; padding: 32px; text-align: center; color: #ffffff;">
            <div style="font-size: 28px; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 6px;">HostNinja Cloud</div>
            <div style="font-size: 12px; color: #00F5FF; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Official Payment Confirmation</div>
        </div>

        <!-- Body -->
        <div style="padding: 32px; space-y: 20px;">
            <p style="font-size: 15px; font-weight: 700; margin-top: 0; color: #0f172a;">Hello {{ $user->name }},</p>
            <p style="font-size: 13px; color: #475569; line-height: 1.6;">
                Thank you for your payment! We have received your payment and your invoice <strong>#{{ $invoice->invoice_number }}</strong> is now marked as <strong>PAID</strong>.
            </p>

            <!-- Invoice Summary Box -->
            <div style="background-color: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0; padding: 20px; margin: 24px 0;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;">Invoice Number:</td>
                        <td style="padding: 6px 0; font-weight: 700; text-align: right; color: #0f172a;">#{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;">Payment Date:</td>
                        <td style="padding: 6px 0; font-weight: 700; text-align: right; color: #0f172a;">{{ now()->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b;">Description:</td>
                        <td style="padding: 6px 0; font-weight: 700; text-align: right; color: #0f172a;">{{ $invoice->description }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #e2e8f0;">
                        <td style="padding: 12px 0 6px 0; font-size: 14px; font-weight: 800; color: #0f172a;">Total Paid:</td>
                        <td style="padding: 12px 0 6px 0; font-size: 18px; font-weight: 800; text-align: right; color: #10b981;">KES {{ number_format($invoice->total, 2) }}</td>
                    </tr>
                </table>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center; margin: 32px 0 16px 0;">
                <a href="{{ route('dashboard') }}" style="display: inline-block; background-color: #0059bb; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 13px; padding: 14px 28px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,89,187,0.25);">
                    Access Customer Dashboard &rarr;
                </a>
            </div>

            <p style="font-size: 12px; color: #94a3b8; line-height: 1.5; margin-bottom: 0;">
                If you have any questions regarding this invoice, please log in to your dashboard to submit a support ticket.
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f1f5f9; padding: 20px 32px; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0;">
            &copy; {{ date('Y') }} HostNinja Cloud Infrastructure. All rights reserved.<br>
            24/7 Support & High-Performance Cloud Web Hosting.
        </div>
    </div>
</body>
</html>
