# To confirm before launch

Claims and placeholders on the built pages that need a yes, a fix or a real value from Impeccabyte. Each line names the page, what the page says now, and what to check. Started 2026-11-19; add to it as pages are built.

Site-wide items that were already open (phone, address, rates, contract terms, testimonials, legal pages, Intercom token and so on) stay in the vault hub's "Open before launch" list.

## Across the site

- **Logos.** Permission and usage rules for every third-party logo shown: the six partner logos in the Home strip, the three gateway logos on Online, and the nine platform logos on Integrations (DoorDash, Uber Eats, Grubhub, Shopify, WooCommerce, BigCommerce, Magento, QuickBooks, Xero). Sources are in each `scripts/content/assets/*logos/README.md`.
- **Photos.** Every page photo is a generated illustration (Higgsfield). Replace with real photography when there is some. Screens show sample amounts only on Online ($420.00 invoice, $48.00 checkout, card ending 4242).
- **Log in link.** Points at `app.impeccabyte.com`; the merchant app MVP is at `pos.impeccabyte.com`. Confirm which.

## Pricing (`/pricing`)

- **Keyed and online rate on Simple.** Raised on 2026-11-22 from 2.99% + 25¢ to 3.29% + 30¢, because Maverick's sponsor banks charge more on keyed and online sales than the pricing playbook assumed. Update the playbook to match, or say which rate to show.
- **Specialty industries.** A new Pricing FAQ says specialty industries are quoted one by one, and each specialty page's "Are there extra fees?" answer says their rates are not taken from the standard programs. Confirm that is how you will quote them.
- **Contract and cancellation.** Resolved 2026-11-23: the term and cancellation policy are spelled out in each formal quote and are not published online. The FAQ "Is there a contract or cancellation fee?" now says so and states no term or fee.

## On the go (`/solutions/on-the-go`)

- **Tap to pay on your phone.** Offered as a setup option. Confirm it is available on Impeccabyte accounts. Resolved 2026-11-23: it works on most smartphones, iPhone and Android, and the FAQ now says so. Setup steps are not listed on the site.
- **Cellular.** "Wi-Fi or cellular, depending on the model" for handhelds, and "a cellular data plan, which we can add". Confirm a data plan can be added.

## Recurring billing (`/solutions/recurring-billing`)

- **Card account updater.** The hero and the Home salon chip no longer say "included", because the pricing playbook prices the updater as an add-on ($39.95/mo, 50¢ per update, $187.95 setup; shown on Pricing under "Other fees"). The Online page footnote still says Maverick's gateway "comes standard" with a card account updater, from Maverick's materials. Confirm which is right for your merchants.
- **Receipts.** "The gateway ... emails your customer a receipt."
- **Dashboard.** "Change an amount, a date or a card from the gateway dashboard" and "See every scheduled and declined payment in one report."

## Dual pricing (`/solutions/dual-pricing`)

- **Surcharging column.** States card network rules plainly: debit cards cannot be surcharged, networks cap the fee, some states limit surcharging. Confirm the wording is acceptable (a "not legal advice" line sits under the table).
- **Online.** "Works in store and online", based on Maverick's FAQ. Confirm for Impeccabyte accounts.

## Cash discount on Clover (Home, `/industries/food-trucks`)

Added 2026-11-23. Clover supports a cash discount, not dual pricing, so the Clover setups now say cash discount (`scripts/content/98-clover-cash-discount.php`). Cash Price (dual pricing) stays only beside Dejavoo and Valor.

- **Price.** The pricing playbook has no cash discount program, so the Home setup lines and the Food Trucks add-on show no price. Say what a Clover cash discount costs merchants, or whether it sits under one of the three programs.
- **How it works.** "Your posted prices are your card prices, and customers who pay cash get a discount at checkout" (Food Trucks FAQ), from Maverick's "true cash discount" description. Confirm with Maverick, and have counsel check the wording and any signage it needs.
- **Setup.** "We set it up with you." Confirm whether Maverick support enables it or the merchant turns on Clover's Cash Discount app.

