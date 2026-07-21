<?php

/**
 * HostNinja Shared Hosting Web Deployment Runner
 * Access this script in your browser: https://your-domain.com/deploy.php
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));

$baseDir = __DIR__ . '/..';
$envFile = $baseDir . '/.env';

// 1. Check vendor directory & unzip if vendor.zip exists
if (!file_exists($baseDir . '/vendor/autoload.php')) {
    $zipFile = $baseDir . '/vendor.zip';
    if (file_exists($zipFile)) {
        $extracted = false;
        if (class_exists('ZipArchive')) {
            $zip = new ZipArchive();
            if ($zip->open($zipFile) === TRUE) {
                $zip->extractTo($baseDir);
                $zip->close();
                $extracted = true;
            }
        }
        if (!$extracted && function_exists('exec')) {
            @exec("cd " . escapeshellarg($baseDir) . " && unzip vendor.zip");
            if (file_exists($baseDir . '/vendor/autoload.php')) {
                $extracted = true;
            }
        }
    }

    if (!file_exists($baseDir . '/vendor/autoload.php')) {
        echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Vendor Dependencies Required</title>";
        echo "<style>body{font-family:sans-serif;background:#090d16;color:#f3f4f6;padding:50px;text-align:center;}.box{max-width:600px;margin:0 auto;background:#111827;border:1px solid #1f2937;padding:30px;border-radius:12px;}h1{color:#ef4444;}code{background:#1f2937;padding:3px 8px;border-radius:4px;color:#a5b4fc;}</style></head><body>";
        echo "<div class='box'><h1>Composer Dependencies Missing</h1>";
        echo "<p>To activate your shared hosting installation without SSH:</p>";
        echo "<ol style='text-align:left;line-height:1.8;'>";
        echo "<li>Upload <code>vendor.zip</code> into your project root folder (<code>/home/zrnixzre/host.saas/</code>) via cPanel File Manager.</li>";
        echo "<li>Refresh this page, and <code>deploy.php</code> will automatically unzip <code>vendor.zip</code> and boot your app!</li>";
        echo "</ol></div></body></html>";
        exit;
    }
}

// 2. Neutralize Composer platform check if present
$platformCheckFile = $baseDir . '/vendor/composer/platform_check.php';
if (file_exists($platformCheckFile)) {
    @file_put_contents($platformCheckFile, "<?php return;\n");
}

// 3. Auto-generate APP_KEY if missing in .env
$keyGeneratedNotice = "";
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (!preg_match('/^APP_KEY=base64:.+/m', $envContent)) {
        $newKey = 'base64:' . base64_encode(random_bytes(32));
        if (preg_match('/^APP_KEY=/m', $envContent)) {
            $envContent = preg_replace('/^APP_KEY=.*/m', 'APP_KEY=' . $newKey, $envContent);
        } else {
            $envContent .= "\nAPP_KEY=" . $newKey . "\n";
        }
        file_put_contents($envFile, $envContent);
        $keyGeneratedNotice = "Auto-generated new APP_KEY: " . $newKey;
    }
}

// 4. Safely load Laravel
$bootstrapError = null;
try {
    require $baseDir . '/vendor/autoload.php';
    $app = require_once $baseDir . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
} catch (\Throwable $e) {
    $bootstrapError = $e->getMessage() . "\n" . $e->getTraceAsString();
}

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

$outputLog = [];
$actionRun = false;

