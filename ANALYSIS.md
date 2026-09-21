# DZURRIYYATUL QUR'AN ACADEMIC — Stage 1 Pre-Implementation Analysis

Required output per Master Prompt §63, produced before any theme code was written.

## 1. Repository Audit

Connected folder: `E:\BeyondDigi\DzurriyyatulQuran\template`

```
template/
└── design/
    ├── code.html    (93,031 bytes — Stitch static export, 1,574 lines)
    ├── DESIGN.md    (13,571 bytes — design-token spec + brand rationale)
    └── screen.png   (227,765 bytes — reference screenshot)
```

Findings:
- No existing WordPress installation, theme, child theme, or plugin anywhere in the connected folder.
- No `package.json`, `composer.json`, build tooling, or JS/CSS build pipeline.
- No pre-existing assets (images/fonts/icons) beyond the two files above — `screen.png` is a visual reference only, not a production asset.
- `code.html` references only remote assets: Google Fonts (Playfair Display, Plus Jakarta Sans, Public Sans — unused, Material Symbols Outlined — imported but **never referenced** in the markup, dead weight), Font Awesome 6.5.1 via cdnjs, Tailwind CSS via the `cdn.tailwindcss.com` runtime compiler, and ~9 `lh3.googleusercontent.com` placeholder photos (hero visuals ×3, mentor portraits ×4, header/footer logo).
- This is a **greenfield build**: the theme is created fresh under `template/dzurriyyatul-academic/`, with `design/` preserved untouched as the reference.

## 2. Design Analysis — Component Inventory

Extracted from `code.html` in document order, each mapped to its anchor id where present:

| # | Component | Anchor | Notes |
|---|---|---|---|
| 1 | Announcement bar | — | Affiliation/legal line, hours, WhatsApp hotline |
| 2 | Main sticky navigation | — | Logo, 8 links, WhatsApp CTA, mobile burger |
| 3 | Mobile navigation drawer | — | JS-injected drawer, closes on link click |
| 4 | Hero carousel (3 slides) | `#beranda` | Tab bar + arrow controls + dot indicators + 7s autoplay |
| 5 | Value pillars strip (×5) | — | Profesional/Edukatif/Transparan/Fleksibel/Berintegritas |
| 6 | Legality & trust banner | `#legalitas` | Kemenkumham registration number |
| 7 | Services grid (×9 cards) | `#layanan` | Icon, title, description, 2 feature bullets |
| 8 | Diagnostic clinic (×4 cards + callout) | `#solusi` | "Masalah 0X" + "Solusi Kami" |
| 9 | Academic integrity manifesto | `#integritas` | Anti-joki warning, do/don't grid, pledge panel |
| 10 | Packages/pricing (×3 tiers) | `#paket` | Middle tier visually featured |
| 11 | Mentor corps (×4 cards) | `#mentor` | Photo, name, role, specialization, 2 badges |
| 12 | Testimonials (×3 cards) | — | Star rating, quote, initials avatar |
| 13 | FAQ (×4 items) | `#faq` | **Static in source** — always expanded, no toggle JS |
| 14 | Final CTA | — | Dark green band, gold primary button |
| 15 | Footer (4 columns + copyright bar) | — | Brand, services links, nav links, contact |

Design-to-WordPress mapping:

