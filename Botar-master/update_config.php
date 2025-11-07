<?php
/**
 * سكريبت لتحديث معرف البوت في config.php تلقائياً
 * Script to automatically update bot username in config.php
 */

require_once "config.php";

echo "===========================================\n";
echo "تحديث إعدادات البوت - Updating Bot Settings\n";
echo "===========================================\n\n";

// التحقق من التوكن
if (empty($TOKEN) || $TOKEN === "YOUR_BOT_TOKEN_HERE") {
    die("❌ خطأ: يجب تعيين توكن البوت في config.php أولاً!\n");
}

// الحصول على معلومات البوت
echo "جاري الحصول على معلومات البوت...\n";
echo "Getting bot information...\n\n";

$botInfoUrl = "https://api.telegram.org/bot$TOKEN/getMe";
$botInfo = json_decode(file_get_contents($botInfoUrl), true);

if ($botInfo && $botInfo['ok']) {
    $bot = $botInfo['result'];
    $botUsername = $bot['username'];
    
    echo "✅ تم الحصول على معلومات البوت:\n";
    echo "✅ Bot information retrieved:\n";
    echo "   الاسم / Name: " . $bot['first_name'] . "\n";
    echo "   المعرف / Username: @" . $botUsername . "\n";
    echo "   ID: " . $bot['id'] . "\n\n";
    
    // تحديث config.php
    $configFile = __DIR__ . '/config.php';
    $configContent = file_get_contents($configFile);
    
    // تحديث معرف البوت
    if (strpos($configContent, 'YOUR_BOT_USERNAME_HERE') !== false) {
        $configContent = str_replace(
            '$botUsername = "YOUR_BOT_USERNAME_HERE";',
            '$botUsername = "' . $botUsername . '";',
            $configContent
        );
        file_put_contents($configFile, $configContent);
        echo "✅ تم تحديث معرف البوت في config.php\n";
        echo "✅ Bot username updated in config.php\n\n";
    } else {
        echo "⚠️  معرف البوت موجود بالفعل في config.php\n";
        echo "⚠️  Bot username already exists in config.php\n\n";
    }
    
    // عرض الإعدادات الحالية
    echo "===========================================\n";
    echo "الإعدادات الحالية - Current Settings:\n";
    echo "===========================================\n";
    echo "✅ التوكن / Token: " . substr($TOKEN, 0, 10) . "...\n";
    echo "✅ معرف البوت / Bot Username: @" . $botUsername . "\n";
    echo "✅ معرف المطور / Developer ID: " . $sudoID . "\n";
    echo "===========================================\n\n";
    
    echo "🎉 تم إكمال الإعداد بنجاح!\n";
    echo "🎉 Setup completed successfully!\n\n";
    
    echo "الخطوة التالية / Next Steps:\n";
    echo "1. ارفع الملفات على خادم ويب يدعم PHP\n";
    echo "2. استخدم set_webhook.php لتعيين Webhook\n";
    echo "3. أو عيّن Webhook يدوياً عبر المتصفح\n\n";
    
} else {
    echo "❌ خطأ: لا يمكن الحصول على معلومات البوت\n";
    echo "❌ Error: Cannot get bot information\n";
    if (isset($botInfo['description'])) {
        echo "السبب / Reason: " . $botInfo['description'] . "\n";
    }
    echo "\nتأكد من:\n";
    echo "1. التوكن صحيح\n";
    echo "2. الاتصال بالإنترنت يعمل\n";
}

