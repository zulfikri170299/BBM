import os, re

views = r'd:\PROJEK\BBM\BBM\resources\views'
count = 0

def fix_svg_text(match):
    full_svg = match.group(0)
    # If the SVG contains the broken text classes, revert to text-white
    # Only do this if we suspect it should be white (usually icons in buttons/badges should be white if they originally were)
    # Actually, my previous script ONLY replaced 'text-white' with 'text-slate-900 dark:text-white'
    # So ANY 'text-slate-900 dark:text-white' inside an SVG used to be 'text-white'!
    # Is it safe to revert all of them inside SVGs?
    # SVGs rarely used text-white unless they were inside a colored container.
    # Wait, some SVGs might be standalone icons that were text-white and NEEDED to be dark in light mode?
    # Actually, if they were text-white originally, they should probably stay text-white if they are inside buttons, or if the user complains.
    # Let's revert it for all SVGs, because standalone icons usually use text-gray-500, not text-white.
    if 'text-slate-900 dark:text-white' in full_svg:
        full_svg = full_svg.replace('text-slate-900 dark:text-white', 'text-white')
    if 'text-slate-800 dark:text-white' in full_svg:
        full_svg = full_svg.replace('text-slate-800 dark:text-white', 'text-white')
    return full_svg

for r, d, fs in os.walk(views):
    for f in fs:
        if f.endswith('.blade.php'):
            fp = os.path.join(r, f)
            with open(fp, 'r', encoding='utf-8') as fh:
                c = fh.read()
            orig = c
            
            # Match entire <svg ...> tag
            c = re.sub(r'<svg[^>]+>', fix_svg_text, c)
            
            if c != orig:
                with open(fp, 'w', encoding='utf-8') as fh:
                    fh.write(c)
                count += 1

print(f"Fixed SVG text-white in {count} files.")
