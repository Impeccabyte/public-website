# Impeccabyte marketing site: build brief

This brief turns the approved wireframes into a Drupal 11 site. It covers the content model, how each screen maps to Drupal, the build phases and the rules every page must meet.

Source of design: `design/wireframes/` (see `design/README.md` for how to read the files).

## 1. Scope

Nine screens, fully responsive (phone 390px, tablet 834px, desktop 1440px):

1. Home
2. Solutions mega menu (part of the global header)
3. Solution detail (dual pricing is the model)
4. Hardware catalog
5. Device detail (Clover Mini is the model)
6. Industry landing (restaurants is the model)
7. Pricing
8. Resource library
9. Get a quote (three-step form)

Plus the global header (mega menu, mobile drawer, "Log in" link to the merchant app, "Get a quote" button) and the global footer (four link columns, newsletter signup, Austin address, disclosure line).

Out of scope: the merchant app behind "Log in", payment processing, CRM integration, production hosting.

## 2. Content model

### Taxonomies

| Vocabulary | Machine name | Used by | Seed terms |
|---|---|---|---|
| Device type | `device_type` | Device | Countertop terminal, Handheld, Mobile reader, POS system, Self-order kiosk |
| Sales channel | `sales_channel` | Device | In store, Tableside, On the go, Online and in person |
| Industry | `industry` | Device, Industry page, Resource | Restaurants and bars, Food trucks, Retail shops, Salons and spas, Home services, Healthcare and dental, Auto repair, Professional services, Nonprofits, Pop-ups and markets |
| Manufacturer | `manufacturer` | Device | Clover, Dejavoo, PAX, Valor, SwipeSimple |
| Solution category | `solution_category` | Solution | Accept payments, Lower your costs, Run your business |
| Resource type | `resource_type` | Resource | Guides, Checklists, Videos, Customer stories, Glossary |
| Resource topic | `resource_topic` | Resource | Getting started, Pricing and fees, Hardware, Online payments, Chargebacks, Funding, Banking |

### Content types

| Type | Machine name | Key fields |
|---|---|---|
| Landing page | `landing_page` | `field_sections` (paragraphs, unlimited). Used for Home and Pricing. |
| Solution | `solution` | Category, short description (for menus and cards), icon name, `field_sections` |
| Industry page | `industry_page` | Industry term, `field_sections` |
| Device | `device` | Manufacturer, device type, sales channels, industries, summary, gallery (media, multiple), lease price per month, buy price, in-stock flag, dual-pricing-ready flag, key features (text, multiple), specs (paragraph `spec_row`, multiple), monthly fees (paragraph `fee_row`, multiple), compatible gateways (paragraph `gateway`, multiple), gateway note (text), documents (media document, multiple), related devices (device reference, up to 4) |
| Resource | `resource` | Type, topic, summary, thumbnail (media), read time or format label, body, featured flag, file (media document, optional) |

Prices are plain number fields. Templates format them as "From $X/mo" and "Buy from $X". Do not hard-code prices in Twig.

### Paragraph types

Each section in the wireframes has a blue dashed tag naming its paragraph type. Build one paragraph type and one theme component for each.