```
STITCH COMPONENT              → WP COMPONENT           → TEMPLATE PART                          → DATA SOURCE
Announcement bar               Template part            template-parts/header/announcement-bar   Theme Settings (options)
Main nav + mobile drawer       Template part + wp_nav_menu fallback  template-parts/header/navigation  Theme Settings + nav_menu('primary')
Hero carousel                  Template part + JS module template-parts/hero/hero-carousel.php     Theme Settings (3 hero slide groups)
Value pillars                  Template part (static, editable via filter)  template-parts/trust/value-pillars.php  Constants + filter hook
Legality banner                Template part            template-parts/legalitas/legality-banner.php  Theme Settings (legal fields)
Services grid                  Template part + WP_Query service CPT       template-parts/services/services-grid.php  `service` CPT
Diagnostic clinic              Template part (static content, filterable) template-parts/diagnosis/diagnostic-section.php  Filter hook (fixed 4-step editorial content)
Integrity manifesto            Template part            template-parts/integrity/integrity-manifesto.php  Static (brand-critical, filterable)
Packages grid                  Template part + WP_Query package CPT       template-parts/packages/packages-grid.php  `package` CPT
Mentor corps                   Template part + WP_Query mentor CPT        template-parts/mentors/mentors-grid.php  `mentor` CPT
Testimonials                   Template part + WP_Query testimonial CPT   template-parts/testimonials/testimonials-grid.php  `testimonial` CPT
FAQ                            Template part + WP_Query faq CPT           template-parts/faq/faq-accordion.php  `faq` CPT
Final CTA                      Template part            template-parts/cta/final-cta.php          Theme Settings (WhatsApp helper)
Footer                         Template part            template-parts/footer/site-footer.php     Theme Settings + `footer` nav menu
```

## 3. Theme File Tree (implemented)

```
dzurriyyatul-academic/
├── style.css                     Theme header (name, textdomain dzurriyyatul-academic) + skip-link/base reset
├── functions.php                 Bootstraps inc/*, theme supports, menus, sidebars
├── README.md                     Setup, admin guide, dev workflow, testing
├── ANALYSIS.md                   This document
├── inc/
│   ├── setup.php                 add_theme_support, menus, image sizes, excerpt length
│   ├── enqueue.php                Fonts (self-hosted-ready, google fonts enqueue), FA, CSS/JS enqueue+defer
│   ├── post-types.php             service, package, mentor, testimonial, faq CPTs + meta boxes
│   ├── settings.php               Settings API: Theme Options page (brand, contact, social, legal, hero slides)
│   ├── security.php               Header hardening, login/XML-RPC/file-edit lockdown, input helpers
│   ├── seo.php                    Meta description, canonical, Open Graph/Twitter cards, JSON-LD (Organization + FAQPage)
│   ├── helpers.php                dq_whatsapp_url(), icon renderer, safe getters
│   ├── template-functions.php     body_class hooks, nav fallback, pagination, breadcrumb
│   └── consultation-form.php      Nonce-protected AJAX handler for the consultation form
├── template-parts/
│   ├── header/announcement-bar.php
│   ├── header/navigation.php
│   ├── hero/hero-carousel.php
│   ├── trust/value-pillars.php
│   ├── legalitas/legality-banner.php
│   ├── services/services-grid.php
│   ├── diagnosis/diagnostic-section.php
│   ├── integrity/integrity-manifesto.php
│   ├── packages/packages-grid.php
│   ├── mentors/mentors-grid.php
│   ├── testimonials/testimonials-grid.php
│   ├── faq/faq-accordion.php
│   ├── cta/final-cta.php
│   ├── cta/consultation-form.php
│   └── footer/site-footer.php
├── assets/
│   ├── css/tokens.css             CSS custom properties from DESIGN.md
│   ├── css/base.css               Reset, typography, base elements, focus states, skip link
│   ├── css/components.css         Buttons, chips, cards, inputs, accordion, nav
│   ├── css/sections.css           Section-specific layout (.academic-hero, .academic-services, …)
│   ├── css/responsive.css         Breakpoint overrides (360/390/430/768/1024/1280/1440)
│   └── images/.gitkeep
├── js/
│   ├── navigation.js              Sticky nav, mobile drawer, focus trap
│   ├── hero.js                    Carousel logic, keyboard nav, reduced-motion, pause-on-hover/focus
│   ├── faq.js                     Accessible accordion (aria-expanded, single/multi open)
│   └── main.js                    Consultation form AJAX submit, smooth-scroll, misc
├── front-page.php
├── page.php
├── single.php
├── archive.php
├── single-service.php
├── archive-service.php
├── single-package.php
├── archive-package.php
├── 404.php
├── search.php
├── header.php
└── footer.php
```

