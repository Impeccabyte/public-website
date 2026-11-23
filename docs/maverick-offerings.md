# What we can offer through Maverick

A reference of the hardware, software, integrations and programs Maverick Payments makes available to its agents, built from a full read of Maverick's agent knowledge base (kb.maverickpayments.com/kb/en/) on 2026-11-21: all 74 guides (365 steps) and all 96 distinct attachments (about 300 pages of PDFs and one spreadsheet, image-only PDFs read by OCR).

Use it to check what the site says before it says it. Section 11 compares the site with this list.

**What is deliberately left out.** The knowledge base is an agent resource and mixes in agent costs, buy rates, revenue shares, bonus programs and the terminal placement program's terms. None of that is recorded here (see `CLAUDE.md`, "Content rules"). Merchant prices on the site still come only from Impeccabyte's pricing playbook. Where this document says "priced in the knowledge base", look it up there; do not copy the number.

**How sure "we offer it" is.**

- **Core:** Maverick stocks it or runs it, and the knowledge base documents it in its own words. Safe to name on the site.
- **Special order:** Maverick sources it from vendors, usually within 3 to 5 business days. Fine to offer on request; don't feature it as standard.
- **Partner:** a third party integrated with Maverick that sets its own pricing, onboarding and support. Fine to name as something we can connect, not as our product.
- **Referral:** Maverick refers the merchant to the vendor, who sells, installs and supports it (TouchBistro, Genius). We can introduce it; we don't provide it.
- **Restricted:** named in the knowledge base but its page is not public (eight embedded guides, section 12). Confirm with Maverick before using.

The knowledge base can change without notice. Before a page promises something specific, confirm it with Maverick (partners@maverickhq.com, (800) 464-9777).

## 1. Processing platforms

| Platform | What it covers | Notes |
|---|---|---|
| TSYS (Global Payments) | Card present and card not present | Default for most terminals and the Maverick gateway. Five sponsor banks. |
| Fiserv | Clover, the FD150, Fiserv front ends (Nashville, Buypass, Omaha/North) | Clover requires Fiserv processing. Fleet cards (WEX, Voyager) run on the Buypass front end. Several high-risk categories are excluded on Fiserv. |
| Visa Platform Connect (VPC) | Card not present only (card present "coming soon") | Maverick's newest rail, launched 2026. Adds Unified Checkout (Apple Pay, Google Pay, Click to Pay), network tokens, real-time account updater, 3D Secure, Cybersource fraud tools and access to Cybersource-compatible carts and CRMs. Merchants already on the internal gateway must be boarded to VPC to get these. |
| Maverick ACH | ACH and eCheck | Maverick's own ACH engine. SEC codes CCD, PPD, WEB and TEL; virtual terminal entry, CSV upload, hosted form, Pay by Bank, ACH API. |

Funding: two-day, next-day and (on TSYS) same-day options exist, with cutoffs that differ by platform and bank. The site keeps to "cutoffs vary by processor" (`CLAUDE.md`).

## 2. Payment programs

From the "Dual Pricing & Surcharging" guide and the device pages.

- **Dual pricing.** Cash and card prices both shown; the card price can be at most 4% higher, and never more than the merchant's processing cost. Applies to every card type except EBT, including PIN debit. Legal in all 50 states; no card brand registration. The guide says the card (highest) price must be the one advertised and, oddly, that "no signage should be posted for dual pricing". That conflicts with common practice and with our dual pricing copy; raise it with counsel (see `to-confirm.md`).
- **Surcharging.** Credit cards only, capped at 3% (2% in Colorado) and at the processing cost. Not allowed on debit or prepaid. Prohibited in California (from July 2024), Connecticut, Maine, Massachusetts, Oklahoma and Puerto Rico. Card brand registration required (the bank registers the merchant). Must be disclosed at the point of entry and point of sale and itemized on the receipt.
- **True cash discount.** The terminal takes a discount off when the cashier picks cash; the entered amount is treated as the card price.

Which devices support which program is in section 3.

## 3. Hardware

### Countertop, handheld and mobile terminals (core)

"DP" dual pricing, "SC" surcharging, "CD" true cash discount, all as stated on the device's knowledge base page.

