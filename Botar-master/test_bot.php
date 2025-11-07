<?php
// اختبار سريع للبوت
$TOKEN = "8075818083:AAG3YIe0z_OObQiR9Ed9jw_pEBahPWNPmoY";

$url = "https://api.telegram.org/bot$TOKEN/getMe";
$response = file_get_contents($url);
$result = json_decode($response, true);

if ($result && $result['ok']) {
    $bot = $result['result'];
    echo "✅ البوت يعمل بنجاح!\n";
    echo "الاسم: " . $bot['first_name'] . "\n";
    echo "المعرف: @" . $bot['username'] . "\n";
    echo "ID: " . $bot['id'] . "\n";
    
    // تحديث config.php
    $configFile = __DIR__ . '/config.php';
    $config = file_get_contents($configFile);
    $config = str_replace('$botUsername = "YOUR_BOT_USERNAME_HERE";', '$botUsername = "' . $bot['username'] . '";', $config);
    file_put_contents($configFile, $config);
    
    echo "\n✅ تم تحديث معرف البوت في config.php\n";
    echo "المعرف: " . $bot['username'] . "\n";
} else {
    echo "❌ خطأ: " . ($result['description'] ?? 'خطأ غير معروف') . "\n";
}

