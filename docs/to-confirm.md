# To confirm before launch

Claims and placeholders on the built pages that need a yes, a fix or a real value from Impeccabyte. Each line names the page, what the page says now, and what to check. Started 2026-11-19; add to it as pages are built.

Site-wide items that were already open (phone, address, rates, contract terms, testimonials, legal pages, Intercom token and so on) stay in the vault hub's "Open before launch" list.

## Across the site

- **Logos.** Permission and usage rules for every third-party logo shown: the six partner logos in the Home strip, the three gateway logos on Online, and the eleven platform logos on Integrations (DoorDash, Uber Eats, Grubhub, Shopify, WooCommerce, BigCommerce, Magento, QuickBooks, Xero, NetSuite, Salesforce). Sources are in each `scripts/content/assets/*logos/README.md`.
- **Photos.** Every page photo is a generated illustration (Higgsfield). Replace with real photography when there is some. Screens show sample amounts only on Online ($420.00 invoice, $48.00 checkout, card ending 4242).
- **Log in link.** Points at `app.impeccabyte.com`; the merchant app MVP is at `pos.impeccabyte.com`. Confirm which.

## On the go (`/solutions/on-the-go`)

- **Tap to pay on your phone.** Offered as a setup option. Confirm it is available on Impeccabyte accounts, and which phones it supports. The FAQ still says "[Supported phones and setup steps to be confirmed.]"
- **Cellular.** "Wi-Fi or cellular, depending on the model" for handhelds, and "a cellular data plan, which we can add". Confirm a data plan can be added.

## Recurring billing (`/solutions/recurring-billing`)

- **Card account updater** is described as included ("Card updater included" in the hero).
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

- **Delivery apps.** Uber Eats and Grubhub "connect through order-management apps ... which we include in your quote." Confirm which app you would recommend.
- **Accounting.** "Several gateways and partner apps sync payments and fees to QuickBooks Online and Desktop."
- **Shopify.** "Shopify may charge an extra fee on sales that don't use Shopify Payments."
- **ERPs.** "Sage, Acumatica and Dynamics can connect too" (via accept.blue).

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