| Device | Type | Connectivity | Programs | PIN pad | Notes |
|---|---|---|---|---|---|
| Dejavoo Z8 | Countertop | LAN, 2.4 GHz Wi-Fi | DP, SC, CD | Z6 | Budget terminal, non-touch screen. |
| Dejavoo QD4 | Countertop | LAN, 2.4 GHz Wi-Fi | DP, SC, CD | QD3 or QD5 | 5.5" touchscreen, built-in printer. |
| Dejavoo QD2 | Wireless (Android) | Wi-Fi, 4G | DP, SC, CD | | Tableside and on the go. |
| Dejavoo QD3 | Wireless (Android mPOS) | Wi-Fi, 4G | DP, SC, CD | | Compact; also sold as a PIN pad for the QD4. |
| Dejavoo P1 | Countertop | LAN, 2.4/5 GHz Wi-Fi | DP, SC, CD | P17 | EBT chip and tap. ACH through Paya (Maverick ACH "coming soon"). |
| Dejavoo P3 | Wireless, rugged | Wi-Fi, 4G | DP, SC, CD | | |
| Dejavoo P5 | Wireless mPOS | Wi-Fi, 4G | DP, SC, CD | | |
| Dejavoo P8 | Wireless, touchscreen | Wi-Fi, 4G | DP, SC, CD | | |
| Dejavoo P18 | Hybrid (docked countertop or handheld) | LAN when docked, Wi-Fi, 4G | DP, SC, CD | | Dock connects printers, cash drawers and Ethernet; tilts to face the customer for tips. |
| Valor VP100 | Countertop | LAN, 2.4 GHz Wi-Fi | DP, SC, CD | VP300 | Recommended for petroleum and auto rental too. |
| Valor VP550 | Wireless (Android) | Wi-Fi, 4G | DP, SC, CD | | |
| Valor VP800 | Hybrid, dual screen | LAN when docked, Wi-Fi, 4G | DP, SC, CD | Built in (customer screen) | Customer screen shows the itemized order and cash and card prices. |
| Valor VL550 | Wireless (Android) | Wi-Fi, 4G | DP, SC, CD | | Fleet cards on Buypass (WEX, Voyager, FuelMan, FleetOne, Visa and Mastercard Fleet). |
| PAX A920 Pro | Wireless (Android) | Wi-Fi, 4G | SC only | | Standalone or semi-integrated with many POS systems. |
| PAX A80 | Countertop (Android) | LAN, Wi-Fi | SC only | Q25 | Standalone or semi-integrated. |
| PAX A35 | Countertop PIN pad | LAN, Wi-Fi | SC only | (is one) | Semi-integrated PIN pad for medium to large POS checkouts. |
| Fiserv FD150 | Countertop | LAN, 2.4 GHz Wi-Fi, dial-up | none listed | RP10 | Lodging, auto rental, petroleum. The only device for WEX and Voyager fleet programs (Buypass). FD gift cards. |
| Equinox | Countertop | LAN, 2.4 GHz Wi-Fi | none listed | | Recommended for lodging. Not in the placement program. |
| SwipeSimple B350 | Mobile reader | Bluetooth to phone | through SwipeSimple software | | Runs on every SwipeSimple plan. |

Every Dejavoo and Valor device above accepts Visa, Mastercard, Discover, American Express, PIN debit and EBT, with chip, swipe and tap (Apple Pay, Google Pay). Gift cards: Factor4, SAGE, Paya and STS on Dejavoo; Factor4 and Valuetec on Valor; Factor4 on PAX.

Debit encryption keys can be loaded remotely on Dejavoo, PAX and Valor terminals (remote key injection), so a merchant's own compatible terminal can usually be reprogrammed rather than replaced.

### PIN pads (core)

Dejavoo Z6, QD3, QD5 and P17; PAX Q25 (shows the surcharge to the customer); Valor VP300; Fiserv RP10.

### Clover (core, Fiserv)

