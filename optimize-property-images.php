<?php
// Optimize dynamically uploaded property images
$uploadDir = __DIR__ . '/upload/properties/';
$optimizedDir = __DIR__ . '/upload/properties/optimized/';

if (!file_exists($optimizedDir)) {
    mkdir($optimizedDir, 0755, true);
}

// Get all uploaded property images
$images = glob($uploadDir . '*.{jpg,jpeg,png}', GLOB_BRACE);

foreach ($images as $image) {
    $filename = basename($image);
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $outputPath = $optimizedDir . $filename;
    $webpPath = $optimizedDir . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
    
    // Skip if already optimized
    if (file_exists($outputPath) && file_exists($webpPath)) {
        echo "Skipping $filename (already optimized)\n";
        continue;
    }
    
    // Create optimized version based on file type
    if ($extension === 'png') {
        $source = imagecreatefrompng($image);
    } else {
        $source = imagecreatefromjpeg($image);
    }
    
    if (!$source) {
        echo "Error processing $filename\n";
        continue;
    }
    
    // Get original dimensions
    $width = imagesx($source);
    $height = imagesy($source);
    
    // Calculate new dimensions (max width 1200px for web)
    $maxWidth = 1200;
    if ($width > $maxWidth) {
        $ratio = $maxWidth / $width;
        $newWidth = $maxWidth;
        $newHeight = $height * $ratio;
        
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        if ($extension === 'png') {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }
        
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($source);
        $source = $resized;
    }
    
    // Save optimized version
    if ($extension === 'png') {
        imagepng($source, $outputPath, 8); // 8 = good compression for PNG
    } else {
        imagejpeg($source, $outputPath, 85); // 85 = good quality for JPEG
    }
    
    // Create WebP version for better compression
    imagewebp($source, $webpPath, 85); // 85 = good quality for WebP
    
    imagedestroy($source);
    
    $originalSize = filesize($image);
    $optimizedSize = filesize($outputPath);
    $webpSize = filesize($webpPath);
    $savings = (($originalSize - $optimizedSize) / $originalSize) * 100;
    $webpSavings = (($originalSize - $webpSize) / $originalSize) * 100;
    
    echo "Optimized $filename:\n";
    echo "  Original: " . round($originalSize/1024) . "KB\n";
    echo "  Optimized ($extension): " . round($optimizedSize/1024) . "KB (" . round($savings) . "% savings)\n";
    echo "  WebP: " . round($webpSize/1024) . "KB (" . round($webpSavings) . "% savings)\n\n";
}

echo "Property image optimization complete!\n";
echo "Optimized images are in: upload/properties/optimized/\n";
?>