| Paragraph | Machine name | Fields | Seen on |
|---|---|---|---|
| Hero | `hero` | Variant (split with media, centered, compact, compact with search, image right), eyebrow, headline, accent word (rendered italic clay), lead, primary link, secondary link, checklist items, media, show breadcrumb | Every page |
| Logo strip | `logo_strip` | Intro text, logos (media or text, multiple) | Home |
| Feature grid | `feature_grid` | Eyebrow, heading, intro, cards (`feature_card`: icon, title, body, link, highlight flag) | Home, Industry |
| Chip links | `chip_links` | Eyebrow, heading, items (industry reference with setup lines) | Home |
| Card grid | `card_grid` | Eyebrow, heading, intro, link, source (manual device list, or filter such as dual pricing ready), footnote | Home, Solution, Device |
| Stat row | `stats` | Items (`stat`: value, label), up to 4 | Home |
| Testimonial | `testimonial` | Quote, name, role and business, before and after figures, link, placeholder flag | Home, Quote sidebar |
| Steps | `steps` | Eyebrow, heading, items (`step`: title, body), numbered automatically | Solution |
| Comparison table | `comparison_table` | Eyebrow, heading, column headers, rows (label plus one cell per column), highlighted column | Pricing |
| Savings estimator | `savings_estimator` | Eyebrow, heading, intro, assumptions text, rate inputs (flat rate percent and cents, interchange plus percent and cents, average ticket, monthly dual pricing fee) | Solution |
| Pricing cards | `pricing_cards` | Cards (`pricing_card`: name, badge, description, starting-at value, unit, bullets, link, featured flag), footnote | Pricing |
| Callout | `callout` | Variant (info, success, warning, danger, brand), title, body | Solution, Device |
| FAQ accordion | `faq` | Eyebrow, heading, intro, items (`faq_item`: question, answer). Outputs FAQPage structured data. | Most pages |
| Recommended setup | `bundle` | Eyebrow, heading, intro, items (`bundle_item`: role label, device or solution reference, note, quantity), add-on labels, estimate note | Industry |
| Case study | `case_study` | Eyebrow, heading, body, image, metrics (value and label, up to 3), link, placeholder flag | Industry |
| Related resources | `related_resources` | Heading, resources (reference, up to 3) | Solution |
| CTA band | `cta_band` | Variant (clove), headline, accent word, body, primary link, secondary link | Most pages |
| Embedded form | `webform_embed` | Eyebrow, heading, intro, steps list, webform reference | Pricing |

### Views

| View | Shows | Notes |
|---|---|---|
| `hardware_catalog` at `/hardware` | Device cards | Search API (database backend) index of devices. Facets as checkboxes: device type, sales channel, industry, manufacturer, each with counts. Exposed sort: recommended, monthly low to high, price low to high, newest. Result count line. "Show more" pager. On phones the filter rail collapses behind a "Filters" button. |
| `resource_library` at `/resources` | Resource cards | Exposed filters for type and topic rendered as chips (Better Exposed Filters), keyword search, featured resource block above the grid, "Load more" pager. |
| `devices_by_filter` (block) | Device cards | Feeds the card grid paragraph when its source is a filter. |

### Webforms

| Form | Machine name | Details |
|---|---|---|
| Get a quote | `get_a_quote` at `/get-a-quote` | Three wizard pages with a progress bar. Page 1, business info: business name, type of business, how long open, ZIP code, monthly card sales range. Page 2, how you take payments: checkboxes (in store, online, on the go, recurring billing), current processor, interests (dual pricing, new hardware, chargeback protection, business banking). Page 3, contact: first name, last name, email, mobile phone, preferred contact method (text, call, email), consent checkbox. Confirmation message with a link to upload a statement. Reassurance sidebar block beside the form. |
| Statement review | `statement_review` | Name, business name, email, phone (optional), how you take payments, file upload (PDF, JPG, PNG, 10 MB limit, private file system), consent checkbox. |
| Newsletter | `newsletter` | Email only. Rendered in the footer. |

Store uploads in the private file system. Submissions go to the Drupal database for now; email delivery and CRM handoff are later decisions.

### Menus and blocks

- **Main menu**: Solutions, Hardware, Industries, Pricing, Resources, Company. Solutions children are grouped under three headings (the solution categories) and each child shows a short description and an icon. Render the mega menu from the menu tree in a custom Twig template; add a description and icon field to menu links (Menu Item Extras, or a small custom module if that module is not available for this Drupal version).
- **Mega menu featured card**: a custom block type (badge, title, body, link) placed in the mega menu.
- **Footer menus**: four menus (Solutions, Hardware, Industries, Company).
- **Footer blocks**: newsletter form, address and contact, disclosure line, legal links.
- **Compare tray**: custom module `impeccabyte_compare`. A "Compare" toggle on each device card adds up to 3 devices, held in `sessionStorage`. A sticky tray on the catalog lists them with remove buttons and a "Compare devices" button that is disabled below 2. The comparison page itself is not designed yet; link to `/hardware/compare?ids=` and render a simple table of the device fields.

