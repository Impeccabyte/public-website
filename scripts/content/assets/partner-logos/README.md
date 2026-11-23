# Partner logos

Official logo files for the hardware and gateway brands in the Home logo strip, fetched on 2026-11-19. They are trademarks of their owners; confirm each brand's permission and usage rules before launch.

| File | Source |
|---|---|
| `clover.png` | clover.com, the wordmark from its consent banner, cropped to the artwork |
| `pax.svg`, `dejavoo.svg`, `ingenico.svg` | maverickpayments.com, in-person payments page |
| `valor.svg` | valorpaytech.com, header logo |
| `nmi.png` | nmi.com, primary logo, converted from WebP |
| `authorize-net.png` | authorize.net, header logo, the same file as in `../gateway-logos/` |
| `paytrace.svg` | paytrace.com, header logo (fetched 2026-11-23) |
| `slimcd.png` | slimcd.com, header logo (fetched 2026-11-23) |
| `swipesimple.svg` | swipesimple.com, header logo (fetched 2026-11-23) |
| `ipospays.png` | portal.ipospays.com, the logo on its sign-in page (ipospays.com redirects there), trimmed to the artwork and scaled to 600 wide (fetched 2026-11-23) |

The gateways (Authorize.Net, NMI, PayTrace, Slim CD, SwipeSimple, iPOSPays) are the ones Maverick lists as core in its knowledge base; see `docs/maverick-offerings.md`, section 5. USAePay (owned by NMI) and Datacap (Certek Artisan POS only) have no logo of their own here.

The site never shows these in their own colors. The logo strip uses each file as a mask and fills it with the `--ink-600` token, so a file only needs a transparent background. Replace Clover with a vector file from the Clover brand kit when one is available.
