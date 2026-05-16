# Design System Inspired by June

## 1. Visual Theme & Atmosphere

June's design system embodies a modern, approachable aesthetic that prioritizes clarity and warmth in the B2B SaaS space. The visual language combines deep, sophisticated navy tones with subtle accent colors and generous whitespace, creating an environment that feels simultaneously professional and welcoming. The system employs a rounded typeface for headlines, softening the technical nature of product analytics while maintaining enterprise credibility. This design approach reflects June's core mission: making complex data analytics accessible and inviting rather than intimidating.

**Key Characteristics**

- Deep navy primary palette (`#151531`, `#2A2A63`) establishing trust and professionalism
- Soft, rounded typography for human-centric communication
- Generous whitespace and light neutral backgrounds reducing cognitive load
- Subtle accent colors (purple, cyan, green) for highlighting and status indication
- Minimal use of shadows and depth for clean, contemporary aesthetics
- Focus on readability with high contrast between text and backgrounds

## 2. Color Palette & Roles

### Primary
- **Deep Navy** (`#151531`): Primary brand color for headings, primary UI elements, and high-emphasis text; used most frequently across the interface
- **Navy Accent** (`#2A2A63`): Secondary deep tone for subheadings, navigation items, and medium-emphasis content

### Accent Colors
- **Brand Purple** (`#6868F7`): Primary interactive accent for links, highlights, and call-to-action elements
- **Light Cyan** (`#C9F0FF`): Secondary accent for success highlights and informational states
- **Light Green** (`#DAF9D4`): Tertiary accent for positive confirmation and success indicators
- **Lavender** (`#F9E2FB`): Subtle background accent for callout sections and gentle emphasis

### Interactive
- **Warning Orange** (`#FFA340`): Warning states and cautionary messaging
- **Error Red** (`#D63A35`): Error states, validation failures, and destructive actions

### Neutral Scale
- **Light Gray** (`#E9ECEF`): Primary neutral surface, used most frequently as backgrounds and borders
- **Lighter Gray** (`#F1F3F5`): Subtle background tint for secondary surfaces
- **Lightest Gray** (`#F8F9FA`): Minimal background variation for tertiary surfaces
- **Very Light Gray** (`#FAFAFA`): Card and content container backgrounds
- **Medium Gray** (`#CED4DA`): Border and divider lines
- **Dark Gray** (`#343A40`): Secondary text, captions, and muted elements
- **Black** (`#000000`): Strong emphasis, rare use for maximum contrast text
- **White** (`#FFFFFF`): Primary background for main content areas and cards

### Surface & Borders
- **Border Default** (`#E9ECEF`): Standard border color for inputs, cards, and containers
- **Surface Primary** (`#FFFFFF`): Main content background
- **Surface Secondary** (`#F9E2FB`): Alternative background for highlighted sections

### Semantic / Status
- **Success** (`#DAF9D4`): Positive completion and success states
- **Warning** (`#FFA340`): Attention-requiring warnings and notifications
- **Error** (`#D63A35`): Failures, errors, and destructive confirmations

## 3. Typography Rules

### Font Family
**Primary:** SF Pro Rounded with fallback stack: `-apple-system, BlinkMacSystemFont, "SF Pro Rounded", "Helvetica Neue", sans-serif`

**Secondary:** Inter with fallback stack: `"Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", sans-serif`

### Hierarchy

| Role | Font | Size | Weight | Line Height | Letter Spacing | Notes |
|------|------|------|--------|-------------|----------------|-------|
| Display / H1 | SF Pro Rounded | 60px | 900 | 60px | 0px | Hero headlines, major page titles |
| Heading / H2 | SF Pro Rounded | 40px | 900 | 50px | 0px | Section headers, prominent subheadings |
| Subheading / H3 | SF Pro Rounded | 24px | 700 | 28px | 0px | Card titles, section subheadings |
| Body / Paragraph | Inter | 16px | 400 | 24px | 0px | Primary body text, list items |
| Body Strong | SF Pro Rounded | 16px | 700 | 24px | 0px | Emphasized body text, labels |
| Button | Inter | 16px | 600 | 24px | 0px | CTA and interactive button text |
| Caption | Inter | 14px | 400 | 20px | 0px | Metadata, timestamps, secondary info |
| Code | Inter | 13px | 400 | 18px | 0px | Code blocks and inline code |