if ($bootstrapError === null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionRun = true;
    $action = $_POST['action'] ?? '';
    $customCmd = trim($_POST['custom_cmd'] ?? '');

    try {
        if ($action === 'full_setup') {
            Artisan::call('migrate', ['--force' => true]);
            $outputLog[] = "<strong>[1/4] Migration:</strong>\n" . Artisan::output();

            Artisan::call('db:seed', ['--class' => 'HostNinjaSeeder', '--force' => true]);
            $outputLog[] = "<strong>[2/4] Database Seeding:</strong>\n" . Artisan::output();

            try {
                Artisan::call('storage:link');
                $outputLog[] = "<strong>[3/4] Storage Link:</strong>\n" . Artisan::output();
            } catch (\Exception $e) {
                $outputLog[] = "<strong>[3/4] Storage Link:</strong> Notice: " . $e->getMessage();
            }

            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            $outputLog[] = "<strong>[4/4] Optimization Caches:</strong>\nConfig, routes, and views cached successfully.";
        } elseif ($action === 'migrate') {
            Artisan::call('migrate', ['--force' => true]);
            $outputLog[] = "<strong>Migration Result:</strong>\n" . Artisan::output();
        } elseif ($action === 'seed') {
            Artisan::call('db:seed', ['--class' => 'HostNinjaSeeder', '--force' => true]);
            $outputLog[] = "<strong>Database Seeding Result:</strong>\n" . Artisan::output();
        } elseif ($action === 'clear_cache') {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            $outputLog[] = "<strong>Clear Cache Result:</strong>\nAll caches cleared successfully.";
        } elseif ($action === 'optimize') {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            $outputLog[] = "<strong>Optimize Caches Result:</strong>\nConfiguration, routes, and views cached successfully.";
        } elseif ($action === 'custom' && !empty($customCmd)) {
            $cmdClean = preg_replace('/^(php\s+artisan\s+)/i', '', $customCmd);
            $parts = explode(' ', $cmdClean);
            $commandName = array_shift($parts);
            $params = [];
            foreach ($parts as $part) {
                if (str_contains($part, '=')) {
                    [$k, $v] = explode('=', $part, 2);
                    $params[$k] = $v;
                } else {
                    $params[] = $part;
                }
            }
            Artisan::call($commandName, $params);
            $outputLog[] = "<strong>Custom Command (<code>artisan {$cmdClean}</code>):</strong>\n" . Artisan::output();
        }
    } catch (\Throwable $e) {
        $outputLog[] = "<span style='color: #ef4444;'><strong>ERROR:</strong> " . htmlspecialchars($e->getMessage()) . "</span>\n<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
}

// Test DB Connection Status
$dbStatus = "Unknown";
$dbClass = "bg-yellow";
if ($bootstrapError === null) {
    try {
        DB::connection()->getPdo();
        $dbStatus = "Connected to database successfully (" . DB::connection()->getDatabaseName() . ")";
        $dbClass = "bg-green";
    } catch (\Throwable $e) {
        $dbStatus = "Database Connection Error: " . $e->getMessage();
        $dbClass = "bg-red";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostNinja Web Deployment Console</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #090d16;
            --card-bg: #111827;
            --border: #1f2937;
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --text: #f3f4f6;
            --text-muted: #9ca3af;
        }
        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }
        .container { max-width: 900px; width: 100%; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 {
            font-size: 28px; font-weight: 700; margin: 0 0 10px 0;
            background: linear-gradient(135deg, #a5b4fc, #6366f1);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .card {
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 12px; padding: 24px; margin-bottom: 24px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
        }
        .status-badge {
            display: inline-block; padding: 6px 12px; border-radius: 6px;
            font-size: 13px; font-weight: 600; margin-bottom: 12px;
        }
        .bg-green { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .bg-red { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
        .bg-yellow { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .btn-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 20px; }
        button, .btn {
            background-color: var(--accent); color: #fff; border: none; padding: 12px 18px;
            border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer;
            transition: all 0.2s ease; text-align: center; text-decoration: none;
        }
        button:hover, .btn:hover { background-color: var(--accent-hover); transform: translateY(-1px); }
        .btn-success { background-color: #059669; }
        .btn-success:hover { background-color: #047857; }
        .btn-secondary { background-color: #374151; }
        .btn-secondary:hover { background-color: #4b5563; }
        .form-group { display: flex; gap: 10px; margin-top: 15px; }
        input[type="text"] {
            flex: 1; background: #0b0f19; border: 1px solid var(--border); color: #fff;
            padding: 12px; border-radius: 8px; font-family: 'JetBrains Mono', monospace; font-size: 14px;
        }
        .terminal {
            background: #050811; border: 1px solid #1e293b; border-radius: 8px; padding: 16px;
            font-family: 'JetBrains Mono', monospace; font-size: 13px; line-height: 1.6;
            color: #38bdf8; white-space: pre-wrap; max-height: 400px; overflow-y: auto;
        }
        .notice { padding: 12px; background: rgba(99, 102, 241, 0.1); border-left: 4px solid var(--accent); margin-bottom: 20px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>HostNinja Web Deployment Console</h1>
            <p style="color: var(--text-muted); font-size: 14px;">Run Laravel Artisan tasks directly from your web browser without SSH terminal access.</p>
        </div>

        <?php if (!empty($keyGeneratedNotice)): ?>
            <div class="notice">✨ <strong>App Key Configured:</strong> <?php echo htmlspecialchars($keyGeneratedNotice); ?></div>
        <?php endif; ?>

        <?php if ($bootstrapError !== null): ?>
            <div class="card" style="border-color: #ef4444;">
                <div class="status-badge bg-red">Laravel Bootstrap Failed</div>
                <p style="color: #f87171; font-weight: 600;">Laravel encountered an error while booting:</p>
                <div class="terminal" style="color: #fca5a5;"><?php echo htmlspecialchars($bootstrapError); ?></div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="status-badge <?php echo $dbClass; ?>">
                    Database: <?php echo htmlspecialchars($dbStatus); ?>
                </div>

                <h3>1-Click Complete Setup</h3>
                <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 16px;">
                    Executes <code>migrate --force</code>, <code>db:seed</code>, <code>storage:link</code>, and caches application configuration/routes.
                </p>
                <form method="POST">
                    <input type="hidden" name="action" value="full_setup">
                    <button type="submit" class="btn-success" style="width: 100%; padding: 14px; font-size: 16px;">🚀 Run Full Setup & Deploy Database</button>
                </form>
            </div>

            <div class="card">
                <h3>Individual Artisan Actions</h3>
                <div class="btn-grid">
                    <form method="POST"><input type="hidden" name="action" value="migrate"><button type="submit" style="width: 100%;">Run Migrations</button></form>
                    <form method="POST"><input type="hidden" name="action" value="seed"><button type="submit" style="width: 100%;">Seed Database</button></form>
                    <form method="POST"><input type="hidden" name="action" value="optimize"><button type="submit" class="btn-secondary" style="width: 100%;">Optimize & Cache</button></form>
                    <form method="POST"><input type="hidden" name="action" value="clear_cache"><button type="submit" class="btn-secondary" style="width: 100%;">Clear All Caches</button></form>
                </div>

                <h4 style="margin-top: 24px; margin-bottom: 8px;">Run Custom Artisan Command</h4>
                <form method="POST">
                    <input type="hidden" name="action" value="custom">
                    <div class="form-group">
                        <input type="text" name="custom_cmd" placeholder="e.g. migrate:status or queue:restart or list" required>
                        <button type="submit">Execute</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($actionRun): ?>
            <div class="card">
                <h3>Execution Output</h3>
                <div class="terminal"><?php foreach ($outputLog as $log) { echo $log . "\n\n"; } ?></div>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 20px;">
            <a href="/" class="btn btn-secondary" style="display: inline-block;">Go to HostNinja Home Page &rarr;</a>
        </div>
    </div>
</body>
</html>
