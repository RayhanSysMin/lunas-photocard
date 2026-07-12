<?php
return [
  'key' => 'news-prothomalo-wc-auto-banner-cta',
  'name' => 'Prothom Alo (Classic, Auto Banner, Footer-only CTA)',
  'fields' => [
    // Core
    'logo'        => ['type'=>'image', 'label'=>'Logo (Center Divider)', 'default'=>''],
    'title_color' => ['type'=>'color', 'label'=>'Title Text Color',     'default'=>'#d9251d'],
    'bottom_bg'   => ['type'=>'color', 'label'=>'Body Background',      'default'=>'#ffffff'],
    'text_color'  => ['type'=>'color', 'label'=>'Body/Domain/CTA Color','default'=>'#111111'],

    // CTA (text-only)
    'cta_text'    => ['type'=>'text',  'label'=>'CTA Text',             'default'=>'বিস্তারিত কমেন্টে'],

    // Banner (Ad)
    'banner'      => ['type'=>'image', 'label'=>'Banner (Ad at End - 1080x150)', 'default'=>''],
    'banner_bg'   => ['type'=>'color', 'label'=>'Banner Background',             'default'=>'#f6f7f9'],
  ],

  'css' => '
/* Canvas */
.npp-card {
  position:relative;width:770px;height:1080px;background:#fff;overflow:hidden;
  font-family:\'SolaimanLipi\',sans-serif;color:{{opt.text_color}};border-radius:10px;
  display:flex;flex-direction:column;
}
.npp-body {position:relative;display:flex;flex-direction:column;height:100%;background:{{opt.bottom_bg}};}
.npp-section.npp-hero {position:relative;flex:1 1 auto;min-height:280px;}
.npp-image {position:absolute;inset:0;overflow:hidden;}
.npp-image img {width:100%;height:100%;object-fit:cover;object-position:center;display:block;}

/* Divider with Logo */
.npp-section.npp-divider {
  position:relative;text-align:center;background:{{opt.bottom_bg}};
  padding-top:50px;padding-bottom:8px;flex:0 0 auto;
}
.divider-logo {
  position:absolute;top:-55px;left:50%;transform:translateX(-50%);
  width:110px;height:110px;background:#fff;border-radius:50%;
  box-shadow:0 0 0 10px #fff;display:flex;align-items:center;justify-content:center;
}
.divider-logo img {width:80%;height:auto;object-fit:contain;}

/* Title (WC: ≤8 → 72px else 50px) */
.npp-section.npp-title-wrap {padding:0 32px;flex:0 0 auto;}
.npp-title {
  color:{{opt.title_color}};text-align:center;margin:22px 0 16px 0;
  font-weight:700;line-height:1.25;word-break:break-word;white-space:normal;
  font-size:50px;
}
.npp-card[data-wc="0"] .npp-title,
.npp-card[data-wc="1"] .npp-title,
.npp-card[data-wc="2"] .npp-title,
.npp-card[data-wc="3"] .npp-title,
.npp-card[data-wc="4"] .npp-title,
.npp-card[data-wc="5"] .npp-title,
.npp-card[data-wc="6"] .npp-title,
.npp-card[data-wc="7"] .npp-title,
.npp-card[data-wc="8"] .npp-title {font-size:72px;}

/* Footer: date | CTA | domain */
.npp-section.npp-footer {
  position:relative;background:{{opt.bottom_bg}};border-top:1px solid #ddd;flex:0 0 auto;
}
.npp-bottom {
  display:grid;grid-template-columns:1fr auto 1fr;align-items:center;
  padding:18px 44px;font-size:28px;font-weight:700;color:{{opt.text_color}};
  column-gap:16px;
}
.npp-date {justify-self:start;display:inline-flex;align-items:center;gap:10px;white-space:nowrap;}
.npp-site {
  justify-self:end;display:inline-flex;align-items:center;gap:10px;
  white-space:nowrap;text-align:right;max-width:100%;overflow:hidden;text-overflow:ellipsis;
  color:{{opt.text_color}};
}
.npp-date::before {content:"📅";font-size:28px;line-height:1;}
.npp-site::before {content:"🌐";font-size:28px;line-height:1;color:#666;}

/* CTA (text-only, same color as domain) */
.npp-cta {
  justify-self:center;background:transparent;color:{{opt.text_color}};
  padding:0;border:none;font-size:22px;font-weight:800;line-height:1;
  display:inline-flex;align-items:center;gap:10px;white-space:nowrap;
}
.npp-cta::before {content:"««"; font-size:24px; line-height:1;}
.npp-cta::after {content:"»»"; font-size:24px; line-height:1;}

/* Banner (auto 150px only when provided) */
.npp-section.npp-banner {
  background:{{opt.banner_bg}};height:150px;display:none;
  align-items:center;justify-content:center;position:relative;overflow:hidden;flex:0 0 auto;
}
.npp-banner img {width:100%;height:100%;object-fit:cover;display:block;}
.npp-card:not([data-banner=""]) .npp-banner {display:flex;}
',

  'html' => '
<div class="npp-card" data-wc="{{opt.wc}}" data-banner="{{opt.banner}}">
  <div class="npp-body">

    <!-- Hero -->
    <section class="npp-section npp-hero">
      <div class="npp-image">
        <img src="{{image}}" alt="{{title}}" crossOrigin="anonymous" />
      </div>
    </section>

    <!-- Divider -->
    <section class="npp-section npp-divider">
      <div class="divider-logo">
        <img src="{{opt.logo}}" alt="{{site}}" crossOrigin="anonymous" />
      </div>
    </section>

    <!-- Title -->
    <section class="npp-section npp-title-wrap">
      <div class="npp-title">{{title}}</div>
    </section>

    <!-- Footer (date | CTA | domain) -->
    <section class="npp-section npp-footer">
      <div class="npp-bottom">
        <div class="npp-date">{{date}}</div>
        <div class="npp-cta">{{opt.cta_text}}</div>
        <div class="npp-site">{{opt.domain}}</div>
      </div>
    </section>

    <!-- Banner (no CTA overlay) -->
    <section class="npp-section npp-banner">
      <img src="{{opt.banner}}" alt="Banner" crossOrigin="anonymous" />
    </section>

  </div>
</div>
'
];