### Principles
- **Rounded for Warmth:** SF Pro Rounded used exclusively for all headings and labels to humanize technical content
- **Clean Body Text:** Inter provides excellent legibility for long-form content and interface labels
- **Weight Contrast:** Bold headlines (900 weight) contrast dramatically with regular body (400 weight) for clear hierarchy
- **Consistent Line Height:** 1.5x multiplier of font size maintained across most roles for comfortable reading
- **No Letter Spacing:** Default tracking maintains modern, tight visual presentation

## 4. Component Stylings

### Buttons

**Primary Button**
- Background: `#6868F7`
- Text Color: `#FFFFFF`
- Font: Inter, 16px, weight 600
- Padding: `12px 24px`
- Border Radius: `8px`
- Border: None
- Box Shadow: `rgba(0, 0, 0, 0.1) 0px 1px 3px 0px`
- Hover State: Background `#5555E0`, Box Shadow `rgba(0, 0, 0, 0.15) 0px 4px 8px 0px`
- Active State: Background `#4444CC`
- Disabled State: Background `#CED4DA`, Text Color `#343A40`, Box Shadow none

**Secondary Button**
- Background: `#F1F3F5`
- Text Color: `#151531`
- Font: Inter, 16px, weight 600
- Padding: `12px 24px`
- Border Radius: `8px`
- Border: `1px solid #E9ECEF`
- Box Shadow: none
- Hover State: Background `#E9ECEF`, Border `1px solid #CED4DA`
- Active State: Background `#DEE2E6`
- Disabled State: Background `#F8F9FA`, Text Color `#CED4DA`, Border `1px solid #E9ECEF`

**Ghost Button**
- Background: transparent
- Text Color: `#151531`
- Font: Inter, 16px, weight 600
- Padding: `12px 24px`
- Border Radius: `8px`
- Border: `1px solid transparent`
- Box Shadow: none
- Hover State: Background `rgba(104, 104, 247, 0.1)`, Border `1px solid #6868F7`
- Active State: Background `rgba(104, 104, 247, 0.2)`, Border `1px solid #6868F7`
- Disabled State: Text Color `#CED4DA`, Border `1px solid transparent`

### Cards & Containers

**Card Default**
- Background: `#FFFFFF`
- Border: `1px solid #E9ECEF`
- Border Radius: `8px`
- Padding: `24px`
- Box Shadow: `rgba(13, 19, 27, 0.05) 0px 2px 1px 0px`
- Margin Bottom: `24px`

**Card Elevated**
- Background: `#FFFFFF`
- Border: `1px solid #E9ECEF`
- Border Radius: `8px`
- Padding: `24px`
- Box Shadow: `rgba(13, 19, 27, 0.1) 0px 2px 10px 0px, rgba(13, 19, 27, 0.2) 0px 0px 2px 0px`

**Card Accent Background**
- Background: `#F9E2FB`
- Border: `1px solid rgba(86, 0, 89, 0.2)`
- Border Radius: `8px`
- Padding: `24px`
- Box Shadow: none

**Container / Section**
- Background: `#F8F9FA`
- Padding: `64px 24px`
- Margin: `0px`
- Border: none

### Inputs & Forms

**Input Field - Default**
- Background: `#FFFFFF`
- Border: `1px solid #E9ECEF`
- Border Radius: `6px`
- Padding: `12px 16px`
- Font: Inter, 16px, weight 400
- Text Color: `#151531`
- Placeholder Color: `#999999`
- Line Height: `24px`
- Transition: `border-color 0.2s ease`