## Interchange plus (`/solutions/interchange-plus`)

- **Markup.** "Your markup is in your agreement in writing. If anything about your pricing ever changes, we tell you first."
- **Monthly fees.** Gateway and PCI compliance are named as example fees. Confirm both apply.

## Chargeback protection (`/solutions/chargeback-protection`)

- **Responding.** "You send your response through the dashboard, and we help you decide what to include."
- **Cost.** "Alerts and dashboard tools come with your account. Any chargeback fees or add-on tools are listed in your quote."
- **Ratio.** The callout promises "we'll reach out if it starts to rise." Keep only if someone will watch each merchant's ratio.
- **Not shown.** Third-party alert services that integrate with Maverick (Chargeback.io, Chargeflow and others). Add if Impeccabyte offers them.

## Integrations (`/solutions/integrations`)

Rewritten on 2026-11-22 to name only what Maverick's knowledge base supports (see `docs/maverick-offerings.md`, section 11).

- **Delivery apps.** "On Clover's restaurant plans, DoorDash, Uber Eats and Grubhub connect straight to your POS." Confirm which Clover plan you would sell for it.
- **Shopify.** Through OpenPath or Checkout Champ. "Shopify may charge an extra fee on sales that don't use Shopify Payments."
- **Accounting.** QuickBooks, Xero and FreshBooks through Chargezoom, Biller Genie and RECUR360, and QuickBooks Online sync in the Valor and iPOSPays portals.

## Funding (`/solutions/funding`)

- **Batch close.** "Most terminals let you set an automatic close time."
- **Fees.** "On some accounts, daily fees" come out of deposits.
- **Deposit account changes** "need a written request from the account owner."

## Restaurants and bars (`/industries/restaurants`)

- **Customer story.** Removed on 2026-11-23 until a real, approved story is supplied.
- **Kitchen display.** Bundle seed price for the Clover KDS 14-inch ($40/mo, $839) is wireframe seed content.

## Food trucks (`/industries/food-trucks`)

- **Data plan.** "A cellular data plan, which we can add" (also listed as a setup add-on).
- **Online ordering** listed as a setup add-on.

## Retail shops (`/industries/retail`)

- **Loading products.** "We help you get your items onto the POS, usually from a spreadsheet." Keep only if Impeccabyte will do this.
- **Barcode scanner** seed price $10/mo (from the Home page wireframe).
- **Add-ons.** Receipt printer, cash drawer and online checkout.

## Salons and spas (`/industries/salons-and-spas`)

- **Tips by stylist.** "Each stylist logs in to take payments, so tips are recorded under their name."
- **Gift cards** listed as a setup add-on.
- **No-show fees.** The FAQ says Impeccabyte helps word the cancellation policy. Confirm you want to offer that.

## Home services (`/industries/home-services`)

- **Accounting sync.** "Payments sync to your accounting software, so invoices are marked paid without retyping." Depends on the gateway or app chosen.
- **Reports by tech.** "Every payment lands in the same reports, by tech and by job." Confirm reports can split by tech and job on the setup you would sell.
- **ACH** listed as a setup add-on, through the virtual terminal.

## Healthcare and dental (`/industries/healthcare`)

- **HSA and FSA cards.** "Most HSA and FSA cards run on the major card networks and are taken like any other card for eligible services." Confirm for Impeccabyte healthcare accounts.
- **Card on file.** "With the patient's written permission ... stored as a token by the gateway, not in your files or practice software."
- **Practice software.** "Many practice management systems connect to a payment gateway." Confirm which ones you would check against.
- **No compliance claims.** The page deliberately says nothing about HIPAA. Add wording only if Impeccabyte or Maverick can back it.

## Auto repair (`/industries/auto-repair`)

- **Dual pricing on repairs.** The page promotes dual pricing for big tickets, as the Home page's auto chip does. Confirm it fits your auto clients.
- **Shop software.** "Many shop management systems connect to a payment gateway." Confirm which ones you would check against.
- **Fleet accounts.** Invoices by email with card or ACH; ACH is listed as a setup add-on.

