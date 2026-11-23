# Content audit

What still needs work on the site's content, from a crawl of every published page on 2026-11-19 (80 pages: 26 devices, 23 industry pages, 9 solutions, 10 resources, 8 landing pages and the hardware, resources, quote and compare pages) plus a check of the content data behind them.

Each item says where it is, what is there now and who has to act: **Supply** (a real value, text or file from Impeccabyte), **Decide** (a yes, no or choice), **Confirm** (a claim on the page that needs a yes or a fix) or **Fix** (work that can be done in the build without new input).

Claim-by-claim questions for each page are in [`to-confirm.md`](to-confirm.md); this list summarizes them in section 4 rather than repeating them. What Maverick actually offers, and where the site's claims differ from it, is in [`maverick-offerings.md`](maverick-offerings.md).

## 1. Blocks launch

Resources were taken offline on 2026-11-21, so items 1.8, 1.9, 2.5 and 2.11 no longer block launch.

These are visible on public pages today as placeholders, or present invented content as real.

| # | Item | Where | Now | Action |
|---|---|---|---|---|
| 1.1 | **Office address and phone** | Footer of every page (79 pages), About, quote page | `[Street address], Suite [XXX]`, `Austin, TX 787[XX]`, `(512) [XXX-XXXX]` | **Supply.** Set once in Site settings (`/admin/config/system/impeccabyte`); About's "Find us" also shows `[Phone number]` and `[ZIP]`. |
| 1.2 | **Privacy policy** | `/privacy` | "[Privacy policy to be supplied and reviewed by counsel before launch.]" | **Supply**, reviewed by counsel. |
| 1.3 | **Website terms of use** | `/terms` | "[Website terms of use to be supplied and reviewed by counsel before launch.]" | **Supply**, reviewed by counsel. |
| 1.4 | **Accessibility statement** | `/accessibility` | "[Formal accessibility statement to be reviewed before launch.]" | **Supply** or approve a draft. |
| 1.5 | **Home testimonial and savings** | Home | Labelled "Example quote": `[Merchant name], owner [Business name]`, monthly fees "Before $[X,XXX] After $[XXX]" | **Supply** a real, approved quote and figures, or **Decide** to remove the block. |
| 1.6 | **Quote page testimonial** | `/get-a-quote` | Labelled "Example quote": `[Merchant name], [Business name] · North Loop` | **Supply** or remove. |
| 1.7 | **Restaurants customer story** | `/industries/restaurants` | "Example story", `[Restaurant name]`, `[XX]-seat [cuisine]`, `[X]%`, `[X] min`, "Photo to come" | **Supply** a real, approved story, or remove the section. |
| 1.8 | **Boutique customer story** | `/resources/boutique-switching-to-dual-pricing` | "[Customer story to be supplied. Publish only a real story the customer has approved.]" | **Supply** or unpublish. **Parked 2026-11-21:** Resources are offline (unpublished, links removed; see `scripts/content/95-hide-resources.php`). Needed only if Resources come back. |
| 1.9 | **Resource articles** | 9 of 10 resources: first merchant account, processing statement, application checklist, disputes, Clover Flex first payment, funding times, interchange terms, payment links vs online store, business bank account | Intro only, then "[Article copy to be supplied.]" | **Supply** the articles (or approve drafts), or unpublish the ones not ready. **Parked 2026-11-21:** Resources are offline (unpublished, links removed; see `scripts/content/95-hide-resources.php`). Needed only if Resources come back. |
| 1.10 | **Five specialty verticals** | `/industries/specialty/` firearms, dating, collections, credit-repair, lending | "[Requirements to be supplied from the Maverick addendum.]" | **Done 2026-11-22.** Requirements written from the Maverick addenda and credit matrix by `scripts/content/88-specialty-requirements.php`, which also added addendum requirements missing from the other seven pages. Summary in [`maverick-offerings.md`](maverick-offerings.md), section 10. |
| 1.11 | **About: business hours** | `/about` | The founder story, founding year (2025), name (Ryan Bealey) and photo were all supplied 2026-11-21 (`scripts/content/97-founder-story.php`, `98-founder-photo.php`). Still a placeholder: `[Business hours]` (see 2.9) | **Confirm** the hours. |
| 1.12 | **Service area** | `/about` FAQ | "[Confirm: whether we take on businesses outside Austin and Central Texas.]" | **Decide.** |
| 1.13 | **Term and cancellation policy** | `/pricing` | "[Confirm current term and cancellation policy.]" | **Confirm** against the Merchant Agreement. |
| 1.14 | **Tap to pay phones** | `/solutions/on-the-go` FAQ | "[Supported phones and setup steps to be confirmed.]" | **Resolved 2026-11-23:** most smartphones, iPhone and Android; the FAQ now says so and lists no setup steps. Still **confirm** which tap to pay product we offer. Maverick documents three (iPOSGo, SwipeSimple Tap to Pay on iPhone, Clover Go); see [`maverick-offerings.md`](maverick-offerings.md), section 11. |
| 1.15 | **Clover Mini printer spec** | `/hardware/clover-mini` | "[Confirm with current Clover spec sheet]" | **Confirm** or remove the row. |
| 1.16 | **Pricing approvals** | Pricing, Dual pricing, every device page | Merchant prices from the pricing playbook are loaded but "Show prices" stays off in the exported config. The playbook requires Maverick's written approval of all merchant-facing materials and a counsel review of the dual pricing signage and disclosure wording. | **Supply** both sign-offs, then turn "Show prices" on and export config (README, "Prices"). |

