<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Payment Reminder — HostNinja Cloud</title>
</head>
<body style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f7f9fb; margin: 0; padding: 30px; color: #191c1e;">
    <div style="max-w: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);">
        
        <!-- Header -->
        <div style="background-color: #0d1c32; padding: 32px; text-align: center; color: #ffffff;">
            <div style="font-size: 28px; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 6px;">HostNinja Cloud</div>
            <div style="font-size: 12px; color: #f59e0b; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Upcoming Payment Reminder</div>
        </div>

        <!-- Body -->
        <div style="padding: 32px;">
            <p style="font-size: 15px; font-weight: 700; margin-top: 0; color: #0f172a;">Hello {{ $user->name }},</p>
            <p style="font-size: 13px; color: #475569; line-height: 1.6;">
                This is a friendly reminder that invoice <strong>#{{ $invoice->invoice_number }}</strong> is due for payment on <strong>{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</strong>.
            </p>

            <!-- Invoice Summary Box -->
            <div style="background-color: #fffbe6; border-radius: 16px; border: 1px solid #fef08a; padding: 20px; margin: 24px 0;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <tr>
                        <td style="padding: 6px 0; color: #854d0e;">Invoice Number:</td>
                        <td style="padding: 6px 0; font-weight: 700; text-align: right; color: #0f172a;">#{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #854d0e;">Description:</td>
                        <td style="padding: 6px 0; font-weight: 700; text-align: right; color: #0f172a;">{{ $invoice->description }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #854d0e;">Due Date:</td>
                        <td style="padding: 6px 0; font-weight: 700; text-align: right; color: #dc2626;">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #fef08a;">
                        <td style="padding: 12px 0 6px 0; font-size: 14px; font-weight: 800; color: #0f172a;">Amount Due:</td>
                        <td style="padding: 12px 0 6px 0; font-size: 18px; font-weight: 800; text-align: right; color: #0059bb;">KES {{ number_format($invoice->total, 2) }}</td>
                    </tr>
                </table>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center; margin: 32px 0 16px 0;">
                <a href="{{ route('dashboard') }}" style="display: inline-block; background-color: #0059bb; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 13px; padding: 14px 28px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,89,187,0.25);">
                    Pay Invoice via M-Pesa / Card &rarr;
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
