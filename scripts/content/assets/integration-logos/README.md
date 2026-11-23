# Integration logos

Logo files for the platforms on the Integrations page, fetched on 2026-11-19. They are trademarks of their owners; confirm each brand's usage rules before launch.

| File | Source |
|---|---|
| `doordash.svg` | Wikimedia Commons, "DoorDash Logo.svg"; a viewBox was added so it scales |
| `uber-eats.svg` | Wikimedia Commons, "Uber Eats 2020 logo.svg" |
| `grubhub.svg` | Wikimedia Commons, "Grubhub 2025 logo.svg" |
| `shopify.svg` | Wikimedia Commons, "Shopify logo 2018.svg" |
| `woocommerce.svg` | Wikimedia Commons, "WooCommerce2025 logo.svg" (the current "Woo" mark) |
| `bigcommerce.svg` | Wikimedia Commons, "Bc-logo-dark.svg" |
| `magento.svg` | Wikimedia Commons, "Magento Logo.svg" ("Magento, An Adobe Company") |
| `quickbooks.svg` | Wikimedia Commons, "Intuit QuickBooks logo.svg", converted (see below) |
| `xero.svg` | English Wikipedia, "Xero software logo.svg", converted (see below) |

The page draws each file as a mask filled with the `--ink-600` token, so only the shape matters. QuickBooks and Xero put white lettering on a coloured shape, which a mask would fill in solid. Both were converted so the white parts become cut-outs: the original artwork sits inside an SVG mask, recoloured so the shape is white and the lettering black.

A platform is named on the site only where Maverick's knowledge base confirms the connection (see `docs/maverick-offerings.md`, Integrations): Clover's restaurant plans for the delivery services, Maverick's checkout partners and the Valor and iPOSPays plugins for the carts, and Chargezoom, Biller Genie and RECUR360 for the accounting tools. NetSuite and Salesforce were dropped on 2026-11-22 because the knowledge base does not name them.