## 2. Content gaps

Nothing is broken here, but the pages are thinner or less accurate than they should be.

| # | Item | Where | Now | Action |
|---|---|---|---|---|
| 2.1 | **Hardware prices** | 20 of 26 devices | Reg-tier prices from the pricing playbook are confirmed for 6 devices (Clover Mini, Flex, Station Duo, Kiosk, PAX A920 Pro, Valor VP550; all matched the seeds). Six keep wireframe seed prices the playbook does not cover (Clover Station Solo, Compact, Go, Dejavoo Z8, QD4, SwipeSimple B350), as do the Clover KDS 14-inch ($40/mo, $839) and barcode scanner ($10/mo) in the Restaurants and Retail bundles, and the Home "$10/mo" stat. The 14 devices added from Maverick's lineup were given Reg-tier prices on 2026-11-22 to match the playbook (`scripts/content/96-hardware-prices.php`). | **Confirm** the 6 seeded prices, the two bundle items and the 14 prices set on 2026-11-22. |
| 2.2 | **Clover spec tables** | 9 Clover devices (all but Mini) | No spec table; Maverick's knowledge base has no Clover specs | **Supply** spec sheets, or **Decide** to pull specs from clover.com. |
| 2.3 | **Device lease and buy terms** | 18 non-Clover devices | Generic: "Month to month." and "One payment and the device is yours." | **Supply** real terms (protection, what is in the box). |
| 2.4 | **Compatible gateways** | 18 non-Clover devices | No gateway section | **Supply** which gateways each supports. |
| 2.5 | **Resource illustrations** | `/resources` and every resource card | No thumbnails; cards show "Illustration to come" | **Fix**: add images, or **Decide** on a text-only card. **Parked 2026-11-21:** Resources are offline (unpublished, links removed; see `scripts/content/95-hide-resources.php`). Needed only if Resources come back. |
| 2.6 | **Equinox, Kitchen Display, POS platforms, tap to pay** | Hardware catalog | Available through Maverick but not listed | **Decide** which to add (details in `to-confirm.md`, Hardware; the full Maverick lineup is in [`maverick-offerings.md`](maverick-offerings.md), section 3). |
| 2.7 | **Dual pricing device grid** | `/solutions/dual-pricing` | Now shows all 13 dual-pricing devices instead of 4 | **Decide** whether to keep the full list or pick a few. |
| 2.8 | **"40+" devices stat** | Home stats band | "40+ Terminals, readers and POS systems we can set up" while the catalog lists 26 | **Confirm** the number (Maverick also integrates other POS systems) or change it to match the catalog. |
| 2.9 | **Business hours disagree** | Quote page and site settings vs About | The quote page and Site settings say "Mon to Fri, 8 to 6 Central"; About says `[Business hours]` | **Confirm** the hours, then set both from Site settings. |
| 2.10 | ~~**Example receipt rate**~~ | `/solutions/dual-pricing` | The labelled "Example receipt" shows a 4% non-cash adjustment | **Done** 2026-11-21: 4% is the playbook's standard card price adjustment. |
| 2.11 | **Business bank account article** | `/resources/business-bank-account` | "Why your business needs its own bank account" | **Decide.** The content rules say no business banking in copy. The article is about opening a bank account elsewhere for deposits, not offering one, but it should be checked against that rule. (The three other "business bank account" mentions only say where deposits land, which is fine.) **Parked 2026-11-21:** Resources are offline (unpublished, links removed; see `scripts/content/95-hide-resources.php`). Needed only if Resources come back. |

