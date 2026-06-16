<?php
/**
 * Script tự động fix lỗi encoding tiếng Việt trong các file PHP views
 * Chạy 1 lần: http://localhost/ins3064/Group7_CapstoneManager-main/fix_encoding.php
 */

$viewsDir = __DIR__ . '/app/views';
$fixed = [];
$errors = [];

function fixEncoding($filePath) {
    // Đọc file dưới dạng binary
    $raw = file_get_contents($filePath);
    if ($raw === false) return false;

    // Kiểm tra xem file có bị lỗi double-encoding không
    // Các bytes UTF-8 tiếng Việt khi đọc sai thành Latin-1 rồi encode lại UTF-8 tạo ra pattern Ã½, á», Ä'...
    // Ta cần decode từ Latin-1 -> UTF-8 đúng
    $decoded = mb_convert_encoding($raw, 'UTF-8', 'ISO-8859-1');

    // Kiểm tra xem file gốc có thực sự bị lỗi không (so sánh)
    if ($decoded === $raw) return false;

    // Verify decoded text là UTF-8 hợp lệ và có tiếng Việt
    if (!mb_check_encoding($decoded, 'UTF-8')) return false;

    // Chỉ ghi lại nếu thực sự có sự thay đổi tiếng Việt
    if (preg_match('/[\x{0080}-\x{FFFF}]/u', $decoded)) {
        file_put_contents($filePath, $decoded);
        return true;
    }
    return false;
}

// Lấy tất cả file PHP trong views
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($viewsDir)
);

foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php') continue;

    $path = $file->getRealPath();
    $content = file_get_contents($path);

    // Kiểm tra có bị lỗi encoding không
    if (preg_match('/Ã½|Ã´ng|Ã¡n|á»|Ä\'á»|Æ°á»|Ã¨|Ã©|Ã ng|Ä\x90á»/', $content)) {
        if (fixEncoding($path)) {
            $fixed[] = str_replace(__DIR__, '', $path);
        } else {
            $errors[] = $path;
        }
    }
}

echo "<pre>";
echo "=== KẾT QUẢ FIX ENCODING ===\n\n";
echo "Đã fix " . count($fixed) . " file:\n";
foreach ($fixed as $f) {
    echo "  ✓ $f\n";
}
if (!empty($errors)) {
    echo "\nLỗi " . count($errors) . " file:\n";
    foreach ($errors as $e) {
        echo "  ✗ $e\n";
    }
}
echo "\nHoàn thành!";
echo "</pre>";
