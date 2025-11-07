<?php
require_once "config.php";

$info = json_decode(file_get_contents("https://api.telegram.org/bot$TOKEN/getMe"), true);

if ($info && $info['ok']) {
    $username = $info['result']['username'];
    echo "Bot Username: @$username\n";
    
    // Update config.php
    $configContent = file_get_contents("config.php");
    $configContent = str_replace('$botUsername = "YOUR_BOT_USERNAME_HERE";', '$botUsername = "' . $username . '";', $configContent);
    file_put_contents("config.php", $configContent);
    
    echo "✅ تم تحديث config.php بنجاح!\n";
    echo "✅ config.php updated successfully!\n";
} else {
    echo "❌ خطأ في الحصول على معلومات البوت\n";
    echo "❌ Error getting bot info\n";
}

