# Gateway logos

Official logo files for the gateways on the Online page, fetched on 2026-11-19. They are trademarks of their owners; confirm each brand's usage rules before launch.

| File | Source |
|---|---|
| `authorize-net.png` | authorize.net, the header logo (a 206 by 45 JPG), with its white background made transparent |
| `nmi.png` | nmi.com, primary logo, the same file as in `../partner-logos/` |
| `accept-blue.png` | accept.blue, the site logo (an SVG with wide padding), rendered and cropped to the artwork with a transparent background |

The page draws each file as a mask filled with the `--ink-600` token, so only the shape matters.

A gateway is named on the site only after checking on its own pages that it supports TSYS or Fiserv, the networks Impeccabyte's merchant accounts run on. Authorize.Net, NMI and accept.blue were checked on 2026-11-19. USAePay also supports TSYS but now redirects to NMI, which owns it, so it has no separate logo.
