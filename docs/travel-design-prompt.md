# Claude Design prompt: Impeccabyte travel pages

Paste everything below the line into Claude Design. It is self-contained. The facts behind it, and what Fora still has to confirm, are in [`travel-fora-research.md`](travel-fora-research.md).

---

Design two pages, plus one menu panel, for a new travel line of business on the Impeccabyte website. Show each page at desktop (1440px) and phone (390px).

## Who this is for

Impeccabyte is a small Austin, Texas business best known for merchant services (card processing for small businesses). It is adding a second line: travel planning, as an independent advisor hosted by Fora Travel. Travel planning is only for businesses with an active Impeccabyte merchant account: it is a service for our payments customers. Visitors are mostly those business owners and their families, planning a special trip or business travel. They want a better hotel stay than booking it themselves, at the same price, with a real person to plan it. Merchants book travel directly with their Impeccabyte account manager; there is no trip request form on the site. Success is a merchant contacting their account manager about a trip, or a non-customer asking for a quote.

## Pages

1. **Travel overview** (`/travel`). Hero, how planning works in three steps, what you get at partner hotels, the kinds of trips we plan, what it costs, FAQ, and a closing call to action.
2. **Hotel perks** (`/travel/hotel-perks`). Explains partner-hotel perks honestly: what partner hotels usually include, what is "when available", and an example perk card for a sample hotel. Ends with a "Log in" button.
3. **Services menu panel.** The site's Services mega menu gains a second business line. Down the left: "Payments" and "Travel". With Travel selected, show columns such as "Plan a Trip" (Trip planning, Hotel perks) and "Company" links. Desktop only; the phone menu nests each line in its own expandable row.

There is no booking or trip request form. The main call to action on travel pages is "Log in", to the merchant app at app.impeccabyte.com, where merchants reach their account manager; the secondary path for non-customers is "Get a quote".

## What the pages may and may not say

These rules matter more than anything visual.

- **Perks are examples, not guarantees.** Write "perks such as daily breakfast, a hotel credit and a welcome amenity, depending on the hotel". Upgrades and early check-in or late check-out are always "when available". Never write "guaranteed upgrade", "VIP status", "free breakfast everywhere" or "red carpet".
- **Price:** "In most cases you pay the same rate as booking direct." Any planning fee is shown as a placeholder, "[Planning fee]", and described as agreed upfront. Never write "no fees", "lowest price" or "best rate guaranteed".
- **Partners:** only "partner hotels" and "participating properties". Do not name hotel brands, consortia or cruise lines, and do not use Fora's logo. Use "[Partner hotel]" in examples, and label any figures in an example "Example".
- **Who can use it:** every travel page says plainly that travel planning is for businesses with an active Impeccabyte merchant account, and that merchants book it through their account manager, with a "Get a quote" path for anyone who isn't a customer yet. Never imply anyone can book travel without one, or that travel can be booked online.
- **Relationship:** say Impeccabyte is "an independent travel advisor hosted by Fora Travel". It is not Fora.
- **Required disclosure on every travel page,** near the footer: "Impeccabyte is an independent contractor of Fora Travel, Inc., a registered seller of travel. California CST [number]. Florida [number]. Washington [number]. Registration as a seller of travel does not constitute approval by the State of California." Keep every number a placeholder in square brackets.
- **No invented proof.** No testimonials, ratings, client counts, destination counts or years of experience. Where a quote would go, leave a clearly marked placeholder or leave the slot out.
- **Support:** no "24/7 support" and no payment-protection claims.

## Voice

Warm, plain-spoken, encouraging and professional, like a well-travelled friend. Speak to "you"; the business is "we". Sentence case everywhere, buttons included. Buttons start with a verb: "Log in", "See hotel perks", "Get a quote". No emoji and no em dashes. Explain travel terms in plain words.

## Visual system (use exactly; it is the existing Impeccabyte brand)

- **Mood:** "The Kitchen-Table Ledger". Warm, domestic and honest, like planning a trip at the kitchen table with someone who explains everything. Calm and generous, never glossy or luxury-cliché. No gold, black-and-white glamour, marble or champagne imagery.
- **Colours:**
  - page background paper #faf6ef, never pure white
  - cards white #ffffff with a 1px #e0d6c6 border
  - text ink #2a211a and body #3b3128, muted #635648
  - brand clay #c0623e for the logo and the italic accent word
  - primary buttons fired clay #a44e2f (hover #833d26) with white text
  - tints clay blush #fbf1ec
  - accent amber #e0a04d, only with dark text, never the main button
  - dark bands clove #302419 with text #f3ebde
  - No pure black, no neutral grey.
- **Type:**
  - Newsreader 600 for h1 to h3. One italic word per hero headline, in clay (in amber on clove bands).
  - Hanken Grotesk for everything else.
  - Sizes: hero 62 or 48, section 38 or 30, lead 18, body 16, supporting 14, eyebrow 12 uppercase.
- **Shapes and depth:**
  - Buttons, chips and badges are full pills.
  - Cards have 20px corners and soft warm-brown shadows.
  - Large panels use 28 to 36px corners.
  - 44px minimum controls.
- **Layout:** 1240px container, 24px gutter, 112px between sections (64px on phones).
- **Icons:** Lucide outline at 1.75 stroke, never emoji.
- **Signature move:** explain with real-world objects instead of abstract graphics. The site already uses an example processing statement with numbered lines explained "In plain English". The travel version is an **example hotel confirmation** with perk lines highlighted and numbered: breakfast, a hotel credit, upgrade "when available", check-in or check-out "when available". Beside it sits a dark "In plain English" card explaining each line, and the whole thing is marked "Example".
- **Imagery:** warm, natural travel photography placeholders (a room with morning light, a breakfast table, a street at golden hour). Describe each placeholder; do not show recognisable hotel brands.

## Accessibility

WCAG 2.2 AA: 4.5:1 text contrast, visible focus (a 2px clay outline with a white halo), one h1 per page with headings in order, no meaning carried by colour alone, and full keyboard use of the menu and the FAQ.