**Input Field - Focus**
- Background: `#FFFFFF`
- Border: `1px solid #6868F7`
- Box Shadow: `0px 0px 0px 3px rgba(104, 104, 247, 0.1)`
- Text Color: `#151531`

**Input Field - Error**
- Background: `#FFFFFF`
- Border: `1px solid #D63A35`
- Box Shadow: `0px 0px 0px 3px rgba(214, 58, 53, 0.1)`
- Text Color: `#151531`

**Label**
- Font: SF Pro Rounded, 14px, weight 700
- Text Color: `#151531`
- Margin Bottom: `8px`
- Display: block

**Checkbox / Radio - Default**
- Width: `20px`
- Height: `20px`
- Border: `2px solid #CED4DA`
- Border Radius: `4px`
- Background: `#FFFFFF`
- Cursor: pointer

**Checkbox / Radio - Checked**
- Background: `#6868F7`
- Border: `2px solid #6868F7`
- Check Icon Color: `#FFFFFF`

### Navigation

**Navigation Bar**
- Background: `#FFFFFF`
- Border Bottom: `1px solid #E9ECEF`
- Padding: `16px 24px`
- Height: `64px`
- Display: flex
- Align Items: center
- Justify Content: space-between

**Navigation Link - Default**
- Color: `#343A40`
- Font: Inter, 16px, weight 400
- Padding: `8px 12px`
- Text Decoration: none
- Border Bottom: `2px solid transparent`
- Transition: `border-color 0.2s ease`

**Navigation Link - Hover**
- Color: `#151531`
- Border Bottom: `2px solid #6868F7`

**Navigation Link - Active**
- Color: `#6868F7`
- Border Bottom: `2px solid #6868F7`
- Font Weight: 600

**Dropdown Menu**
- Background: `#FFFFFF`
- Border: `1px solid #E9ECEF`
- Border Radius: `8px`
- Box Shadow: `rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px`
- Padding: `8px 0px`
- Z Index: 1000

**Dropdown Item**
- Padding: `12px 16px`
- Color: `#343A40`
- Font: Inter, 14px, weight 400
- Cursor: pointer
- Transition: `background-color 0.15s ease`

**Dropdown Item - Hover**
- Background: `#F1F3F5`
- Color: `#151531`

### Links

**Link - Default**
- Color: `#151531`
- Font: SF Pro Rounded, 16px, weight 700
- Text Decoration: underline
- Transition: `color 0.2s ease`

**Link - Hover**
- Color: `#6868F7`

**Link - Muted**
- Color: `#343A40`
- Font: Inter, 16px, weight 400
- Text Decoration: none

**Link - Muted Hover**
- Color: `#151531`
- Text Decoration: underline

## 5. Layout Principles

### Spacing System
**Base Unit:** 8px

**Scale with Usage Context:**
- **Micro (4px):** Inline icon spacing, minimal internal component padding
- **Compact (8px):** Form input spacing, compact list items, minimal button padding
- **Comfortable (12px):** Standard internal component padding, form label margins
- **Standard (16px):** Default padding for content areas, button padding horizontal
- **Medium (20px):** Card title spacing, section dividers
- **Large (24px):** Card padding, standard section margins, content spacing
- **XL (32px):** Section separators, major content breaks
- **2XL (40px):** Large section margins
- **3XL (48px):** Hero section padding, major layout sections
- **4XL (64px):** Major padding for full-width sections
- **5XL (80px):** Large vertical spacing between sections
- **6XL (96px):** Hero and entrance spacing

### Grid & Container
- **Max Content Width:** 1200px for standard page layouts
- **Full Bleed Sections:** 100% viewport width with internal padding of `64px` horizontal
- **Column Strategy:** Flexible 12-column grid for desktop; CSS Grid preferred for component layout
- **Gutters:** 24px between columns
- **Mobile Container:** 100% width with `16px` horizontal padding
- **Tablet Container:** 100% width with `24px` horizontal padding

