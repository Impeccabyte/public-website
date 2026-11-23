# Device photos

Official product images for the 26 devices in the hardware catalog, taken on 2026-11-19 from each manufacturer's own website and edited to fit the site. They are the manufacturers' marketing images, used to show the products Impeccabyte sells. Swap any of them for a newer official image or your own photography by changing the device's gallery.

## How they were edited

Each source image is a transparent cutout. `scripts/devices/normalise-photos.py` trims it to the product, scales it into a fixed 1120 × 960 box and centres it on a 1200 × 1032 transparent canvas (the gallery stage's 1 : 0.86 ratio), saved as WebP at quality 88. Every device then sits at the same margins on the cream card panel and the white gallery stage. Pocket readers (Clover Go, SwipeSimple B350) are drawn at 72% of the box so they do not look as big as a POS station, matching the wireframe's placeholder proportions.

No image had its colours, screen content or branding changed. Images on brand graphics (Dejavoo's purple circles), lifestyle photos, hands and annotated diagrams were left out.

## Sources

The first image for each device is its catalog and gallery lead.

| File | Source |
|---|---|
| `clover-mini-1-angled.webp` | clover.com/mini: `clover-mini-order-screen.webp` |
| `clover-mini-2-front.webp` | clover.com/shop: `mini-shop.png` (small source, upscaled 1.6×) |
| `clover-flex-1-angled.webp` | clover.com/flex: `flex-3-left-up-Order-Screen-sq.png` |
| `clover-flex-2-front.webp` | clover.com/shop: `shop_flex_image__1_.png` (small source, upscaled 1.6×) |
| `clover-station-duo-1-front.webp` | clover.com/shop: `clover-duo-printer-mini3-facing-customer.webp` |
| `clover-station-solo-1-merchant.webp` | clover.com/station-solo: `clover-station-solo-merchant-facing.webp` |
| `clover-station-solo-2-front.webp` | clover.com/shop: `station-solo-shop.png` |
| `clover-compact-1-angled.webp` | clover.com/compact: `clover_compact_payment_terminal.webp` |
| `clover-compact-2-front.webp` | clover.com/shop: `clover_compact_shop.webp` |
| `clover-go-1-front.webp` | clover.com/shop: `go3-shop.png` (small source, upscaled 1.4×) |
| `clover-kiosk-1-front.webp` | clover.com/kiosk: `kiosk-front-create-sale-1-shadow__1__2.png` |
| `dejavoo-z8-1-angled.webp` | dejavoo.io media library: `2024/07/Dejavoo_Z8.webp` |
| `dejavoo-qd4-1-front.webp` | dejavoo.io media library: `2024/07/v1_QD4.webp` |
| `dejavoo-qd4-2-angled.webp` | dejavoo.io media library: `2025/08/Dejavoo-QD4_Cable-scaled.webp` |
| `dejavoo-qd4-3-white.webp` | dejavoo.io QD4 family page: `2026/03/QD4-White-1080.webp` |
| `pax-a920-pro-1-angled.webp` | pax.us media library: `2025/05/A920Pro_angled_left_appscreen.png` |
| `pax-a920-pro-2-front.webp` | pax.us media library: `2023/12/A920Pro-_9.png` |
| `pax-a920-pro-3-side.webp` | pax.us media library: `2023/12/a920pro_transparent.png` |
| `valor-vp550-1-front.webp` | valorpaytech.com/…/vp550: `Valor-VP550-Comparsion-image.png` |
| `valor-vp550-2-signature.webp` | valorpaytech.com/…/vp550: `VP550-vp.png` |
| `swipesimple-b350-1-angled.webp` | home.swipesimple.com/product/reader-contactless: `Side tilt 2 350 Reader SwipeSimple.png` |
| `swipesimple-b350-2-front.webp` | home.swipesimple.com/card-readers: `SwipeSimple 350 Reader.avif` (small source, upscaled 1.6×) |
| `dejavoo-qd2-1-angled.webp` | dejavoo.io media library: `2025/10/1080x1080_QD2.webp` |
| `dejavoo-qd2-2-white.webp` | dejavoo.io media library: `2026/03/QD2-White-1080.webp` |
| `dejavoo-qd3-1-front.webp` | dejavoo.io media library: `2025/10/1080x1080_website-QD3_mPOS.webp` |
| `dejavoo-p1-1-front.webp` | dejavoo.io media library: `2024/08/P1-2-scaled-1.webp` |
| `dejavoo-p3-1-angled.webp` | dejavoo.io media library: `2024/08/P3-scaled-1.webp` |
| `dejavoo-p3-2-front.webp` | dejavoo.io media library: `2024/08/P3_Front_BlackScreen-1.webp` |
| `dejavoo-p5-1-front.webp` | dejavoo.io media library: `2024/08/P5-2-scaled-1.webp` |
| `dejavoo-p5-2-dual-pricing.webp` | dejavoo.io media library: `2024/07/v1_P5.png` |
| `dejavoo-p8-1-front.webp` | dejavoo.io media library: `2024/08/P8-scaled-1.webp` |
| `dejavoo-p8-2-amount.webp` | dejavoo.io media library: `2024/04/v1_P8.webp` |
| `dejavoo-p18-1-docked.webp` | dejavoo.io media library: `2026/09/P18_RENDER-scaled.webp` |
| `valor-vp100-1-front.webp` | valorpaytech.com media library: `2025/09/Valor-VP100-Comparsion-image.png` |
| `valor-vp100-2-signature.webp` | valorpaytech.com media library: `2024/07/Countertop-POS-Devices-VP100.png` |
| `valor-vp800-1-front.webp` | valorpaytech.com media library: `2025/09/Valor-VP800-Comparsion-image.png` |
| `valor-vp800-2-payment.webp` | valorpaytech.com media library: `2024/07/Andorid-Devices-VP800.png` |
| `valor-vl550-1-front.webp` | valorpaytech.com media library: `2025/09/Valor-VL550-Comparsion-image.png` |
| `valor-vl550-2-signature.webp` | valorpaytech.com media library: `2024/07/VL550-pos-terminal-menu.png` |
| `pax-a80-1-angled.webp` | pax.us media library: `2023/03/A80.png` |
| `pax-a80-2-portrait.webp` | pax.us media library: `2025/03/payment-device-pax-a80-countertop-portrait.webp` (small source, upscaled 1.6×) |
| `pax-a35-1-angled.webp` | pax.us media library: `2023/03/A35_transparent.png` |
| `pax-a35-2-screen.webp` | pax.us media library: `2025/07/payment-device-pax-A35-1.webp` |
| `fiserv-fd150-1-with-pin-pad.webp` | merchants.fiserv.com/en-us/products/small-business/fd150: `FD150-terminal.png`, shown with the optional RP10 PIN pad (small source, upscaled 1.6×) |
| `clover-flex-pocket-1-front.webp` | clover.com/shop: `shop_flexpocket_image__1_.png` (small source, upscaled 1.6×) |

PAX now sells the A920Pro Duo (PCI 7) as its current model; these images are the A920 Pro (PCI 5) the catalog lists.