| Device | Type | Facts from Clover's sales sheets |
|---|---|---|
| Go (3rd gen) | Mobile reader plus free app | Pairs with iOS or Android; app can key or scan cards on its own; offline mode; Apple Pay and Google Pay on the reader. |
| Compact | Countertop | 3.6" touchscreen, tactile PIN pad, thermal printer, LTE, Wi-Fi and LAN, Android 13. Surcharging, cash discount, EBT, gift cards, manual check entry. |
| Flex (4th gen) | Handheld | Built-in printer, 5MP camera with barcode scanning, Wi-Fi, 4G/LTE. |
| Flex Pocket | Handheld | 6" display, 0.9 lb, no printer, barcode scanning, Wi-Fi, 4G/LTE. |
| Mini (3rd gen) | Countertop POS | 8" screen, swipe, chip and contactless, LTE tablet option. |
| Station Solo | Countertop POS | Includes printer; optional cash drawer. |
| Station Duo (2nd gen) | Countertop POS | Customer-facing screen; includes printer; optional cash drawer. |
| Kiosk | Self-order kiosk | 24" ordering screen with 8" payment display and printer; orders go to the KDS or kitchen printer. Growth Restaurant plan only. |
| KDS (14" and 24") | Kitchen display | Growth Restaurant plan only. |

Clover peripherals are also available: P500 receipt printer, Star kitchen printers, Epson sticky label printer, Zebra barcode scanners, CAS weight scale, cash drawer, starter kits and stands. Clover cellular data is billed per active SIM.

Programs on Clover: surcharging (enabled by Maverick support, account must be on surcharge pricing) and true cash discount (the "Cash Discount" app). The knowledge base does not list dual pricing for Clover.

### Special order (3 to 5 business days)

Dejavoo D1 register and DPP cash drawer; PAX Q30, A77, E600 Mini, E700, E800 and Aries 8 (end of life, limited supply); PAX A35 and Ingenico Lane 7000 Datacap variants; Ingenico P200, Desk/1500, Desk 5000, Lane 7000 and Lane 7000 Deluxe; Star TSP143III printers and cash drawer; stands, batteries and power supplies.

### Referral-partner hardware

PAX A800 (TouchBistro); Verifone TC650C and TC650P (Genius for Restaurants). Deployed and supported by the partner.

### Older terminals with quick reference guides

PAX S80/S90, A80 and A920; Verifone VX520 and V200c/V400c/V400m Plus; Ingenico Desk 3500, Desk 5000 and Move 5000; Valor VL100 and VL110; Dejavoo Z9 and Z11. These are support guides for merchants who already own them, not a current lineup. Their dates run from 2016 to 2021 (Dejavoo 2024).

## 4. POS software

| Product | Kind | Who it fits | Notes |
|---|---|---|---|
| Clover software plans | Core (billed by Clover on the Clover statement) | All | Six plans, Q1 2026, US only: Starter, Essentials, Services, Growth Retail, Growth Restaurant, Clover for Healthcare. See below. |
| SwipeSimple | Core | Mobile, services, retail, appointments | Starter, Standard and Premium plans. Mobile app, Tap to Pay on iPhone, virtual terminal, invoicing with text-to-pay, payment links, recurring billing, inventory, QuickBooks Online sync and appointments (Premium). Dual pricing, cash discount and surcharging on every plan. Runs on the B350 reader and PAX A80, A920 Pro and Aries 8. |
| DejaPayPro | Core | Retail and restaurants | Cloud POS on Denovo. Retail: inventory, dual pricing, cash discount. Restaurant: split tickets, payment links, online ordering, reservations, table management, tips. Runs on the Dejavoo D1 register, QD2, QD3, QD4, P1, P3, P5 and P8. |
| Quantic POS | Core | Retail and restaurants | iPad and Android stations, QLite and smart terminal tiers; kiosk stations, online ordering and gift cards as add-ons. Integrates PAX A920 Pro, A80, A35, A800 and Dejavoo P1, P17 and P8. Dual pricing and cash discount. Hardware includes barcode scanners, scales, kitchen, receipt and label printers. |
| iPOSPays (Dejavoo) | Core | All | Omni-channel gateway and portal: terminals, Cloud POS (virtual terminal, invoices, payment links, card on file, recurring billing, hosted payment pages, QR codes) and iPOSGo tap to pay. WooCommerce extension, QuickBooks Online sync, 3D Secure, Level 2/3, lite inventory. |
| Valor portal | Core | All Valor merchants | Transaction management, virtual terminal (invoices, payment links, QR codes, recurring, customer vault), "Engage My Customer" text marketing, QuickBooks Online sync, lite inventory, card updater. |
| Denovo (Dejavoo) | Core | Dejavoo merchants | Settlement reports, digital receipts ("Go Green"), Spin Proxy to pair a terminal with a semi-integrated POS. |
| TouchBistro | Referral | Full-service and quick-service restaurants, bars, breweries | Reservations, KDS, online ordering, delivery integrations, loyalty and gift cards. TouchBistro prices, installs and supports it. |
| Genius for Restaurants | Referral | Restaurants and bars | The Genius dealer prices, installs and supports it. "Genius Retail" is listed with no content. |