## 4. WordPress Content Model

Native **Posts** → Articles (blog/`single.php`/`archive.php`).

Custom Post Types (all `public`, `show_in_rest`, `menu_icon` set, `supports => ['title','editor','thumbnail','page-attributes']` so admins get the native drag-orderable "Order" box for Display Order — no bespoke JS needed):

- **`service`** — meta: `_dq_short_description`, `_dq_icon` (FA class), `_dq_price_label`, `_dq_duration`, `_dq_features` (newline list), `_dq_cta_text`, `_dq_cta_url`, `_dq_featured` (checkbox).
- **`package`** — meta: `_dq_price`, `_dq_price_label`, `_dq_billing_type`, `_dq_features`, `_dq_cta_text`, `_dq_cta_url`, `_dq_featured`, `_dq_badge_text`.
- **`mentor`** — meta: `_dq_role`, `_dq_specialization`, `_dq_credentials` (newline → badge chips), `_dq_academic_id`, `_dq_institution`, `_dq_experience`.
- **`testimonial`** — meta: `_dq_program`, `_dq_graduation_year`, `_dq_initials`, `_dq_rating` (1–5).
- **`faq`** — meta: `_dq_category` (plain text grouping label).

All CPTs default to demo/placeholder-free: activation seeds nothing, admin must populate through wp-admin (§48 compliance — no fabricated mentors/testimonials/stats are ever hard-coded into templates).

## 5. Admin Settings Model (Settings → Academic Site Settings, Settings API, one options row `dq_theme_settings`)

Brand: tagline override, footer logo (media). Contact: WhatsApp number, WhatsApp default message, email, phone, address, operating hours. Social: Instagram, Facebook, TikTok, YouTube, Google Maps URL. Legal: entity name, AHU number, SK date, additional legal note. Hero: 3 repeat groups (badge_icon_emoji, badge_text, heading_line1, heading_line2_italic, tagline_quote, tags (CSV), description, primary_cta_label, primary_cta_url, secondary_cta_label, secondary_cta_url, image (media), image_alt, caption_badge, caption_text, quote_text, quote_author, check_item_1, check_item_2, metric_1_value, metric_1_label, metric_2_value, metric_2_label, metric_3_value, metric_3_label). Site title/tagline/logo/favicon use native WP Customizer (Site Identity) rather than duplicating that UI.

## 6. Asset Strategy

- Fonts: enqueued via `wp_enqueue_style` from Google Fonts with `display=swap`; Public Sans and Material Symbols dropped (unused in the source design → pure payload weight). Self-hosting via a font plugin is documented in README as an optional performance upgrade.
- Icons: Font Awesome 6.5.1 kept (required for visual fidelity — dozens of glyphs throughout) but loaded through `wp_enqueue_style` (no raw `<link>` in templates) with only the CSS bundle, not the JS kit.
- Images: no bundled binaries. `wp_get_attachment_image()` + `srcset`/`sizes` used everywhere content comes from a CPT/setting; the hero LCP image gets `fetchpriority="high"` and is **not** lazy-loaded, every other image below the fold gets `loading="lazy"`, `width`/`height` are always output to prevent CLS. Admins upload real photography through the Media Library; nothing is hot-linked from `googleusercontent.com`.
- Tailwind's CDN JIT compiler is **not** carried into WordPress (§13) — its utility vocabulary is translated into the BEM-ish component/section classes in `assets/css/*`.

## 7. JavaScript Architecture