## 3. Screen to Drupal mapping

| Screen | Drupal build | Sections, in order |
|---|---|---|
| Home | `landing_page` node, front page | Hero (split with media), Logo strip, Feature grid (6 cards), Chip links (industry picker), Card grid (4 featured devices), Stat row, Testimonial, FAQ, CTA band |
| Solutions menu | Main menu and mega menu template | Three category columns with description links, featured card, help bar with "Talk to us" and phone |
| Solution detail | `solution` node | Hero with breadcrumb, Steps, Savings estimator, Card grid (dual pricing ready devices), Callout (warning), FAQ, Related resources, CTA band |
| Hardware catalog | View page | Hero (compact), then the view: filter rail, sort, result count, card grid, compare tray |
| Device detail | `device` node, full display | Breadcrumb, gallery with thumbnails, badges, title, summary, lease or buy choice cards with the fee list, buttons, feature checklist, specs table, compatible gateways with note, documents, related devices |
| Industry landing | `industry_page` node | Hero (image right), Feature grid (pain points), Recommended setup with estimate card, Case study, FAQ, CTA band |
| Pricing | `landing_page` node | Hero (centered), Pricing cards, Comparison table, Embedded form (statement review), FAQ |
| Resource library | View page | Hero (compact with search), filter chips, featured resource, card grid |
| Get a quote | Webform page | Heading, three-step form, reassurance sidebar |

## 4. Theme components

Build these as single directory components in `web/themes/custom/impeccabyte/components/`. The first eight mirror the design system.

| Component | Variants and props |
|---|---|
| `button` | Variants primary (fill `--clay-600`, hover `--clay-700`, press `--clay-800`), secondary, ghost, accent, danger. Sizes sm (36px), md (44px), lg (52px). Block, disabled, icon left or right. Renders as `<a>` or `<button>`. Set text colour through a `--_fg` custom property so link hover rules cannot override it. |
| `badge` | Variants neutral, brand, accent, success, danger, warning, info. Solid, dot, size sm. |
| `card` | Padding sm, md, lg. Elevation flat, default, raised. Tone default, brand, dark. Interactive lift on hover. |
| `input`, `select`, `checkbox` | Label, hint, error, required. Label colour must adapt on dark surfaces. |
| `tabs` | Pill and underline. Arrow-key support. |
| `accordion` | Single open by default, optional multiple. Uses `<button aria-expanded>` and a labelled region. |
| `callout` | Variants info, success, warning, danger, brand. Optional title. |
| `chip` | Toggle chip with `aria-pressed`. Scrolls sideways in a row on phones. |
| `device-card`, `resource-card`, `feature-card`, `pricing-card` | As drawn |
| `choice-card` | The lease or buy cards on the device page: tablist behaviour, selected state shown by outline, fill and a check |
| `section-header` | Eyebrow, heading, intro, optional link |
| `site-header`, `mega-menu`, `mobile-drawer`, `site-footer` | As drawn |

Header behaviour: navigation collapses to the drawer below 1180px. The mega menu fades in and moves down 8px over 220ms and reverses on close; the chevron rotates; the drawer opens by height over 220ms. Menus close on Escape and on focus leaving. All motion is off under reduced motion.

## 5. Accessibility rules

These came out of the wireframe review and are required.