### Whitespace Philosophy
June's design prioritizes generous whitespace to reduce cognitive load in analytics-heavy interfaces. Spacing is used strategically to create visual breathing room, group related content, and establish clear hierarchy. Large vertical spacing (`64px–96px`) separates major sections, while `24px` padding defines card and container boundaries. Compact elements maintain `12px–16px` internal spacing. This approach creates an open, inviting feel that contrasts with typically dense data visualization environments.

### Border Radius Scale
- **Minimal (2px):** Subtle backgrounds, very small components
- **Small (4px):** Checkboxes, small toggles, tag badges
- **Standard (6px):** Input fields, small cards
- **Medium (8px):** Buttons, standard cards, dropdown menus, modals
- **Large (12px):** Large card components, emphasis containers
- **Full (9999px):** Fully rounded pills for buttons and badges

## 6. Depth & Elevation

| Level | Treatment | Use |
|-------|-----------|-----|
| Flat (0) | No shadow, transparent or solid fill | Base surfaces, inline content, text |
| Raised (sm) | `rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px` | Dropdown menus, floating buttons, tooltips |
| Elevated (md) | `rgba(13, 19, 27, 0.25) 0px 0px 1px 0px, rgba(13, 19, 27, 0.05) 0px 2px 1px 0px` | Standard cards, default button states, form inputs on hover |
| Lifted (lg) | `rgba(13, 19, 27, 0.1) 0px 2px 10px 0px, rgba(13, 19, 27, 0.2) 0px 0px 2px 0px` | Modal dialogs, elevated cards, prominent cards, navigation overlays |

**Shadow Philosophy**

June employs a subtle, layered shadow system that suggests depth without heaviness. Shadows are minimal and use soft, semi-transparent blacks to create spatial relationships. This conservative approach maintains the clean, modern aesthetic while preserving visual hierarchy. Elevated components use compound shadows with a hard shadow line (creating definition) and a soft blur (creating depth), resulting in a sophisticated, contemporary look.

## 7. Do's and Don'ts

### Do
- Use deep navy (`#151531`, `#2A2A63`) for all primary headings and emphasis text
- Employ SF Pro Rounded for headlines and interactive labels to maintain visual warmth
- Use `24px` padding as the standard internal spacing for cards and containers
- Apply primary purple (`#6868F7`) exclusively to interactive elements: buttons, links, and highlights
- Combine light backgrounds (`#F1F3F5`, `#F9E2FB`) with carefully considered accent colors for emphasis sections
- Maintain consistent `8px` base spacing units throughout all components
- Use high contrast between text and background (minimum 4.5:1 for accessibility)
- Apply subtle box shadows (sm/md level) to elevate interactive and floating components
- Group related form inputs with consistent `12px` internal spacing and `24px` external margins
- Use Inter exclusively for body text, captions, and all non-headline content

### Don't
- Don't use brand purple for text content — reserve it strictly for interactive elements
- Don't apply shadows heavier than the lg level; maintain visual lightness
- Don't mix SF Pro Rounded and Inter within the same text element
- Don't use border colors other than `#E9ECEF` or `#CED4DA` for standard component borders
- Don't create custom colors — use only the established semantic palette
- Don't reduce spacing below `8px` base unit; use multiples of 8px only
- Don't apply negative padding or margin; use consistent positive spacing hierarchy
- Don't stack more than two levels of elevation; use clear visual separation instead
- Don't use error red (`#D63A35`) for non-destructive warnings; use warning orange (`#FFA340`)
- Don't create text smaller than `13px` for body content; maintain readability standards

## 8. Responsive Behavior

### Breakpoints

| Name | Width | Key Changes |
|------|-------|-------------|
| Mobile | 320px–599px | Single column, `16px` padding, H1 `40px`, H2 `28px`, font sizes `14px–16px`, touch targets `44px×44px` |
| Tablet | 600px–1023px | Two columns, `24px` padding, H1 `48px`, H2 `32px`, standard font sizes, touch targets `44px×44px` |
| Desktop | 1024px+ | Multi-column layout, `64px` padding, full H1 `60px`, H2 `40px`, standard typography, touch targets minimum `40px×40px` |
| Large Desktop | 1440px+ | Max width container `1200px` centered, `96px` padding, full typography scale |