Four small vanilla-JS modules (no framework, no bundler needed for Stage 1), each enqueued with `defer` and scoped with `document.querySelector` guards so a missing element never throws:
- `navigation.js` — sticky-nav shadow toggle, mobile drawer open/close, focus trap + `Escape` to close.
- `hero.js` — rebuilt from the source `<script>` block: tab/dot/arrow control, 7s autoplay via `setInterval`, pause on hover **and** on focus-within, full keyboard support (←/→), and an early `matchMedia('(prefers-reduced-motion: reduce)')` check that disables autoplay and cross-fades instantly.
- `faq.js` — turns the static always-open FAQ markup into a real accordion: `aria-expanded`, `aria-controls`, `hidden` attribute toggling, animates via CSS `grid-template-rows` (no JS height math), keyboard-operable by default because it's a real `<button>`.
- `main.js` — consultation form `fetch()` submit against `admin-ajax.php` with the localized nonce, inline validation messages, and anchor smooth-scroll for the in-page nav links.

## 8. CSS Architecture

Custom-property design tokens (`tokens.css`) generated 1:1 from `DESIGN.md`'s YAML front-matter (`--color-*`, `--font-*`, `--radius-*`, `--space-*`, `--shadow-*`), consumed by BEM-style component/section classes (`.academic-header`, `.academic-hero`, `.academic-service-card`, `.academic-package-card`, `.academic-mentor-card`, `.academic-faq`, `.academic-cta`, `.academic-footer`, …) in `components.css`/`sections.css`, with breakpoint overrides isolated in `responsive.css` at the exact widths in §43. No Tailwind utility classes are copied into PHP; `!important` is not used.

## 9. Security Strategy

Nonces (`wp_nonce_field`/`check_ajax_referer`) on the consultation form and every settings/meta-box save; `current_user_can()` gates on all admin saves; every echoed value passes through `esc_html`/`esc_attr`/`esc_url`/`wp_kses_post` at the point of output (never at the point of storage); all `$_POST` input is sanitized with the matching `sanitize_*` function before saving; no raw `$wpdb` queries (Settings API + `WP_Query`/post meta only, so no SQL-injection surface); `X-Content-Type-Options`, `Referrer-Policy`, and CSP-friendly practices are set in `inc/security.php`; XML-RPC and file editing from wp-admin are disabled; no secrets (SMTP creds, API keys) are ever hard-coded — `wp_mail()` only, SMTP left to a plugin/server config per §52.

## 10. SEO Strategy

Semantic landmark HTML (`header`/`nav`/`main`/`footer`), one `<h1>` per view, logical heading order per section; `inc/seo.php` outputs `<meta name="description">` (from excerpt or a settings fallback), canonical URL, Open Graph + Twitter Card tags, and JSON-LD for `Organization` (using the real legal fields from Theme Settings — never invented) and `FAQPage` (from the `faq` CPT, only when at least one FAQ is published). No fake ratings, review counts, or award schema are ever emitted (§39).

## 11. Testing Strategy

- **Static/positive**: `php -l` on every PHP file (ships clean in this build); manual WP smoke test checklist in README covering the §57 positive-test list (homepage, nav, mobile menu, carousel incl. keyboard, each CPT archive/single, FAQ accordion, consultation form success path, WhatsApp links).
- **Negative/security**: checklist covering invalid form data, missing/forged nonce, unauthorized meta-box save attempt, XSS payload in a text field (must render escaped), empty-state rendering for every CPT (no results ≠ fatal error), disabled/unpublished content correctly excluded from queries.
- **Responsive/visual**: manual diff against `design/code.html` and `screen.png` at 360/390/430/768/1024/1280/1440px per §43–44 (documented as a checklist in README since no live WP/browser environment is available in this analysis pass to automate it).
- **Performance**: Core Web Vitals checklist (LCP image not lazy-loaded, CLS-safe image dimensions, deferred JS, minimal blocking CSS) documented for post-deploy verification with real hosting/PageSpeed Insights.

## 12. Demo-Content Flags (§48)

Nothing in this build hard-codes the source's placeholder facts as real: the WhatsApp number, email, legal AHU number/date, mentor identities, testimonials, and hero metrics all come from empty Theme Settings / empty CPT archives on first activation — the site owner must fill them in through wp-admin before launch. README calls this out explicitly as a pre-launch checklist item.

---
Proceeding to implementation per the phase order in §62.
