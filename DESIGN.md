---
name: Impeccabyte
description: Warm, plain-spoken merchant services for small businesses in Austin, Texas.
colors:
  clay: "#c0623e"
  clay-deep: "#a44e2f"
  clay-deeper: "#833d26"
  clay-darkest: "#5f2d1e"
  clay-blush: "#fbf1ec"
  amber: "#e0a04d"
  amber-deep: "#ce8a35"
  clove: "#302419"
  clove-night: "#1f1710"
  paper: "#faf6ef"
  white: "#ffffff"
  ink: "#2a211a"
  ink-body: "#3b3128"
  ink-soft: "#4d4239"
  ink-muted: "#635648"
  ink-outline: "#9b8e7e"
  ink-rule: "#e0d6c6"
  ink-hairline: "#efe8dc"
  ink-wash: "#f7f2e9"
  on-dark: "#f3ebde"
  sage: "#5e7a4f"
  brick: "#b23a2e"
  brick-deep: "#8f2e24"
  slate: "#4a6a7a"
typography:
  display:
    fontFamily: "Newsreader, Georgia, 'Times New Roman', serif"
    fontSize: "62px"
    fontWeight: 600
    lineHeight: 1.05
    letterSpacing: "-0.025em"
  headline:
    fontFamily: "Newsreader, Georgia, 'Times New Roman', serif"
    fontSize: "38px"
    fontWeight: 600
    lineHeight: 1.22
    letterSpacing: "-0.02em"
  title:
    fontFamily: "'Hanken Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
    fontSize: "20px"
    fontWeight: 600
    lineHeight: 1.22
  lead:
    fontFamily: "'Hanken Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
    fontSize: "18px"
    fontWeight: 400
    lineHeight: 1.6
  body:
    fontFamily: "'Hanken Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
    fontSize: "16px"
    fontWeight: 400
    lineHeight: 1.6
  label:
    fontFamily: "'Hanken Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
    fontSize: "14px"
    fontWeight: 600
    lineHeight: 1.2
  eyebrow:
    fontFamily: "'Hanken Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
    fontSize: "12px"
    fontWeight: 600
    letterSpacing: "0.12em"
  receipt:
    fontFamily: "'JetBrains Mono', ui-monospace, 'SF Mono', Menlo, monospace"
    fontSize: "14px"
    fontWeight: 400
rounded:
  xs: "6px"
  sm: "10px"
  md: "14px"
  lg: "20px"
  xl: "28px"
  2xl: "36px"
  pill: "999px"
spacing:
  "1": "4px"
  "2": "8px"
  "3": "12px"
  "4": "16px"
  "5": "20px"
  "6": "24px"
  "8": "32px"
  "10": "40px"
  "12": "48px"
  "16": "64px"
  "20": "80px"
  "24": "96px"
  section: "112px"
  gutter: "24px"
