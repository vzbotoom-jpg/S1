# 🚀 Panduan Upgrade Gemini API ke Paid Plan

## Status Saat Ini
- ✅ API Key: Already working
- ✅ Service: Fully implemented with retry logic
- ⚠️ Issue: Free tier quota sudah habis (429 RESOURCE_EXHAUSTED)

---

## 📋 Option 1: Upgrade ke Paid Plan (RECOMMENDED)

### Step 1: Setup Billing di Google Cloud
1. Buka https://console.cloud.google.com/
2. Login dengan akun Google yang sama
3. Di sidebar, klik **Billing**
4. Klik **Linking a Billing Account**
5. Pilih atau **Create New Billing Account**
6. Isi informasi kartu kredit

### Step 2: Verify Project Linked
1. Di sidebar, klik **IAM & Admin** → **Settings**
2. Catat **Project ID** Anda
3. Pastikan project ini sudah linked ke billing account

### Step 3: Check & Request Quota Increase
1. Pergi ke **APIs & Services** → **Quotas**
2. Filter: `generativelanguage.googleapis.com`
3. Akan melihat quotas seperti:
   - `GenerateRequestsPerMinutePerProjectPerModel-FreeTier`
   - `GenerateContentInputTokensPerModelPerMinute-FreeTier`
4. Setelah upgrade, free tier limits akan dihapus
5. Baru akan di-charge sesuai usage

### Step 4: Pricing
Lihat terbaru di: https://ai.google.dev/pricing

Kurang-lebih:
- **Input tokens**: ~$0.075 per 1M tokens
- **Output tokens**: ~$0.3 per 1M tokens
- **Cukup murah** untuk kebanyakan aplikasi

---

## 📋 Option 2: Gunakan API Key Baru

Jika ingin test lebih dulu, buat API key baru:
1. Buka https://aistudio.google.com/app/apikey
2. Klik **Create new API key**
3. Copy key baru
4. Update di `.env`: `GEMINI_API_KEY=<new-key>`
5. Clear cache: `php artisan config:clear`

---

## 🔄 Implementasi Caching (Mengurangi API Calls)

### Cache Responses ke Database
```php
// Contoh di controller
$cacheKey = 'chat:' . md5($userMessage);
$cachedResponse = cache()->get($cacheKey);

if ($cachedResponse) {
    return $cachedResponse;
}

$response = $gemini->sendMessage($userMessage);
cache()->put($cacheKey, $response, 86400); // Cache 24 jam

return $response;
```

### Redis Cache (Lebih Cepat)
Update `.env`:
```
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

---

## 🧪 Testing

### Test dengan Retry Logic:
```bash
php artisan test:gemini-api "Test message"
```

Sekarang akan:
- ✅ Retry jika quota limit (hingga 3x)
- ✅ Exponential backoff (2s, 4s, 8s)
- ✅ Tampilkan info jika quota exceeded

### Check Logs:
```bash
tail -50 storage/logs/laravel.log
```

---

## 📊 Monitor Usage

1. Buka https://console.cloud.google.com/
2. Pergi ke **APIs & Services** → **Quotas**
3. Lihat usage real-time
4. Setup alerts jika melebihi threshold

---

## ✨ Peningkatan Sudah Diterapkan

### GeminiService.php
- ✅ Retry logic dengan exponential backoff
- ✅ Support 429 rate limit handling
- ✅ Better error logging
- ✅ Interface implementation sesuai standar

### TestGeminiApi Command
- ✅ Better error messages
- ✅ Quota exceeded detection
- ✅ Suggestions untuk solutions
- ✅ Fixed message display

---

## 🎯 Next Steps

1. **Pilih Option**: Upgrade ke Paid atau gunakan API key baru
2. **Update .env**: Ganti API key jika perlu
3. **Clear Cache**: `php artisan config:clear`
4. **Test**: `php artisan test:gemini-api`
5. **Monitor**: Pantau usage di Cloud Console

Semua sudah siap! Tinggal upgrade billing account saja. 🚀
