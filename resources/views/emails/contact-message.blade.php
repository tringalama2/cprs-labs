<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Message from EasyCPRSLabs</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #0ea5e9, #06b6d4);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .content {
            padding: 30px;
        }

        .field {
            margin-bottom: 20px;
        }

        .field-label {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 5px;
            display: block;
        }

        .field-value {
            background: #f3f4f6;
            padding: 12px;
            border-radius: 6px;
            border-left: 4px solid #0ea5e9;
        }

        .message-content {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            white-space: pre-wrap;
            line-height: 1.8;
        }

        .footer {
            background: #f3f4f6;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>New Contact Message</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">From EasyCPRSLabs Contact Form</p>
    </div>

    <div class="content">
        <div class="field">
            <span class="field-label">From:</span>
            <div class="field-value">{{ $contactData['name'] }}</div>
        </div>

        <div class="field">
            <span class="field-label">Email:</span>
            <div class="field-value">{{ $contactData['email'] }}</div>
        </div>

        <div class="field">
            <span class="field-label">Subject:</span>
            <div class="field-value">{{ $contactData['subject'] }}</div>
        </div>

        <div class="field">
            <span class="field-label">Message:</span>
            <div class="message-content">{{ $contactData['message'] }}</div>
        </div>
    </div>

    <div class="footer">
        <p>This message was sent via the EasyCPRSLabs contact form.<br>
           Reply directly to this email to respond to {{ $contactData['name'] }}.</p>
    </div>
</div>
</body>
</html>