## 3. Site-wide and technical content

| # | Item | Where | Action |
|---|---|---|---|
| 3.1 | ~~**Meta descriptions missing**~~ | `/hardware`, `/resources`, `/get-a-quote`, `/privacy`, `/terms`, `/accessibility` | **Done** 2026-11-19: all six have a search and Open Graph description (`scripts/setup/85-meta-descriptions.php`, `scripts/content/94-legal-meta.php`). The Privacy and Terms descriptions describe what those pages say now; review them when the real policies arrive. |
| 3.2 | **Photos are illustrations** | Every page photo except devices and logos | Generated illustrations, not Impeccabyte's people or premises. **Supply** real photography over time. |
| 3.3 | **Manufacturer photos** | 26 devices | Official manufacturer images (sources in `scripts/content/assets/devices/README.md`). **Confirm** your agreements allow using them. |
| 3.4 | **Third-party logos** | Home partner strip, Online gateways, Integrations | **Confirm** permission for each (listed in `to-confirm.md`, Across the site). |
| 3.5 | **Log in link** | Header and footer | Points at `app.impeccabyte.com`; the merchant app is at `pos.impeccabyte.com`. **Confirm** which. |
| 3.6 | **Lead handlers off** | All forms | Submissions save in Drupal only. Intercom needs its access token; the Apollo handler has no account yet. **Supply** the token when ready. |
| 3.7 | **Fonts and hardening** | Whole site | Fonts load from Google; the README's "Before this goes anywhere public" list (HTTPS, backups, mail, file permissions) is still open. **Fix** before going live. |

## 4. Claims to confirm (from `to-confirm.md`)

74 open questions about what the pages promise. Grouped by page, with the count of items in each:

| Page | Items | Main topics |
|---|---|---|
| Hardware and devices | 11 | Lineup choices, corrections made from Maverick's knowledge base, prices, terms, gateways, photos |
| About us | 8 | Founder story, office visits, reply time, application help, who approves applications |
| Specialty industries | 4 | Five addenda, review steps, imagery, good-standing habits |
| Chargeback protection | 4 | Responding through the dashboard, cost, ratio monitoring, alert services |
| Integrations | 4 | Delivery apps, accounting sync, Shopify fee, ERPs |
| Healthcare and dental | 4 | HSA and FSA cards, card on file, practice software, no HIPAA claims |
| Recurring billing, Funding, Retail, Salons, Home services, Auto repair, Nonprofits, Pop-ups | 3 each | Feature claims, add-ons, seed prices |
| Across the site | 3 | Logos, photos, log in link |
| On the go, Dual pricing, Interchange plus, Restaurants, Food trucks, Professional services | 2 each | Tap to pay, cellular plans, surcharging wording, markup, customer story, ACH |

## 5. Checked and fine

- **Links and images:** all 121 internal links and 137 images resolve; no 404s.
- **Disclosure:** the footer disclosure appears with the exact required text on every page.
- **Accessibility basics:** every page has one h1, headings never skip a level, and every image has alt text (empty only where decorative).
- **Voice rules:** no em dashes and no emoji in page text.
- **Promises:** no "guaranteed approval", "no reserves" or same-day or next-day funding claims; funding copy says "cutoffs vary by processor".
- **Prices in text:** no product or service price appears in page text; every price renders as "Pricing coming soon" while "Show prices" is off. The only dollar amounts are the quote form's sales ranges and sample amounts on mock screens (the Home dashboard and the Dual pricing example receipt). Wireframe seed prices remain in hidden price notes on the Home industry chips and some bundles.
- **Compare page:** `/hardware/compare` loads with one h1.
- **Pricing on and off (2026-11-21):** with "Show prices" on, every dollar and percent amount on the site traces to the pricing playbook, a seed price listed in 2.1, an amount calculated from those (bundle totals, the savings estimator) or a sample amount on a mock screen. With it off, every price disappears; only the mock screens, the example receipt and the $15,000 volume threshold remain.