components:
  button-primary:
    backgroundColor: "{colors.clay-deep}"
    textColor: "{colors.white}"
    typography: "{typography.body}"
    rounded: "{rounded.pill}"
    padding: "0 22px"
    height: "44px"
  button-primary-hover:
    backgroundColor: "{colors.clay-deeper}"
  button-primary-active:
    backgroundColor: "{colors.clay-darkest}"
  button-secondary:
    backgroundColor: "{colors.white}"
    textColor: "{colors.ink}"
    rounded: "{rounded.pill}"
    padding: "0 22px"
    height: "44px"
  button-secondary-hover:
    backgroundColor: "{colors.ink-wash}"
  button-ghost:
    textColor: "{colors.clay-deep}"
    rounded: "{rounded.pill}"
    padding: "0 22px"
    height: "44px"
  button-accent:
    backgroundColor: "{colors.amber}"
    textColor: "{colors.ink}"
    rounded: "{rounded.pill}"
    padding: "0 22px"
    height: "44px"
  button-accent-hover:
    backgroundColor: "{colors.amber-deep}"
  chip:
    backgroundColor: "{colors.white}"
    textColor: "{colors.ink-body}"
    typography: "{typography.label}"
    rounded: "{rounded.pill}"
    padding: "0 18px"
    height: "44px"
  chip-selected:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.paper}"
  badge-brand:
    backgroundColor: "{colors.clay-blush}"
    textColor: "{colors.clay-deeper}"
    typography: "{typography.label}"
    rounded: "{rounded.pill}"
    padding: "0 12px"
    height: "28px"
  card:
    backgroundColor: "{colors.white}"
    rounded: "{rounded.lg}"
    padding: "30px"
  card-brand:
    backgroundColor: "{colors.clay-blush}"
    rounded: "{rounded.lg}"
    padding: "30px"
  card-dark:
    backgroundColor: "{colors.clove}"
    textColor: "{colors.on-dark}"
    rounded: "{rounded.lg}"
    padding: "30px"
  input:
    backgroundColor: "{colors.white}"
    textColor: "{colors.ink}"
    typography: "{typography.body}"
    rounded: "{rounded.md}"
    padding: "0 16px"
    height: "44px"
  cta-band:
    backgroundColor: "{colors.clove}"
    textColor: "{colors.on-dark}"
    rounded: "{rounded.2xl}"
    padding: "72px"
---

# Design System: Impeccabyte

## Overview

**Creative North Star: "The Kitchen-Table Ledger"**

Every page should feel like sitting down with a business owner at their kitchen table, statement spread out between you, going through it line by line until nothing is a mystery. The materials are warm and domestic: cream paper, clay, amber lamplight, the deep brown of an old wooden table. Nothing is cold, gray or corporate, because the person across the table has been surprised by fees before and needs to feel they are being dealt with straight.

The system is calm and generous rather than dense. Sections breathe (112px apart on desktop), headlines are set in a bookish serif that reads like a well-kept ledger, and the working text is a plain, friendly grotesk. Figures, fees and fine print are treated as content worth reading, not something to tuck away: receipts and statements appear as illustrations, and totals get the same typographic care as headlines.

Brand colour is used like a pen mark on the page: the italic clay word in a headline, a primary button, an icon tile. Dark clove bands close a page the way a ledger closes a month, with one clear next step.

**Key Characteristics:**
- Warm paper page (never pure white), white cards laid on top like sheets.
- Newsreader serif headlines at 600 with one italic clay word as the signature.
- Hanken Grotesk for everything that works: body, labels, buttons, navigation.
- Full-pill buttons, chips and badges; 20px-cornered cards.
- Soft, warm-brown shadows; deep clove for the only heavy surfaces.
- Accessible by construction: 44px controls, visible focus halo, state never shown by colour alone.

## Colors

