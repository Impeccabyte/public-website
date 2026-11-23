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

## On the go (`/solutions/on-the-go`)

- **Tap to pay on your phone.** Offered as a setup option. Confirm it is available on Impeccabyte accounts, and which phones it supports. The FAQ still says "[Supported phones and setup steps to be confirmed.]"
- **Cellular.** "Wi-Fi or cellular, depending on the model" for handhelds, and "a cellular data plan, which we can add". Confirm a data plan can be added.

## Recurring billing (`/solutions/recurring-billing`)

- **Card account updater.** The hero and the Home salon chip no longer say "included", because the pricing playbook prices the updater as an add-on ($39.95/mo, 50¢ per update, $187.95 setup; shown on Pricing under "Other fees"). The Online page footnote still says Maverick's gateway "comes standard" with a card account updater, from Maverick's materials. Confirm which is right for your merchants.
- **Receipts.** "The gateway ... emails your customer a receipt."
- **Dashboard.** "Change an amount, a date or a card from the gateway dashboard" and "See every scheduled and declined payment in one report."

## Dual pricing (`/solutions/dual-pricing`)

- **Surcharging column.** States card network rules plainly: debit cards cannot be surcharged, networks cap the fee, some states limit surcharging. Confirm the wording is acceptable (a "not legal advice" line sits under the table).
- **Online.** "Works in store and online", based on Maverick's FAQ. Confirm for Impeccabyte accounts.

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

- **Customer story.** Still a placeholder: "[Restaurant name]", "[XX]-seat [cuisine]", "[X]%" and "[X] min". Needs a real, approved story or should be removed.
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
- **Founder photo.** Ryan's own photo, supplied 2026-11-21: a selfie at his desk with a decorated holiday tree behind him. Swap it for a year-round shot whenever there is one (replace `scripts/content/assets/about/founder.jpg` and the media item "About: founder").
- **Office, phone and hours.** "Find us" and the FAQ show [Street address], Austin, TX [ZIP], [Phone number] and [Business hours]. Confirm whether visitors can stop by the office at all; if not, the "stop by" wording comes out.
- **Service area.** The FAQ "Do you only work with Austin businesses?" is a [Confirm] placeholder.
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
- **Clover specs still missing.** The knowledge base has no Clover spec sheets, so the Clover devices other than Mini still have no spec table, and Clover Mini's printer row still reads "[Confirm with current Clover spec sheet]".
- **Product photos.** Every device shows official images from its manufacturer's website (Clover, Dejavoo, PAX, Valor, SwipeSimple, Fiserv), trimmed and centred on one transparent canvas. Sources are in `scripts/content/assets/devices/README.md`. Confirm that your agreements allow using manufacturer marketing images.
- **Details written from existing copy.** Summaries for the original Clover devices reuse what the site already says. Two are worth a check: Clover Station Duo "one screen for your team and one facing your customer", and Clover Kiosk "customers order and pay on their own". Clover Flex Pocket's line ("lighter, without a built-in printer") comes from clover.com.
- **Lease and buy terms.** Clover devices say the starter kit and 3-year protection are included, as on Pricing. The other devices only say "Month to month" and "One payment and the device is yours" until their terms are confirmed.
- **Compatible gateways.** All Clover devices show the Clover gateway section. The other devices have no gateway section yet; confirm which gateways each supports.
- **PAX A920 Pro.** PAX now sells the A920Pro Duo (PCI 7) as its current model; Maverick's knowledge base still lists the A920 Pro.
