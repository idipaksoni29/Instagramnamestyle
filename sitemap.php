<?php
// ==========================================
// ✅ Auto Sitemap Generator for InstagramNameStyle.co.in (Fixed Double Slash Issue)
// ==========================================

$base_url = "https://instagramnamestyle.co.in"; // ❌ No trailing slash here

function get_all_files($dir) {
    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && preg_match('/\.html$/', $file->getFilename())) {
            $files[] = $file->getPathname();
        }
    }
    return $files;
}

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

// Homepage
$xml .= "  <url>\n";
$xml .= "    <loc>{$base_url}/</loc>\n";
$xml .= "    <changefreq>daily</changefreq>\n";
$xml .= "    <priority>1.0</priority>\n";
$xml .= "  </url>\n";

$files = get_all_files(__DIR__);

foreach ($files as $file) {
    $url = str_replace($_SERVER['DOCUMENT_ROOT'], $base_url, $file);
    $url = str_replace('\\', '/', $url); // Fix for Windows paths
    $url = preg_replace('#(?<!:)//+#', '/', $url); // ✅ Remove double slashes
    $url = htmlspecialchars($url);

    // Skip sitemap files
    if (strpos($url, 'sitemap.php') !== false || strpos($url, 'sitemap.xml') !== false) {
        continue;
    }

    $xml .= "  <url>\n";
    $xml .= "    <loc>{$url}</loc>\n";
    $xml .= "    <changefreq>weekly</changefreq>\n";
    $xml .= "    <priority>0.8</priority>\n";
    $xml .= "  </url>\n";
}

$xml .= '</urlset>';

// Save the sitemap.xml file
file_put_contents(__DIR__ . '/sitemap.xml', $xml);

echo "✅ Sitemap generated successfully for InstagramNameStyle.co.in (fixed double slashes)!<br>";
echo "Check: <a href='{$base_url}/sitemap.xml' target='_blank'>{$base_url}/sitemap.xml</a>";
?>