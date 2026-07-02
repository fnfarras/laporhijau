<?php

require __DIR__ . '/vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Quality;

// ─── STEP 1: Configure Cloudinary (inline — no .env required) ───────────────
$cloudinary = new Cloudinary(
    Configuration::instance([
        'cloud' => [
            'cloud_name' => 'fex0dx5u',      // ← Cloud Name
            'api_key'    => '634746924414925', // ← API Key
            'api_secret' => 'crWQ-tNkGytpBnBCbkdigUV67bI', // ← API Secret
        ],
        'url' => [
            'secure' => true,
        ],
    ])
);

// ─── STEP 2: Upload gambar dari URL demo Cloudinary ─────────────────────────
$sampleImageUrl = 'https://res.cloudinary.com/demo/image/upload/sample.jpg';

echo "⏳ Mengupload gambar dari Cloudinary demo...\n";

$uploadApi = new UploadApi();
$uploadResult = $uploadApi->upload($sampleImageUrl, [
    'folder'    => 'laporhijau_test',
    'public_id' => 'onboarding_sample',
]);

$secureUrl = $uploadResult['secure_url'];
$publicId  = $uploadResult['public_id'];

echo "✅ Upload berhasil!\n";
echo "   Secure URL : {$secureUrl}\n";
echo "   Public ID  : {$publicId}\n\n";

// ─── STEP 3: Ambil metadata detail gambar ───────────────────────────────────
echo "⏳ Mengambil metadata gambar...\n";

$adminApi = new AdminApi();
$assetDetails = $adminApi->asset($publicId);

$width    = $assetDetails['width'];
$height   = $assetDetails['height'];
$format   = $assetDetails['format'];
$fileSize = $assetDetails['bytes'];

echo "📐 Detail Gambar:\n";
echo "   Width     : {$width}px\n";
echo "   Height    : {$height}px\n";
echo "   Format    : {$format}\n";
echo "   File Size : {$fileSize} bytes\n\n";

// ─── STEP 4: Transformasi gambar dengan f_auto dan q_auto ───────────────────
// f_auto : Cloudinary otomatis memilih format terbaik (WebP, AVIF, dll)
//          berdasarkan browser pengguna — menghemat bandwidth tanpa kerja manual.
// q_auto : Cloudinary otomatis menyesuaikan kualitas kompresi untuk
//          keseimbangan terbaik antara ukuran file dan kualitas visual.

$transformedUrl = $cloudinary->image($publicId)
    ->delivery(Delivery::format('auto'))   // f_auto
    ->delivery(Delivery::quality('auto'))  // q_auto
    ->toUrl();

echo "✅ Done! Click link below to see optimized version of the image. Check the size and the format.\n";
echo "🔗 Transformed URL:\n   {$transformedUrl}\n";
