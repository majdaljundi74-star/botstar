<?php
/**
 * سكريبت للتحقق من حالة البوت
 * Script to check bot status
 */

require_once "config.php";

echo "===========================================\n";
echo "التحقق من حالة البوت - Checking Bot Status\n";
echo "===========================================\n\n";

// التحقق من التوكن
if (empty($TOKEN) || $TOKEN === "YOUR_BOT_TOKEN_HERE") {
    echo "❌ خطأ: توكن البوت غير محدد في config.php\n";
    echo "❌ Error: Bot token not set in config.php\n";
    exit;
}

// التحقق من معرف المطور
if (empty($sudoID) || $sudoID === "YOUR_DEVELOPER_ID_HERE") {
    echo "⚠️  تحذير: معرف المطور غير محدد في config.php\n";
    echo "⚠️  Warning: Developer ID not set in config.php\n\n";
}

// 1. التحقق من معلومات البوت
echo "1. معلومات البوت / Bot Info:\n";
echo "----------------------------------------\n";
$botInfoUrl = "https://api.telegram.org/bot$TOKEN/getMe";
$botInfo = json_decode(file_get_contents($botInfoUrl), true);

if ($botInfo && $botInfo['ok']) {
    $bot = $botInfo['result'];
    echo "✅ البوت يعمل / Bot is active\n";
    echo "   الاسم / Name: " . $bot['first_name'] . "\n";
    echo "   المعرف / Username: @" . ($bot['username'] ?? 'غير محدد') . "\n";
    echo "   المعرف الفريد / ID: " . $bot['id'] . "\n";
} else {
    echo "❌ خطأ: التوكن غير صحيح أو البوت غير موجود\n";
    echo "❌ Error: Invalid token or bot not found\n";
    exit;
}

echo "\n";

// 2. معلومات Webhook
echo "2. معلومات Webhook / Webhook Info:\n";
echo "----------------------------------------\n";
$webhookUrl = "https://api.telegram.org/bot$TOKEN/getWebhookInfo";
$webhookInfo = json_decode(file_get_contents($webhookUrl), true);

if ($webhookInfo && $webhookInfo['ok']) {
    $info = $webhookInfo['result'];
    if (!empty($info['url'])) {
        echo "✅ Webhook مفعل / Webhook is active\n";
        echo "   الرابط / URL: " . $info['url'] . "\n";
        echo "   الرسائل المعلقة / Pending updates: " . ($info['pending_update_count'] ?? 0) . "\n";
        
        if (isset($info['last_error_date'])) {
            echo "   ⚠️  آخر خطأ / Last error: " . date('Y-m-d H:i:s', $info['last_error_date']) . "\n";
            echo "   رسالة الخطأ / Error: " . ($info['last_error_message'] ?? 'غير متاح') . "\n";
        } else {
            echo "   ✅ لا توجد أخطاء / No errors\n";
        }
    } else {
        echo "⚠️  Webhook غير مفعل / Webhook is not set\n";
        echo "   استخدم set_webhook.php لتعيين Webhook\n";
        echo "   Use set_webhook.php to set Webhook\n";
    }
}

echo "\n";

// 3. التحقق من ملفات مهمة
echo "3. التحقق من الملفات / Files Check:\n";
echo "----------------------------------------\n";
$files = ['config.php', 'index.php', 'util.php'];
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file موجود / exists\n";
    } else {
        echo "❌ $file غير موجود / not found\n";
    }
}

// 4. التحقق من المجلدات
echo "\n4. التحقق من المجلدات / Directories Check:\n";
echo "----------------------------------------\n";
$dirs = ['Lang', 'Logs', 'Plugins'];
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        echo "✅ $dir موجود / exists\n";
        if (!is_writable($dir)) {
            echo "   ⚠️  المجلد غير قابل للكتابة / Directory is not writable\n";
        }
    } else {
        echo "⚠️  $dir غير موجود / not found\n";
    }
}

echo "\n===========================================\n";
echo "✅ اكتمل الفحص / Check completed\n";
echo "===========================================\n";