**Clover plans.** All six include card payments, virtual terminal, eCommerce connections, Clover Go, email invoicing, recurring payments, surcharging, cash discount, tips, EBT, PayPal/Venmo and Paze, gift cards, Level 2/3 data, offline mode, customer records, Clover Capital and Rapid Deposit, the Clover Dashboard and 24/7 support. Restaurant-only features (likely Growth Restaurant) include online ordering, tableside ordering and payment, bill splitting, table management, kitchen printing, Kiosk and KDS, and UberEATS, DoorDash, Grubhub and Google integrations. HSA/FSA cards appear on one plan (likely Healthcare). Appointments, online booking, no-show fees and Reserve with Google appear on one plan (likely Services). Clover for Healthcare is assigned automatically for medical MCCs, can't be changed afterwards, and only supports Mini, Flex and Go.

## 5. Gateways and online payments

| Gateway | Kind | Notes |
|---|---|---|
| Maverick gateway (Merchant Dashboard) | Core | Virtual terminal with Level 2/3 ("Advanced Sale"), hosted payment form (embed or link), customer vault, recurring plans, customizable email receipts, AVS/CVV settings, interchange optimization, 3D Secure (on request), account updater (may cost extra; "not yet available" on TSYS), API and webhooks. |
| Visa Platform Connect | Core | Card not present only. See section 1. |
| Authorize.Net | Core (supported) | Listed in Maverick's gateway pricing. |
| NMI | Core (supported) | With eCheck, encrypted devices, fraud protection and payer authentication (3DS) add-ons. |
| USAePay | Core (supported) | Silver, Gold and Platinum packages. |
| Accept.Blue | Partner gateway | White-label gateway: virtual terminal, Level 3, invoicing, ACH, customer vault, recurring, surcharging, Apple Pay and Google Pay on invoices and hosted pages. Connects to Maverick ACH. |
| PayTrace | Core (supported) | Basic (virtual terminal) and Professional (API, payment links, recurring, Level 2/3, EMV). |
| Fluid Pay | Partner gateway | Virtual terminal, recurring billing, customer vault, invoicing, account updater; dual pricing when the merchant has both card and ACH accounts. |
| Slim CD | Core (supported) | Hosted pages, EMV device activation, enterprise recurring. |
| Datacap | Core (supported) | Certek Artisan POS only. |
| SwipeSimple gateway | Core | Part of the SwipeSimple plans. |
| iPOSPays Cloud POS | Core | See section 4. |

## 6. Integrations

Partners connect to the Maverick gateway or Maverick ACH through the dashboard's API tokens or "Connect" feature. Each sets its own price and onboarding.

| Partner | What it does | Integrates with (as stated) |
|---|---|---|
| Chargezoom | Invoicing, payment links, recurring billing, Level 2/3, card and ACH | QuickBooks (Desktop and Online), Xero, FreshBooks |
| Biller Genie | Accounts receivable automation: emailed invoices, online payment, auto-reconciliation, recurring and auto pay, Level 2/3, card and ACH | QuickBooks (all versions, Online and Desktop), Xero, AccountingSuite |
| RECUR360 | Recurring invoicing and subscription billing, late fees, convenience fees, early-pay discounts, card and ACH | QuickBooks Desktop and Online, open API |
| AuthVia | Text-to-pay (TXT2PAY): payment requests by text, email, chat or QR, card and ACH. Standard portal billed through Maverick; Billing Pro, Managed Services and API solutions by referral | Merchant's gateway; industries: auto service, field services, call centers, healthcare, pharmacy, property management |
| OpenPath | Checkout for online stores without a separate gateway; card and ACH | Shopify, WooCommerce, Magento |
| Checkout Champ (Konnektive) | Hosted checkout pages and CRM, credit card | Shopify, WooCommerce, BigCommerce |
| Vrio (formerly Sublytics) | eCommerce subscription billing, upsells and routing | Maverick gateway |
| Valor and iPOSPays add-ons | See section 4 | WooCommerce, QuickBooks Online, Magento, OpenCart, BigCommerce (Valor), 3D Secure via Cardinal |
| Skipify, Hyfin, QuickRefund | Named partners | Restricted pages (section 12); a Skipify addendum is among the merchant forms |