### Touch Targets
- **Minimum Height & Width:** `44px×44px` for all interactive elements (buttons, links, inputs)
- **Recommended:** `48px×48px` for primary actions and high-frequency interactions
- **Compact:** `36px×36px` only for secondary actions or when space is severely constrained
- **Spacing Between Targets:** Minimum `12px` to prevent accidental taps

### Collapsing Strategy
- **Mobile (320–599px):**
  - Stack all layout columns vertically
  - Collapse navigation into hamburger menu
  - Reduce padding to `16px` horizontally
  - Reduce headline sizes: H1 `40px`, H2 `28px`, H3 `20px`
  - Stack form inputs vertically with `16px` margin between
  - Use full-width cards with no additional spacing
  - Collapse hero sections to single column

- **Tablet (600–1023px):**
  - Two-column grid layouts acceptable
  - Show primary navigation; collapse secondary into dropdowns
  - Padding `24px` horizontally
  - Moderate headline sizes: H1 `48px`, H2 `32px`
  - Two-column form layouts supported
  - Cards maintain `24px` padding

- **Desktop (1024px+):**
  - Full multi-column layouts enabled
  - All navigation visible
  - Standard padding and spacing
  - Full typography hierarchy
  - Maximum content width `1200px`

## 9. Agent Prompt Guide

### Quick Color Reference
- **Primary CTA:** Brand Purple (`#6868F7`) — all interactive primary actions
- **Primary Background:** White (`#FFFFFF`) — main content surfaces
- **Secondary Background:** Light Gray (`#E9ECEF`) — alternate sections, disabled states
- **Heading Text:** Deep Navy (`#151531`) — all headlines and emphasis
- **Body Text:** Dark Gray (`#343A40`) — primary body and secondary navigation
- **Success Indicator:** Light Green (`#DAF9D4`) — positive confirmations
- **Warning Indicator:** Warning Orange (`#FFA340`) — cautionary states
- **Error Indicator:** Error Red (`#D63A35`) — failures and destructive actions
- **Border Color:** Light Gray (`#E9ECEF`) — component borders and dividers
- **Card Background:** White (`#FFFFFF`) — card and container fills
- **Accent Background:** Lavender (`#F9E2FB`) — callout and highlight sections

### Iteration Guide

1. **Always use `#151531` for headline text and `#343A40` for body copy** — establish clear text hierarchy immediately
2. **Brand purple (`#6868F7`) is reserved for interactive elements only** — buttons, links, hover states, never for text content
3. **Maintain 8px base spacing:** all margins and padding must be multiples of 8px (8, 12, 16, 20, 24, 32, 40, 48, 64, 80, 96)
4. **Apply SF Pro Rounded exclusively to headings, labels, and callouts** — maintain consistent warmth in UI
5. **Use Inter for all body text, links, and utility copy** — ensures readability and neutrality
6. **Card padding is always 24px** — standard container internal spacing for consistency
7. **Button padding is 12px vertical × 24px horizontal** — maintains touch target minimums
8. **Primary buttons use purple background with white text** — second option is light gray secondary button
9. **Border radius standard is 8px** — apply to buttons, cards, inputs, modals; use 6px only for inputs, 4px for small components
10. **Shadows follow three-level system:** sm for floats, md for raised cards, lg for modals only — never create custom shadow values
11. **Responsive breakpoints are 320px (mobile), 600px (tablet), 1024px (desktop)** — adjust column grids and spacing accordingly
12. **Minimum touch target is 44px×44px** — all interactive elements on mobile must meet this minimum
13. **Maximum content width is 1200px** — center containers beyond this width with equal side padding
14. **Form labels use SF Pro Rounded 14px bold** — inputs use Inter 16px regular for text entry
15. **Use semantic colors consistently:** green for success, orange for warning, red for error — no custom status color variations