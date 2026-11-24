# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Small business owners in Austin and Central Texas, in two situations:

- **Switching.** They already take cards, find their processing statement hard to read and their fees surprising, and want to know whether they could pay less or at least understand what they pay.
- **Starting out.** They are opening a business, or adding card payments for the first time, and need a merchant account, the right hardware and a pricing program without learning the industry first.

They arrive by industry as often as by product: restaurants and bars, food trucks, retail, salons and spas, home services, healthcare and dental, auto repair, professional services, nonprofits, pop-ups and markets, plus specialty verticals (firearms, dating, collections, credit repair, lending and others) that many processors turn away. Most read on a phone at some point.

Whether Impeccabyte takes clients outside Austin and Central Texas is undecided (`docs/content-audit.md` 1.12). Do not claim either way.

## Product Purpose

Impeccabyte is a merchant services agency in Austin, Texas, and an authorized sales agent of Maverick Payments. The site explains card processing in plain words, shows the hardware and pricing programs Impeccabyte can set up, and turns a visitor into a conversation.

**Success is a processing statement uploaded for review** (the statement review form). A statement review is what wins a switch. The three-step Get a quote form and a call or text are the supporting paths.

Founded in 2025 by Ryan Bealey. The founder story (About us, `scripts/content/97-founder-story.php`) is the origin: watching a small business owner face a confusing statement and surprise fees every month, and starting Impeccabyte to set businesses up with pricing they understand and fees that never surprise them.

## Positioning

Three claims Impeccabyte stands behind:

1. **Statements and fees in plain English.** We explain what you pay and why, and explain payments terms in plain words.
2. **Published pricing.** Program rates, monthly fees and hardware prices are stated openly rather than held back for a sales call. This is a commitment pending approvals: prices are loaded in price fields but stay hidden ("Show prices" off) until Maverick approves the merchant-facing pricing in writing and counsel reviews the dual pricing wording.
3. **A setup fit to your industry.** Each industry gets a recommended hardware and program setup, including specialty verticals with their own requirements.

Not a positioning claim: being a named local person you can call. Austin is where we are, not the pitch.

## Operating Context

- Built on Drupal 11 (PHP 8.4, MariaDB, Docker). Content is managed by the client in Drupal; sections are paragraph types, UI is single directory components in `web/themes/custom/impeccabyte`. Structure and first content go through re-runnable scripts in `scripts/setup` and `scripts/content`. See `CLAUDE.md` and `docs/BUILD_BRIEF.md`.
- Approved hi-fi wireframes in `design/wireframes/` (live canvas linked in `design/README.md`) and design tokens in the theme's `css/tokens.css` are the design authority.
- Sections of the site: Solutions (in person, online, on the go, recurring billing, dual pricing, interchange plus, chargeback protection, integrations, funding), Hardware catalog with filters and compare, device detail pages, Industries, Pricing, Get a quote, About us. Resources are built but offline since 2026-11-21.
- What Maverick actually offers is in `docs/maverick-offerings.md`; claims waiting on a yes are in `docs/to-confirm.md` and `docs/content-audit.md`.
- "Log in" points to the merchant app, which is out of scope for the site (target URL still to confirm).

## Capabilities and Constraints

- **Merchant services only.** No business banking in copy, menus or forms.
- **No promises** of approval, no reserves, or funding times beyond "cutoffs vary by processor".
- **Prices** come only from Impeccabyte's pricing playbook (November 2026) and live only in price fields, never free text, so one setting hides them all. Prices the playbook does not cover are editable seed content.
- **Never** put Impeccabyte's costs, Maverick buy rates, residual shares, margins, non-merchant hardware tiers or the placement rule anywhere in the site or repo.
- **Dual pricing:** never call the card price difference a fee. Only Dejavoo and Valor terminals are dual pricing ready through Maverick.
- **Required disclosure on every page**, exact text: "Merchant accounts are provided by Maverick Payments and its sponsor banks. Impeccabyte is an authorized sales agent of Maverick Payments."
- **Placeholders stay placeholders.** Phone, address, hours, rates not in the playbook, contract terms and legal pages are `[bracketed]` until supplied.
- Out of scope: the merchant app, payment processing, CRM integration, production hosting. Where form submissions go (email, CRM) is undecided.

## Brand Commitments

- Name: Impeccabyte. Austin, Texas.
- Voice: warm, plain-spoken, encouraging, professional. Speak to "you", refer to the company as "we". Sentence case, with the formal title-case exceptions listed in `CLAUDE.md` and `scripts/content/99-formal-names.php`. Buttons start with a verb. No emoji, no em dashes. State prices and fees plainly.
- An established design system (Impeccabyte design system: tokens, components, rules in `CLAUDE.md`, "Design rules") is binding. It is recorded there, not here.

## Evidence on Hand

- **Real:** the founder story, name, founding year and photo (About us). Maverick's documented lineup and programs (`docs/maverick-offerings.md`). Official manufacturer device photos (usage rights to confirm, `scripts/content/assets/devices/README.md`).
- **Not real yet:** every testimonial and customer story (Home, Get a quote, Restaurants) is a labelled placeholder. Never present an invented quote, customer, figure or statistic as real.
- **Illustrations, not photography:** every page photo other than devices and logos is a generated illustration. Replace with real photography when it exists.
- **Permission pending:** third-party logos (partner strip, gateways, integrations).
- **Not approved yet:** merchant-facing pricing (Maverick written approval and counsel review).

## Product Principles

1. **Clarity is the product.** If a page leaves a fee, term or step unexplained, it has failed the founding promise.
2. **Lead toward the statement review.** The strongest next step for a switching owner is "send us your statement"; every page should make that easy to find.
3. **Show the setup, not just the processor.** Industry and device pages answer "what would I actually use and what would it cost me".
4. **Only say what is true and approved.** Placeholders stay visible as placeholders; claims wait for Maverick, counsel or the client.
5. **Respect the merchant's time.** Short paths, plain answers, nothing hidden behind a call.

## Accessibility & Inclusion

WCAG 2.2 AA, with the project rules in `docs/BUILD_BRIEF.md` section 5: 4.5:1 text contrast, 3:1 for control outlines and focus rings, 24px minimum targets (44px for buttons, chips, menu items and form controls), one `h1` and headings in order, a visible label on every input, nothing conveyed by colour alone, full keyboard support, reduced motion honoured, no sideways scroll at 390px.