ISO-side CRMs (for us, not merchants): Merchant Central (formerly IRIS CRM) and AboveBoard CRM.

## 7. Chargeback and fraud tools

Built in: the Merchant Dashboard's chargeback tools and Fraud Analysis page (chargeback patterns, a negative database of cards with prior chargebacks, decline breakdowns), and Bizlitix.

| Partner | What it does |
|---|---|
| Chargeblast | Pre-dispute alerts (Ethoca and Verifi named on the guide), automatic matching and refunding, rules builder |
| Disputifier | White-label alerts (Ethoca, RDR, CDRN), automated representment, AI fraud blocking; integrates Shopify, WooCommerce, CheckoutChamp, sticky.io, NMI, Authorize.Net, Fluid Pay |
| Xcaliber | Xprotect real-time risk monitoring, alerts, chargeback audits, 3D Secure, BIN and negative-database filters |
| Midigator (now part of Kount) | Chargeback reporting, management and fighting |
| RocketGate | Automated chargeback processing, subscription cancellation and blocking; connects through the dashboard's App Connect |
| Chargeback.io, Findustry AI, QuickRefund | Named; pages restricted |

## 8. Other services

- **Gift and loyalty cards.** Factor4 (preferred): physical and digital gift cards, loyalty cards, carriers, online gift card sales, conversions from FD Gift, Clover Gift, STS, Paya and others. Works with Dejavoo/iPOSPays, Valor, Clover, PAX and Ingenico Desk 3500. Valuetec on Valor. Clover Gift Cards on Clover. FD Gift on the FD150. Quantic has its own gift cards.
- **4G wireless.** NuvoLinQ SIMs on AT&T for wireless-capable terminals (10 MB and 25 MB plans).
- **Fleet cards.** WEX (full acquiring) and Voyager on the FD150 through Fiserv Buypass. Fits fuel, fleet, auto repair and B2B.
- **Bizlitix.** Business analytics in the dashboard: card analytics, rush hour, website analytics, keyword research, local competition, heatmaps, market intelligence, reputation monitoring.
- **Terminal placement program.** Maverick has an equipment placement program for qualifying merchants with its own addendum. Its terms are internal and are not recorded here.
- **Split funding.** Covered by a Split Funding Agreement (v202603).
- **Merchant Dashboard.** Statements, batches, authorizations, chargebacks, reserves, ACH rejects, payouts, notifications by email or SMS, two-step verification, user permissions.

## 9. Security

Maverick is PCI DSS Level 1 (listed on Visa's and Mastercard's service provider registries) and has a SOC 2 Type II report each year; the current certificate covers Maverick BankCard Inc. for the period ending December 31, 2025.

### From the merchant agreement (Acquiring MPA v202607 and ACH Agreement v202607)

