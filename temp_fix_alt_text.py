from pathlib import Path
import re

root = Path(r"c:\Users\admin\Documents\GitHub\theindusclub")
files = list(root.rglob("*.html")) + list(root.rglob("*.php"))

patterns = [
    (re.compile(r'(?is)(<img\b[^>]*?src=["\']https?://www\.facebook\.com/tr[^"\']*["\'][^>]*?)alt\s*=\s*["\']\s*["\']'), r'\1alt="Facebook tracking pixel"'),
    (re.compile(r'(?is)(<img\b[^>]*?src=["\']https?://dc\.ads\.linkedin\.com/collect/[^"\']*["\'][^>]*?)alt\s*=\s*["\']\s*["\']'), r'\1alt="LinkedIn tracking pixel"'),
    (re.compile(r'(?is)(<img\b[^>]*?class=["\'].*?lightbox-img.*?["\'][^>]*?)alt\s*=\s*["\']\s*["\']'), r'\1alt="Expanded gallery image"'),
    (re.compile(r'(?i)alt\s*=\s*["\']\s*["\']'), 'alt="The Indus Club gallery image"'),
    (re.compile(r'(?i)alt\s*=\s*["\']Logo["\']'), 'alt="The Indus Club logo"'),
    (re.compile(r'(?i)alt\s*=\s*["\']logo["\']'), 'alt="The Indus Club logo"'),
    (re.compile(r'(?i)alt\s*=\s*["\']Image["\']'), 'alt="The Indus Club gallery image"'),
    (re.compile(r'(?i)alt\s*=\s*["\']image["\']'), 'alt="The Indus Club gallery image"'),
    (re.compile(r'(?i)alt\s*=\s*["\']img["\']'), 'alt="The Indus Club image"'),
    (re.compile(r'(?i)alt\s*=\s*["\']Banner["\']'), 'alt="The Indus Club banner image"'),
    (re.compile(r'(?i)alt\s*=\s*["\']banner["\']'), 'alt="The Indus Club banner image"'),
    (re.compile(r'(?i)alt\s*=\s*["\']photo["\']'), 'alt="The Indus Club photo"'),
    (re.compile(r'(?i)alt\s*=\s*["\']picture["\']'), 'alt="The Indus Club picture"'),
]

updated = 0
for path in files:
    try:
        text = path.read_text(encoding='utf-8')
    except UnicodeDecodeError:
        text = path.read_text(encoding='latin-1')

    original = text
    for pattern, replacement in patterns:
        text = pattern.sub(replacement, text)

    if text != original:
        path.write_text(text, encoding='utf-8')
        updated += 1

print(f'Updated {updated} files')
