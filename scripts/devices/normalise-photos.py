"""Normalise official product cutouts onto one consistent canvas.

Every output is a 1200x1032 transparent WebP (the gallery stage's 1:0.86
ratio). The product is trimmed to its visible pixels, then scaled to fit a
fixed box centred on the canvas, so every device sits at the same margins on
the light card panel and the white gallery stage alike.

usage: normalise.py <picks.tsv> <raw dir> <out dir>
picks.tsv columns: slug, order, view label, source file name
"""
import csv, os, sys
from PIL import Image

W, H = 1200, 1032           # 1 : 0.86
BOX_W, BOX_H = 1120, 960    # product never exceeds this; ~3-4% margins (cards and stage add their own padding)
MAX_UPSCALE = 1.6           # beyond this a small source would blur
# Pocket readers sit smaller so they do not read as big as a POS station,
# matching the wireframe's placeholder proportions (reader 76px vs 130px).
SHRINK = {'clover-go': 0.72, 'swipesimple-b350': 0.72}

picks, raw, out = sys.argv[1:4]
os.makedirs(out, exist_ok=True)
report = []
for slug, order, view, src in csv.reader(open(picks), delimiter='\t'):
    im = Image.open(os.path.join(raw, src))
    im.load()
    im = im.convert('RGBA')
    alpha = im.getchannel('A')
    # Drop near-invisible fringe pixels so the trim finds the real edge.
    alpha = alpha.point(lambda a: 0 if a < 8 else a)
    im.putalpha(alpha)
    bbox = alpha.getbbox()
    im = im.crop(bbox)
    shrink = SHRINK.get(slug, 1.0)
    scale = min(BOX_W * shrink / im.width, BOX_H * shrink / im.height, MAX_UPSCALE)
    size = (round(im.width * scale), round(im.height * scale))
    im = im.resize(size, Image.LANCZOS)
    canvas = Image.new('RGBA', (W, H), (0, 0, 0, 0))
    canvas.alpha_composite(im, ((W - size[0]) // 2, (H - size[1]) // 2))
    name = f'{slug}-{order}-{view}.webp'
    canvas.save(os.path.join(out, name), 'WEBP', quality=88, method=6)
    fit = 'fits box' if scale < MAX_UPSCALE else 'capped (small source)'
    report.append(f'{name}: trimmed {bbox[2]-bbox[0]}x{bbox[3]-bbox[1]}, scale {scale:.2f} -> {size[0]}x{size[1]} ({fit}), {os.path.getsize(os.path.join(out, name))//1024} KB')
print('\n'.join(report))