- **Card types:** Visa, Mastercard and Discover through the sponsor bank; American Express through OptBlue (or an existing Amex ESA); PIN debit; EBT; PayPal In-Store Checkout (on by default unless the merchant opts out); Visa Limited Acceptance (credit-only or debit-only).
- **Pricing structures the agreement supports:** interchange plus, tiered, flat rate, surcharge and dual pricing.
- **Equipment:** bought outright, staged only (reprogramming the merchant's own terminal) or through the placement program.
- **ACH origination:** debits and credits (payouts), PPD, CCD, WEB and TEL, same-day ACH if approved.
- **Split funding:** part of each batch sent to a named payee by percentage, fixed amount or added convenience charge.
- **Skipify:** digital wallet and faster checkout, pay-by-link by SMS and "Flex Pay by Upgrade" pay-over-time offers. US and Canada (not Quebec).
- **Terms worth knowing before we promise anything:** three-year term with two-year renewals and an early termination fee; reserves can be set or raised at any time; settlement credits are provisional; the agreement overrides anything a sales representative says (MPA section 3). The site's "no promises of approval, no reserves or funding times" rule (`CLAUDE.md`) matches this.

## 10. Specialty industries

Maverick publishes 19 high-risk addenda (v202603 unless noted) and a credit matrix (2026-10-01) that sets what underwriting accepts. Every addendum requires licenses where the merchant operates and where its customers are, true product statements, disclosure of every website (with a monthly URL monitoring fee), and notice of any regulator inquiry. Many add card brand registration with Visa and Mastercard, which carries initial and annual fees set out in the addendum.

| Site page | Maverick addendum | Main requirements |
|---|---|---|
| Hemp & CBD | Hemp CBD v202603; FFB bank version v202607 | THC at or under 0.3%; lab results on request; age verification; no cure or treat claims; FFB version adds 12-month-old CoAs, geofencing and bans delta-8, delta-10, THC-O, HHC and synthetic cannabinoids. HHC needs its own certification addendum. |
| Smoke & Vape | Tobacco, Nicotine, E-Cigarette and Paraphernalia | Card-present and card-not-present approved separately; CNP needs Visa and Mastercard registration; 21+ age verification; exact nicotine warning on the site; FDA marketing authorization for every product; no CBD or kratom without approval (and none at all through a Fiserv MID). |
| Kratom | Kratom | Testing results on request; stop sales where it is illegal; no "incense" labelling, health claims or free trials; kratom cannot be the main line of business (credit matrix). 7-OH is prohibited. |
| Firearms | Firearm | Federal Firearms License; FFL-to-FFL shipping disclaimer on the website; Gun Control Act and related law. Fiserv excludes CNP firearms. Some banks are card-present only. |
| Adult Content | Adult Content | Content monitoring and takedown, complaint process, age and ID checks for anyone depicted, monthly reporting; Visa and Mastercard registration. The credit matrix accepts studio-produced or webcam content only and prohibits AI and content-creator platforms. |
| Dating | Dating and Related Verticals | No explicit content; age verification; identity controls; registration with Visa (and Discover per the matrix); Negative Option addendum if trials are offered. |
| Gaming | Gaming and Related Verticals | Legal opinion per state, third-party certification of age and location controls, geolocation blocking, a written compliance program; Visa MVV for MCCs 7800 to 7802 and 7995. Fiserv excludes gaming. |
| Collections | Collection Agency | Licensed where debtors live; no debt buying; debit cards only; FDCPA, FCRA, TCPA. The credit matrix lists collection agencies both as acceptable with conditions and on a prohibited list, so confirm per bank. |
| Credit Repair | Credit Repair and Related Services | Credit Repair Organizations Act and Telemarketing Sales Rule among others; strict advertising and billing bans, including no free or risk-free trials. |
| Lending | Short Term Loan | No payday loans; US borrowers only, no tribal lending; full disclosure of loan terms; Visa credit cards cannot repay the loan. Short-term (under 12 months) and predatory lending appear on the prohibited list. |
| Nutraceuticals & Subscriptions | Marketing Practices; Negative Option Trial | Unchecked consent box with price and frequency, clear cancellation, email notice of charges, no free trials under Marketing Practices; card brand rules on enrollment and reminders. Registration for negative option merchants. |
| Online Pharmacy & Telehealth | Card-Absent Pharmacy | NABP or LegitScript accreditation (or licenses in every state shipped to), DEA and Ryan Haight Act compliance, age and ID verification; registration with all four card brands. Telehealth without prescriptions is "teledoc" in the credit matrix. Fiserv excludes CNP pharmacy. |

Addenda with no page on the site: Cannabis-Industry-Supporting Related Businesses, HHC-Related Products, Marketplace, Money Service Business, Mushroom-Related Products. The credit matrix also covers many more categories with conditions (alcohol, auctions, bail bonds, charities, coaching, crowdfunding, digital goods, extended warranty, lodging, membership clubs, moving companies, pawn shops, travel agencies and others).

## 11. Compared with the site

### Hardware

- **Every one of the 26 catalog devices is in the knowledge base.** All are core stock except the VP800 and VL550, which are core but outside the placement program.
- **Core items the catalog doesn't list:** the PIN pads (Dejavoo Z6, QD5, P17; PAX Q25; Valor VP300; Fiserv RP10), Clover KDS 14" and 24", the Equinox lodging terminal, and the Dejavoo D1 register (DejaPayPro). Clover peripherals (printers, scanners, scale, cash drawer) appear only as bundle items.
- **"40+ terminals, readers and POS systems" (Home stats band):** defensible. Core devices and PIN pads come to about 40, and special order adds more than 15. It overstates the 26-device catalog, so either list more or keep the figure as "we can set up".
- **Dual pricing ready devices:** the knowledge base confirms dual pricing on every Dejavoo and Valor terminal, surcharging only on PAX, surcharging and cash discount on Clover, and nothing listed for the FD150. One exception to the rule in `CLAUDE.md`: SwipeSimple's software provides dual pricing on its supported PAX terminals (A80, A920 Pro) and the B350. Keep the rule for standalone terminals and decide whether to mention SwipeSimple.
- **Fleet cards:** the WEX and Voyager guide says fleet is "available exclusively on the FD150", while the VL550 page also lists fleet cards on Buypass. Confirm before saying either.

### Tap to pay (open item 1.14 in `content-audit.md`)

Three ways, all documented: **iPOSGo** (Dejavoo) on iPhone XS or later with iOS 16 or later, or Android 9 or later with NFC and Google Play; **SwipeSimple** Tap to Pay on iPhone; and the **Clover Go** app (keyed or scanned on the phone, tap with the Go reader).

### Integrations page

Fixed on 2026-11-22 (`scripts/content/93-maverick-copy-fixes.php` and the source scripts), so the page now names only what the knowledge base supports:

- **Delivery apps:** DoorDash, Uber Eats and Grubhub connect to Clover's restaurant plans, on the Integrations and Restaurants & Bars pages. The "order apps with their own monthly fee" wording is gone.
- **Shopify:** through OpenPath or Checkout Champ, not Authorize.Net or NMI.
- **Online stores:** checkout partners and gateways, with OpenPath and Checkout Champ for Shopify and Valor and iPOSPays for WooCommerce.
- **Accounting:** QuickBooks, Xero and FreshBooks through Chargezoom, Biller Genie and RECUR360, and the Valor and iPOSPays QuickBooks Online sync. NetSuite and Salesforce (logos and text) and the Sage, Acumatica and Dynamics note are removed; the ERP note now asks merchants to tell us their software.
- **"Hundreds of carts and business tools"** in the FAQ is removed, since nothing in the knowledge base backs the number.

### Solutions and industry pages

- **Card updater** (Recurring billing): available through Valor, Visa Platform Connect, Fluid Pay and the Maverick gateway (extra cost; "not yet available" on TSYS). Name the route when quoting.
- **HSA and FSA cards** (Healthcare): supported on Clover's healthcare plan; Clover assigns that plan automatically for medical MCCs and it only runs on Mini, Flex and Go.
- **Text-to-pay** (Home, Auto Repair, Healthcare): supported through AuthVia, SwipeSimple Standard and Premium, and the Valor and iPOSPays virtual terminals.
- **Gift cards** (Salons): Factor4, Clover Gift Cards, Valuetec and Quantic gift cards.
- **Specialty pages** (open item 1.10): fixed 2026-11-22. The five placeholder pages now carry their addenda requirements, and the other seven gained the addendum requirements the first table left out (`scripts/content/88-specialty-requirements.php`). Adult Content's hub line said "Adult content sites and creators", which conflicts with the credit matrix's ban on content-creator platforms; it now reads "Adult content websites" (fixed 2026-11-22).
- **Dual pricing page:** Maverick caps the card price at 4% above cash and says the card price is the one to advertise. Its guide also says "no signage should be posted for dual pricing", which needs counsel's view (already open in `to-confirm.md`, Dual pricing). The Dejavoo Z8 quick reference guide's on-screen prompt says "fee"; our copy must not repeat it.

### Questions for Maverick

1. Is fleet card acceptance on the VL550 or only on the FD150?
2. Can we say Authorize.Net or NMI connect to Shopify? (The site now names only OpenPath and Checkout Champ.)
3. Which ERP integrations are supported, if any? (The site names none.)
4. For collections, which banks accept the category (the credit matrix lists it as both conditional and prohibited)?
5. Is the Equinox still current, and what model is it?
6. What do the restricted pages say about Skipify, Hyfin, QuickRefund, Chargeback.io and Findustry AI?
7. Can we present SwipeSimple's dual pricing on PAX hardware as dual pricing ready?

## 12. Restricted or empty pages

These knowledge base pages are referenced but not public (the server refuses them): Denovo Services and Add-on Services for iPOSPays (as embedded copies; their main pages were read), Payouts, QuickRefund, Skipify, Hyfin, Chargeback.io and Findustry AI. "Genius Retail", "FD Series Terminals" and several filter pages have a title and no content.
