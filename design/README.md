# Design source

`wireframes/` holds the approved hi-fi wireframes as exported design files. Live canvas: https://claude.ai/artifact/XAKMdPXFarosG9eHVzxm1M

| File | Screen |
|---|---|
| `Main.dc.html` | 1. Home |
| `Solutions-Menu.dc.html` | 2. Solutions mega menu open |
| `Solution-DualPricing.dc.html` | 3. Solution detail: dual pricing |
| `Hardware.dc.html` | 4. Hardware catalog |
| `Device-CloverMini.dc.html` | 5. Device detail: Clover Mini |
| `Industry-Restaurants.dc.html` | 6. Industry landing: restaurants |
| `Pricing.dc.html` | 7. Pricing |
| `Resources.dc.html` | 8. Resource library |
| `Quote.dc.html` | 9. Get a quote |
| `Header.dc.html`, `Footer.dc.html` | Global header (with mega menu and mobile drawer) and footer |
| `Home-Tablet.dc.html`, `Home-Mobile.dc.html`, `Mobile-Menu.dc.html` | Wrappers that show the home page and header at 834px and 390px |

## How to read a `.dc.html` file

These are not runnable pages on their own. Each one is a template plus a small script.

- The markup between `<x-dc>` and `</x-dc>` is the design. Inline `style` attributes carry the exact layout and sizes.
- `<helmet><style>` holds the page's CSS classes, including responsive breakpoints and hover, focus and pressed states.
- `{{name}}` is a value from the script's `renderVals()` at the bottom of the file. That function also holds the seed content: device lists, FAQ items, industry setups, resource cards.
- `<sc-for list="{{items}}" as="x">` repeats its content for each item. `<sc-if value="{{flag}}">` shows its content when the flag is true.
- `<dc-import name="Header">` includes another file (`Header.dc.html`).
- `<x-import component-from-global-scope="ImpeccabyteDesignSystem_14a6d0.Button" ...>` is a design system component. The attributes are its props. Rebuild each as a Twig component: Button, Badge, Input, Select, Checkbox, Tabs, Accordion, Callout.
- `<div class="dp-tag">` blocks are annotations naming the Drupal paragraph type or region for that section. They are guidance, not page content. Do not render them.
- Files reference `ds/impeccabytedesignsystem_14a6d0/...` and `support.js`. Those are the design tool's runtime and are not included. The token values you need are in the theme's `css/tokens.css`.
