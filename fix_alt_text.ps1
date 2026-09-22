$root = "c:\Users\admin\Documents\GitHub\theindusclub"
$files = Get-ChildItem -Path $root -Recurse -Include *.html,*.php

$patterns = @(
    @{ pattern = "(?is)(<img\b[^>]*?class=[\"'].*?lightbox-img.*?[\"'][^>]*?)alt\s*=\s*[\"']\s*[\"']"; replacement = '${1}alt="Expanded gallery image"' },
    @{ pattern = "(?is)(<img\b[^>]*?src=[\"']https?://www\.facebook\.com/tr[^\"']*[\"'][^>]*?)alt\s*=\s*[\"']\s*[\"']"; replacement = '${1}alt="Facebook tracking pixel"' },
    @{ pattern = "(?is)(<img\b[^>]*?src=[\"']https?://dc\.ads\.linkedin\.com/collect/[^\"']*[\"'][^>]*?)alt\s*=\s*[\"']\s*[\"']"; replacement = '${1}alt="LinkedIn tracking pixel"' },
    @{ pattern = "(?i)alt\s*=\s*[\"']\s*[\"']"; replacement = 'alt="The Indus Club gallery image"' },
    @{ pattern = "(?i)alt\s*=\s*[\"']Logo[\"']"; replacement = 'alt="The Indus Club logo"' },
    @{ pattern = "(?i)alt\s*=\s*[\"']logo[\"']"; replacement = 'alt="The Indus Club logo"' },
    @{ pattern = "(?i)alt\s*=\s*[\"']Image[\"']"; replacement = 'alt="The Indus Club gallery image"' },
    @{ pattern = "(?i)alt\s*=\s*[\"']image[\"']"; replacement = 'alt="The Indus Club gallery image"' },
    @{ pattern = "(?i)alt\s*=\s*[\"']img[\"']"; replacement = 'alt="The Indus Club image"' },
    @{ pattern = "(?i)alt\s*=\s*[\"']Banner[\"']"; replacement = 'alt="The Indus Club banner image"' },
    @{ pattern = "(?i)alt\s*=\s*[\"']banner[\"']"; replacement = 'alt="The Indus Club banner image"' },
    @{ pattern = "(?i)alt\s*=\s*[\"']photo[\"']"; replacement = 'alt="The Indus Club photo"' },
    @{ pattern = "(?i)alt\s*=\s*[\"']picture[\"']"; replacement = 'alt="The Indus Club picture"' },
    @{ pattern = "(?i)stageImg\.alt\s*=\s*[\"']\s*[\"'];?"; replacement = 'stageImg.alt = "The Indus Club gallery image";' }
)

$count = 0
foreach ($file in $files) {
    $text = [System.IO.File]::ReadAllText($file.FullName, [System.Text.UTF8Encoding]::new($false))
    $before = $text
    foreach ($entry in $patterns) {
        $text = [regex]::Replace($text, $entry.pattern, $entry.replacement)
    }
    if ($text -ne $before) {
        [System.IO.File]::WriteAllText($file.FullName, $text, [System.Text.UTF8Encoding]::new($false))
        $count++
    }
}
Write-Host "Updated $count files"