## Professional services (`/industries/professional-services`)

- **ACH cost.** "It can cost less on large invoices." Confirm with your ACH pricing.
- **Legal trust accounts.** Deliberately not mentioned. Law firms with client trust accounts have their own rules; add wording only if Impeccabyte supports them.

## Setup panel without hardware (all industry pages)

- A setup made only of solutions (Professional services today, and any future one) now shows "Your quote: Every fee in writing before you sign." instead of a hardware estimate. Confirm the wording.

## Nonprofits (`/industries/nonprofits`)

- **Nonprofit pricing.** The FAQ answers "Do you offer special pricing for nonprofits?" by saying you compare programs on their statement. If Maverick has a nonprofit rate or program, name it there.
- **Donor receipts.** "Your own acknowledgment letters for tax purposes still come from your organization." Payment receipts only; no tax-receipt claims.
- **Text-to-give links** and ACH gifts listed as setup add-ons.

## Pop-ups and markets (`/industries/pop-ups-and-markets`)

- **Tap to pay on your phone.** Offered as a backup and a setup add-on; same open question as On the go.
- **New businesses.** "Many vendors start with us before their first market." Confirm you take brand-new businesses.
- **Flat-rate pricing** named as the starting program, as on the Home page's pop-up chip.

## Specialty industries (`/industries/specialty` and its twelve pages)

- **Five verticals still placeholders.** Firearms, Dating, Collections, Credit repair and Lending say "[Requirements to be supplied from the Maverick addendum.]" until their addenda are reviewed.
- **Hub steps.** "We review your website ... and tell you what to fix first" and "We list the documents your industry needs." Confirm Impeccabyte does this review before every specialty application.
- **Imagery.** The hub and all twelve vertical pages now have PG photos of people at counters, desks and offices (no products in focus, no smoke, no weapons, nothing suggestive). Ask for a swap if any vertical should stay text-only.
- **Good-standing habits.** Each vertical now lists four habits after approval: a recognizable billing name, a clear refund and cancellation policy, answering disputes quickly with dashboard alerts, and "Tell us before you add new products or websites." Confirm the last one is how you want merchants to handle changes.

## About us (`/about`)

- **Founder story.** Written up from the founder's own words (2026-11-21), with 2025 as the founding year. It names the family business, Luxury On A Leash, and ends "fees that never come as a surprise". Confirm both are fine to publish. It is signed "Ryan Bealey, Founder".
- **Founder photo.** Resolved 2026-11-23: the photo beside the founder story is now Ryan's moms on a boat with their dog, Olivia (Livvy), supplied by Ryan in place of the portrait. It is 960 x 720, so it can look slightly soft at tablet width; a larger original would fix that (add it to `scripts/content/assets/about` under a new file name, set `$filename` in `98-founder-photo.php` and run `scripts/content/98-founder-photo.php`).
- **Contact details.** Resolved 2026-11-23: no business hours are listed (the About us hours line is gone; the "Mon to Fri, 8 to 6 Central" setting only shows next to a phone number, and there is none). the mailing address is 17350 State Hwy 249, Ste 220 #37005, Houston, TX 77064, for mail only, and no copy invites a visit. There is no company phone number: the header, menus, quote page, footer, About us and Accessibility show howdy@impeccabyte.com instead, and copy no longer asks people to call.
- **Service area.** Resolved 2026-11-23: we take on clients outside Central Texas, with optional on-site support for a fee. The FAQ "Do you only work with Austin businesses?" now says so; no amount is stated, and the fee is listed on the quote. Confirm the on-site fee amount before it appears anywhere.
- **Reply time.** "A local specialist replies within one business day" appears in the FAQ and closing band, as on other pages.
- **Application help.** "We help you through the application" and "help with the application" describe our role. Confirm this matches what you do.
- **Who does what.** The sponsor banks card says Maverick and its sponsor banks "review and approve each application," and the Maverick card says "Your agreement is with them." Confirm both against the Merchant Agreement.
- **Photos.** The hero (a specialist and a cafe owner at a terminal) and the street of storefronts are generated illustrations, not your team or premises.