1. Text contrast is at least 4.5:1, or 3:1 for text 24px and up (18.66px and up if bold).
2. White text on `--clay-500` is 4.15:1. Use it only for bold labels 19px and up. For smaller white text on clay (small badges, step dots, checks) use `--clay-600`. **Decided:** the primary button fill is `--clay-600` (white label at 5.7:1), with `--clay-700` on hover and `--clay-800` on press. `--clay-500` stays the brand colour for the logo, accents and large display text.
3. Control outlines (chips, toggle buttons, option cards, checkboxes, upload area) are at least 3:1 against their surroundings. Use `--ink-400` or darker, and `--clay-600` for selected states. `--ink-200` is for decorative card borders only.
4. Focus ring: 2px `--clay-600` outline, 2px offset, with a white halo, so it shows on cream, white and clove. See `css/a11y.css`.
5. A button rendered as a link keeps the button's own text colour on hover and press.
6. Form labels on dark surfaces use `--text-on-dark`. The footer newsletter label is the known case.
7. Targets are at least 24px in both directions. Buttons, chips, menu items and form controls are 44px.
8. One `h1` per page. Section headings are `h2`, card titles `h3`. No skipped levels.
9. Every input has a visible label. Grouped options have a group label. Repeated links such as "Details" name their target for screen readers.
10. State is never shown by colour alone: selected chips and cards also change fill, outline or icon.
11. Wide tables scroll inside their own box on small screens. Nothing causes sideways page scroll at 390px.
12. Everything works by keyboard: menus, drawer, accordion, tabs, chips, gallery, compare tray, form steps.
13. Honour `prefers-reduced-motion`.
14. Decorative images and icons are hidden from assistive technology. Product photos have alt text.

## 6. Build phases

Run each phase's checks before moving on. Export config and commit at the end of each.

**Phase 1. Foundation.** Confirm the stack runs. Create taxonomies with seed terms, media types, text formats, the private file path, URL patterns (Pathauto) and the admin toolbar. *Check:* `./scripts/drush status` is clean; terms exist; config exports.

**Phase 2. Theme and global chrome.** Build the base components (button, badge, card, form controls, tabs, accordion, callout, chip, section header). Build the site header with mega menu and mobile drawer, and the footer with newsletter and disclosure. Create the menus with seed links. *Check:* a blank page shows header and footer correctly at 390, 834 and 1440px; menu and drawer open, close and work by keyboard; no sideways scroll.

**Phase 3. Paragraphs and landing pages.** Create every paragraph type in section 2 with its component. Create the `landing_page` type. Build Home and Pricing with the seed copy from the wireframes. *Check:* both pages match their wireframes section by section; one `h1`; headings in order.

**Phase 4. Devices and catalog.** Create the `device` type. Seed the 12 devices from `Hardware.dc.html` with prices as fields; give Clover Mini its full detail content from `Device-CloverMini.dc.html`. Build the device page, the Search API index, the catalog view with facets and sort, and the compare module. *Check:* facet counts match (for example Clover 7, Countertop terminal 4); sorting works; compare holds at most 3; the lease or buy cards switch.

**Phase 5. Solutions and industries.** Create `solution` and `industry_page`. Build the dual pricing page, including the savings estimator behaviour, and the restaurants page. Create stub pages for the other solutions and industries so menu links resolve. *Check:* the estimator matches the wireframe's numbers at $30,000 a month (flat rate about $1,230, interchange plus about $810, dual pricing $19.95); the restaurant setup totals $275 a month.

**Phase 6. Resources.** Create `resource`, seed the 10 items from `Resources.dc.html` (one featured), build the library view with chips and search. *Check:* filters combine; the count line updates; featured item shows above the grid.

**Phase 7. Forms.** Build the three webforms and place them. *Check:* the quote form validates per step, moves back and forward without losing answers, and stores a submission; the statement form accepts a PDF and rejects a file over the limit; uploads are not publicly reachable.

**Phase 8. Polish.** Metatags, sitemap, redirects, FAQ structured data, 404 and 403 pages, favicons. Run an accessibility pass against section 5 on every page at three widths. *Check:* list anything that does not meet the rules and anything still a placeholder.

## 7. Placeholders to resolve with the client

- Phone number, street address and suite, ZIP
- Processing rates for flat rate and interchange plus, and the "under $X a month" line in the pricing comparison
- Contract term and cancellation policy (pricing FAQ)
- Testimonial and case study names, businesses, quotes and figures (all placeholders today)
- Whether the Clover Mini has a built-in receipt printer (spec row says to confirm)
- Partner logos for the logo strip, with permission to use each
- Product photography for every device
- Where form submissions should go (email, CRM)