A warm earth palette of clay, amber and clove on cream paper, with brown-tinted inks in place of any gray. Every colour comes from `web/themes/custom/impeccabyte/css/tokens.css` (exported from the design system's tokens.json); component CSS never uses raw hex.

### Primary
- **Kiln Clay** (`clay`): the brand colour. The logo, the italic accent word in hero headlines, large display text, chart highlights and decorative accents. White text on it is only 4.15:1, so it never carries small white labels.
- **Fired Clay** (`clay-deep`): the working brand colour. Primary button fill, text links, eyebrows, selected outlines, the focus ring and any small white-on-clay element (step dots, checks, solid badges). White on it is 5.7:1.
- **Burnt Clay** (`clay-deeper`) and **Clay Shadow** (`clay-darkest`): primary button hover and press, link hover, brand badge text.
- **Clay Blush** (`clay-blush`): brand-tinted surfaces: tinted sections, brand cards, icon tiles, active nav pill, the highlighted card line on a receipt.

### Secondary
- **Lamplight Amber** (`amber`): an accent only, always with ink text: the italic accent word on dark clove bands, accent buttons and badges, warning fills. Never the main call to action. **Deep Amber** (`amber-deep`) is its hover.

### Tertiary
- **Clove** (`clove`): the dark surface. CTA bands, the footer, dark cards and the terminal in hero art. **Clove Night** (`clove-night`) is the darker step beneath it.

### Neutral
- **Paper** (`paper`): the page background everywhere. Also the text colour on selected (ink) chips.
- **White** (`white`): cards, inputs, compact hero bands and white sections laid over paper.
- **Ledger Ink** (`ink`): headings and strong text. **Body Ink** (`ink-body`) for body copy, **Soft Ink** (`ink-soft`) for lead text and card bodies, **Muted Ink** (`ink-muted`) for meta lines, hints and fine print.
- **Outline Ink** (`ink-outline`): the minimum outline for controls (chips, secondary buttons, inputs, option cards, checkboxes) so they meet 3:1.
- **Rule Ink** (`ink-rule`): decorative card borders only. **Hairline** (`ink-hairline`) for dividers and section edges. **Ink Wash** (`ink-wash`) for sunken panels, device photo wells and hover fills.
- **On Dark** (`on-dark`): body text and form labels on clove.

### Status
- **Sage** (`sage`) for success and checklist ticks, **Brick** (`brick`) and **Deep Brick** (`brick-deep`) for danger, errors and required marks, **Slate** (`slate`) for info. Amber doubles as warning. Each has a `-50` subtle fill in tokens.css for callouts and badges.

### Named Rules
**The Paper, Not White Rule.** The page is always paper. White is for things laid on the page (cards, inputs, white bands), never the page itself. No pure black and no neutral gray anywhere; every dark and every gray is brown-tinted ink.

**The Two Clays Rule.** Clay is the brand, Fired Clay does the work. If white text sits on it and is under 19px bold, it must be Fired Clay.

**The Amber Is a Garnish Rule.** Amber is never the primary call to action and never carries white text.

## Typography

**Display Font:** Newsreader (with Georgia, Times New Roman)
**Body Font:** Hanken Grotesk (with the system sans stack)
**Label/Mono Font:** JetBrains Mono, used only for receipts, URLs and figures inside illustrations

**Character:** A bookish, slightly literary serif for headings set against a clear, friendly grotesk for everything you read or press. The serif makes the company sound considered; the grotesk keeps it plain-spoken.

### Hierarchy
- **Display** (Newsreader 600, 62px, or 48px on interior heroes; clamps down from 38px on phones; line-height 1.05, tracking -0.025em): hero headlines only, one `h1` per page.
- **Headline** (Newsreader 600, 38px or 30px, clamps 30 to 38; line-height 1.22, tracking -0.02em): section headings (`h2`), CTA band headlines.
- **Title** (Hanken Grotesk 600, 20px, or 18px on device cards; line-height 1.22): card titles (`h3`). Card titles are sans, not serif.
- **Lead** (Hanken Grotesk 400, 18px, line-height 1.6, Soft Ink): the paragraph under a hero or section heading, held to about 540 to 640px.
- **Body** (Hanken Grotesk 400, 16px, line-height 1.6, Body Ink): running text.
- **Label** (Hanken Grotesk 600, 14px): form labels, chips, badges, meta lines, supporting text.
- **Eyebrow** (Hanken Grotesk 600, 12px, uppercase, tracking 0.12em, Fired Clay): the small line above a section heading.

### Named Rules
**The One Italic Word Rule.** Each hero headline carries exactly one italic word, set in Newsreader at weight 500 in Kiln Clay (Lamplight Amber on clove). It is the signature; never two, never in body text.

**The Fixed Scale Rule.** Sizes come only from the scale: 62 or 48 hero, 38 or 30 section, 18 lead, 16 body, 14 supporting, 12 eyebrow. No off-scale sizes in component CSS.

## Layout

A centred 1240px container with a 24px gutter (20px on phones). Sections stack vertically with 112px of space above and below, dropping to 64px under 760px. Inside a section, the header and its content sit 48px apart (32px on phones).

Content grids are auto-fit columns with a 300px minimum and 24px gaps, so cards reflow from four or three across to one without breakpoints of their own. Heroes split text and media into two flexible 460px-basis columns that wrap to a single column; the text column always comes first. Section headers keep their text to 680px and can push a link to the right edge.

Bands alternate between paper, white (with hairline rules top and bottom) and clay blush, and a page usually ends on a clove CTA band before the clove footer.

Breakpoints: navigation collapses to a drawer below 1180px; layouts tighten below 760px; the header drops its "Get a quote" button below 520px. Design checks happen at 390px, 834px and 1440px. Nothing may scroll sideways at 390px: wide tables scroll inside their own rounded box, and chip rows scroll sideways on phones.

## Elevation & Depth

Soft paper layers. Cards sit just above the page like sheets laid on a table, each with a 1px Rule Ink border and a gentle, warm-brown shadow (all shadows are tinted with the clove brown, never gray or black). Interactive cards lift 3px on hover and take a deeper shadow. Illustrations that stand for real objects (the receipt, the dashboard and terminal in hero art, hero photos) sit highest, on the extra-large shadow. Clove bands and the footer are the only heavy surfaces; they carry no shadow because their weight comes from colour.

### Shadow Vocabulary
- **Resting sheet** (`--shadow-sm`): every default card.
- **Raised sheet** (`--shadow-md`): raised cards and dropdowns.
- **Lifted sheet** (`--shadow-lg`): interactive cards on hover, hero photos.
- **Object** (`--shadow-xl`): illustrated objects (receipt, dashboard, terminal).
- **Clay glow** (`--shadow-brand`): the primary button only.

### Named Rules
**The Warm Shadow Rule.** Every shadow uses the clove-brown tint from tokens.css. A gray or black shadow breaks the paper illusion.

## Shapes

Soft and round, never sharp. Everything you press or that labels something is a full pill: buttons, chips, badges, nav items, the compare toggle, the hamburger. Containers use generous, consistent corners: 20px for cards, tables and receipts, 28px for hero images and illustrated panels, 36px for the CTA band. Inputs use 14px so they read as fields rather than buttons. Checkboxes are 6px-cornered 24px squares inside a 44px row. Icon tiles are 48px squares at 14px. Borders are thin (1px on cards, 1.5px on controls) and warm.

## Components

Friendly and sure-footed: rounded, generous and clearly labelled. Approachable, never cute.

### Buttons
- **Shape:** full pill (999px). Sizes 36px (small), 44px (default) and 52px (large), 22px side padding, Hanken Grotesk 600 at 16px, icon left or right with an 8px gap.
- **Primary:** Fired Clay fill, white label, a soft clay glow. Hover Burnt Clay, press Clay Shadow.
- **Secondary:** white fill, Ledger Ink label, 1.5px Outline Ink border (darker on hover).
- **Ghost:** no fill, Fired Clay label.
- **Accent:** Lamplight Amber fill with Ledger Ink label. Secondary emphasis only.
- **On dark:** transparent with a translucent on-dark outline, for use on clove bands.
- **Hover / Focus:** colour changes over 140ms with a soft ease-out. Focus is a 2px Fired Clay outline, 2px offset, with a 5px white halo so it shows on paper, white and clove. A button rendered as a link keeps its own label colour on hover.
- **Labels** start with a verb, in sentence case ("Get a quote", "See pricing").

### Chips
- **Style:** white pill, 44px tall, 18px padding, 1px Outline Ink border, 14px medium label in Body Ink. Hover turns the border Fired Clay.
- **State:** selected chips flip to a Ledger Ink fill with paper text and show a check icon, so the state is never colour alone. On phones a chip row scrolls sideways instead of wrapping.

### Badges
- **Style:** pill, 28px (24px small), 14px semibold label. Tinted by default (Clay Blush with Burnt Clay text for brand), or solid. Variants: neutral, brand, accent, success, danger, warning, info; optional leading dot.

### Cards / Containers
- **Corner Style:** 20px.
- **Background:** white by default; Clay Blush for brand tone; Clove with on-dark text for dark tone.
- **Shadow Strategy:** resting sheet by default, flat or raised as variants; interactive cards lift on hover (see Elevation & Depth).
- **Border:** 1px Rule Ink (decorative only).
- **Internal Padding:** 20px, 30px or 40px. Media cards run their photo edge to edge with 26px of padding below.
- **Feature card:** a 48px Clay Blush icon tile with a Fired Clay Lucide icon, sans title, body and an arrow link pinned to the bottom.
- **Device card:** product photo on an Ink Wash well, the whole card is one link target, prices on a hairline-ruled footer.

### Inputs / Fields
- **Style:** white field, 44px tall (52px large), 16px padding, 1.5px Outline Ink border, 14px corners, 16px text. Placeholder in Ink 500.
- **Labels:** always visible, 14px semibold above the field; required marks in Deep Brick; hints in Muted Ink. Labels on clove use On Dark.
- **Focus:** the shared Fired Clay outline with white halo. Hover darkens the border to Muted Ink.
- **Error:** Deep Brick border and a message with a round "!" mark. **Disabled:** 50% opacity.
- **Select:** custom chevron icon at the right, 44px of right padding.

### Navigation
- **Header:** sticky, 76px tall, on paper with a hairline bottom rule. Logo left, centred pill nav items (Hanken Grotesk 500, 16px, 44px tall), "Log in" and the primary "Get a quote" button right.
- **States:** hover fills Ink Wash; open or current items turn Fired Clay on Clay Blush, and the chevron rotates 180°.
- **Mega menu:** fades in and moves down 8px over 220ms, reversing on close; closes on Escape or when focus leaves.
- **Mobile:** below 1180px a 44px pill hamburger opens a drawer that grows by height over 220ms. All motion stops under reduced motion.

### Footer
Clove background, on-dark text, Clove 100 links that turn white and underline on hover. A pitch and the newsletter signup sit above four link columns, then the address and the required disclosure line.

### CTA Band (signature)
A rounded (36px) Clove panel inside the container, with a serif headline whose italic word is Lamplight Amber, an on-dark body line and a primary plus on-dark button pair. It is the closing note of most pages.

### Receipt and Statement Art (signature)
Payments explained with the merchant's own objects: a white receipt on a 20px sheet with the object shadow, a Newsreader shop name, JetBrains Mono line items, dashed rules and the card line highlighted in Clay Blush; or a dashboard window beside a Clove terminal with a paper screen. Use these instead of abstract graphics when a page explains money.

## Do's and Don'ts

### Do:
- **Do** take every colour, size, space, radius and shadow from `web/themes/custom/impeccabyte/css/tokens.css`.
- **Do** use Fired Clay (`--clay-600`) for the primary button, hovering to `--clay-700` and pressing to `--clay-800`.
- **Do** set h1 to h3 in Newsreader 600, with exactly one italic clay word in a hero headline.
- **Do** make every button, chip and badge a full pill, and every card 20px with a 1px `--ink-200` border and `--shadow-sm`.
- **Do** give controls at least 44px and outline them in `--ink-400` or darker; keep 24px as the absolute minimum target.
- **Do** use Lucide icons, outline only, 1.75 stroke.
- **Do** animate colour over 140ms and movement over 220ms with the soft ease-out, and switch all motion off under reduced motion.
- **Do** show state with fill, outline or an icon as well as colour.

### Don't:
- **Don't** use pure white as the page background, pure black, or any neutral gray.
- **Don't** put small white text on Kiln Clay (`--clay-500`); it is 4.15:1.
- **Don't** make amber the main call to action or put white text on it.
- **Don't** use `--ink-200` for control outlines; it is for decorative card borders only.
- **Don't** use emoji or unicode symbols as icons, or filled icon styles.
- **Don't** add bounce or overshoot to motion.
- **Don't** set off-scale type sizes or raw hex values in component CSS.