## Hardware (`/hardware` and the 26 device pages)

- **Prices for the Maverick lineup.** The 14 devices added from Maverick's lineup were priced on 2026-11-22 to match the pricing playbook's Reg tier (`scripts/content/96-hardware-prices.php`). Confirm them, and whether Clover Go stays at $209.
- **Lineup from Maverick.** The catalog now lists every payment terminal in Maverick's agent knowledge base (kb.maverickpayments.com, read 2026-11-19): the 12 original devices plus Dejavoo QD2, QD3, P1, P3, P5, P8 and P18, Valor VP100, VP800 and VL550, PAX A80 and A35, Fiserv FD150 and Clover Flex Pocket. Descriptions, specs, industries and pricing programs for the non-Clover devices come from the knowledge base; its terminal costs and placement program levels were not used.
- **Not added.** The Equinox terminal (the knowledge base does not name the model and recommends it for lodging only), the Clover Kitchen Display System (14 and 24 inch, not a payment terminal), the Quantic, DejaPayPro and TouchBistro POS platforms, and tap to pay on a phone (iPOSGO, SwipeSimple Tap to Pay on iPhone). Say which of these you want on the site.
- **Corrected.** Valor VP550 was listed as a countertop terminal; the knowledge base calls it an Android mobile terminal on Wi-Fi and 4G, so it is now a handheld, and the Home auto repair chip, the In person countertop examples and the Auto repair setup no longer call it a countertop (In person now names the Valor VP100). PAX A920 Pro was marked dual pricing ready; the knowledge base lists PAX for surcharging only, so the badge is gone and its page says surcharging.
- **Dual pricing page.** Its device grid follows the "Dual pricing ready" flag, so it now shows 13 devices (all Dejavoo and Valor) instead of 4. Say if it should show a shorter, picked list.
- **No prices on new devices.** The 14 new devices have no lease or buy amounts. They show "Pricing coming soon" like every device while "Show prices" is off, but need amounts before prices go live.
- **Clover specs still missing.** The knowledge base has no Clover spec sheets, so the Clover devices other than Mini still have no spec table. Clover Mini's printer row now reads "Built-in thermal receipt printer" (clover.com/mini, 2026-11-23).
- **Product photos.** Every device shows official images from its manufacturer's website (Clover, Dejavoo, PAX, Valor, SwipeSimple, Fiserv), trimmed and centred on one transparent canvas. Sources are in `scripts/content/assets/devices/README.md`. Confirm that your agreements allow using manufacturer marketing images.
- **Details written from existing copy.** Summaries for the original Clover devices reuse what the site already says. Two are worth a check: Clover Station Duo "one screen for your team and one facing your customer", and Clover Kiosk "customers order and pay on their own". Clover Flex Pocket's line ("lighter, without a built-in printer") comes from clover.com.
- **Lease and buy terms.** Clover devices say the starter kit and 3-year protection are included, as on Pricing. The other devices only say "Month to month" and "One payment and the device is yours" until their terms are confirmed.
- **Compatible gateways.** All Clover devices show the Clover gateway section. The other devices have no gateway section yet; confirm which gateways each supports.
- **PAX A920 Pro.** PAX now sells the A920Pro Duo (PCI 7) as its current model; Maverick's knowledge base still lists the A920 Pro.

## Travel (`/travel` and `/travel/hotel-perks`)

- **Photos.** The three "Trips we plan" photos (business travel, family trips, celebrations) are generated illustrations, not clients or real hotels. Swap them for real photography when there is some.
- **Loyalty points.** The pages say clients can use the loyalty points from their favorite hotel and travel programs (Ry, 2026-11-24). They do not say clients earn points or keep elite benefits on bookings; confirm with Fora before adding that.
- **Resolved 2026-11-24:** no planning fee, and Fora Travel's registrations and licenses (California, Florida, Washington, British Columbia, Ontario, United Kingdom) are in the disclosure on both pages.
