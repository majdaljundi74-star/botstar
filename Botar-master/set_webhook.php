<?php
/**
 * سكريبت تعيين Webhook للبوت
 * Script to set Webhook for the bot
 */

require_once "config.php";

// التحقق من وجود التوكن
if (empty($TOKEN) || $TOKEN === "YOUR_BOT_TOKEN_HERE") {
    die("❌ خطأ: يجب تعيين توكن البوت في ملف config.php أولاً!\nError: Please set bot token in config.php first!\n");
}

// رابط ملف index.php على الخادم
// URL of index.php on your server
echo "===========================================\n";
echo "إعداد Webhook للبوت - Setting Bot Webhook\n";
echo "===========================================\n\n";

echo "الرجاء إدخال رابط ملف index.php على خادمك:\n";
echo "Please enter the URL of index.php on your server:\n";
echo "مثال / Example: https://example.com/bot/index.php\n";
echo "أو اضغط Enter لاستخدام القيمة الافتراضية / Or press Enter for default:\n";
$webhookUrl = trim(fgets(STDIN));

if (empty($webhookUrl)) {
    echo "❌ يجب إدخال رابط Webhook!\n";
    exit;
}

// تعيين Webhook
$apiUrl = "https://api.telegram.org/bot$TOKEN/setWebhook";
$data = [
    'url' => $webhookUrl
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

echo "\n===========================================\n";
if ($result && $result['ok']) {
    echo "✅ تم تعيين Webhook بنجاح!\n";
    echo "✅ Webhook set successfully!\n\n";
    echo "الرابط / URL: " . $webhookUrl . "\n";
    
    // التحقق من معلومات Webhook
    echo "\n===========================================\n";
    echo "معلومات Webhook الحالية / Current Webhook Info:\n";
    echo "===========================================\n";
    
    $infoUrl = "https://api.telegram.org/bot$TOKEN/getWebhookInfo";
    $info = json_decode(file_get_contents($infoUrl), true);
    
    if ($info && $info['ok']) {
        $webhookInfo = $info['result'];
        echo "الرابط / URL: " . ($webhookInfo['url'] ?? 'غير محدد') . "\n";
        echo "عدد الرسائل المعلقة / Pending updates: " . ($webhookInfo['pending_update_count'] ?? 0) . "\n";
        if (isset($webhookInfo['last_error_date'])) {
            echo "⚠️  آخر خطأ / Last error date: " . date('Y-m-d H:i:s', $webhookInfo['last_error_date']) . "\n";
            echo "رسالة الخطأ / Error message: " . ($webhookInfo['last_error_message'] ?? 'غير متاح') . "\n";
        }
    }
} else {
    echo "❌ فشل تعيين Webhook!\n";
    echo "❌ Failed to set Webhook!\n\n";
    echo "الخطأ / Error: " . ($result['description'] ?? 'خطأ غير معروف') . "\n";
    echo "Response: " . $response . "\n";
}
echo "===========================================\n";

