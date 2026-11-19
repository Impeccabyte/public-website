#!/usr/bin/env python3
"""Builds the favicon and the touch icon from the Impeccabyte mark.

Writes favicon.svg and apple-touch-icon.png into the theme. The PNG is drawn
here with no third-party packages: rounded rectangles, four samples per pixel
edge for smooth corners. Colors are the design tokens clay-500, amber-400 and
paper. Run it again whenever the mark changes:

    python3 scripts/favicon/build-icons.py
"""
import struct
import zlib
from pathlib import Path

THEME = Path(__file__).resolve().parents[2] / "web/themes/custom/impeccabyte"
CLAY = (0xC0, 0x62, 0x3E)
AMBER = (0xE0, 0xA0, 0x4D)
MID = (0xD1, 0x7C, 0x56)
PAPER = (0xFA, 0xF6, 0xEF)

# The mark on its 72-unit square (viewBox 14 20 72 72 in the header logo):
# a base block and three rising squares. x, y, width, height, radius, color.
SHAPES = [
    (16, 60, 68, 30, 7, CLAY),
    (28, 46, 11, 11, 3.4, CLAY),
    (43, 34, 11, 11, 3.4, MID),
    (58, 22, 11, 11, 3.4, AMBER),
]

SVG = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="8 14 84 84">
  <rect x="8" y="14" width="84" height="84" rx="18" fill="#faf6ef"/>
  <rect x="16" y="60" width="68" height="30" rx="7" fill="#c0623e"/>
  <rect x="28" y="46" width="11" height="11" rx="3.4" fill="#c0623e"/>
  <rect x="43" y="34" width="11" height="11" rx="3.4" fill="#d17c56"/>
  <rect x="58" y="22" width="11" height="11" rx="3.4" fill="#e0a04d"/>
</svg>
"""


def inside(px, py, x, y, w, h, r):
    """Whether a point is inside a rounded rectangle."""
    if not (x <= px <= x + w and y <= py <= y + h):
        return False
    cx = min(max(px, x + r), x + w - r)
    cy = min(max(py, y + r), y + h - r)
    return (px - cx) ** 2 + (py - cy) ** 2 <= r * r


def render(size, samples=4):
    """Renders the mark on a paper square, origin 8,14 and 84 units wide."""
    scale = 84 / size
    rows = []
    for row in range(size):
        line = bytearray([0])
        for col in range(size):
            total = [0, 0, 0]
            for sy in range(samples):
                for sx in range(samples):
                    px = 8 + (col + (sx + 0.5) / samples) * scale
                    py = 14 + (row + (sy + 0.5) / samples) * scale
                    color = PAPER
                    for x, y, w, h, r, fill in SHAPES:
                        if inside(px, py, x, y, w, h, r):
                            color = fill
                    for i in range(3):
                        total[i] += color[i]
            line += bytes(round(c / samples ** 2) for c in total)
        rows.append(bytes(line))
    return b"".join(rows)


def png(size):
    def chunk(kind, data):
        body = kind + data
        return struct.pack(">I", len(data)) + body + struct.pack(">I", zlib.crc32(body) & 0xFFFFFFFF)

    header = struct.pack(">IIBBBBB", size, size, 8, 2, 0, 0, 0)
    return b"\x89PNG\r\n\x1a\n" + chunk(b"IHDR", header) + chunk(b"IDAT", zlib.compress(render(size), 9)) + chunk(b"IEND", b"")


if __name__ == "__main__":
    (THEME / "favicon.svg").write_text(SVG)
    (THEME / "apple-touch-icon.png").write_bytes(png(180))
    print("Wrote favicon.svg and apple-touch-icon.png to", THEME)
