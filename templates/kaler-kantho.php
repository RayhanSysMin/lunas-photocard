<?php
return [
  'key'   => 'kalerkantho-1080-classic',
  'name'  => 'Kaler Kantho (1080×1080 • Corner Icon • Auto Font • Ad-aware)',
  'fields'=> [
    // Branding
    'logo'         => ['type'=>'image', 'label'=>'Brand Logo (above title)', 'default'=>''],
    'corner_icon'  => ['type'=>'image', 'label'=>'Top-right Circle Icon',    'default'=>''],

    // Text/Colors
    'title_color'  => ['type'=>'color', 'label'=>'Title Color',      'default'=>'#FFFFFF'],
    'bg_dark'      => ['type'=>'color', 'label'=>'Dark Background',  'default'=>'#0B1220'],
    'date_color'   => ['type'=>'color', 'label'=>'Date Color',       'default'=>'#E5E7EB'],
    'date_bg'      => ['type'=>'color', 'label'=>'Date Pill BG',     'default'=>'rgba(255,255,255,0.16)'],
    'domain_color' => ['type'=>'color', 'label'=>'Domain Color',     'default'=>'#9CA3AF'],
    'cta_text'     => ['type'=>'text',  'label'=>'CTA Text',         'default'=>'বিস্তারিত লিংকে'],

    // Optional Ad (bottom 1080×150)
    'ad_banner'    => ['type'=>'image', 'label'=>'Ad Banner (1080×150)', 'default'=>''],
    'ad_bg'        => ['type'=>'color', 'label'=>'Ad Background',        'default'=>'#FFFFFF'],
  ],

  'css' =>
'/* ===== Kaler Kantho 1080×1080 ===== */
.kk-card{position:relative;width:1080px;height:1080px;background:{{opt.bg_dark}};
  overflow:hidden;font-family:"SolaimanLipi",sans-serif;color:#fff;display:flex;flex-direction:column}

/* TOP: photo */
.kk-top{position:relative;height:600px;background:{{opt.bg_dark}}}
.kk-photo{position:absolute;inset:0;overflow:hidden}
.kk-photo img{width:100%;height:100%;object-fit:cover;object-position:center;display:block}
/* dark fade for legibility — reduced to ~19% height and blending to bg */
.kk-photo::after{
  content:"";position:absolute;left:0;right:0;bottom:0;height:19%;
  background:linear-gradient(
    180deg,
    rgba(11,18,32,0) 0%,
    rgba(11,18,32,.85) 70%,
    {{opt.bg_dark}} 100%
  );
}

/* Date pill (top-left) */
.kk-date{position:absolute;top:22px;left:26px;padding:10px 16px;border-radius:10px;font-size:28px;
  font-weight:700;background:{{opt.date_bg}};color:{{opt.date_color}};backdrop-filter:blur(2px)}

/* Corner circle icon (top-right) */
.kk-corner{position:absolute;top:20px;right:20px;width:70px;height:70px;border-radius:50%;
  background:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(0,0,0,.2)}
.kk-corner img{width:70%;height:70%;object-fit:contain;display:block}
.kk-card[data-corner=""] .kk-corner{display:none}

/* BODY: brand logo 20px above title, then title */
.kk-body{flex:1 1 auto;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;
  padding:0 64px;background:{{opt.bg_dark}}}
.kk-brand{margin-top:24px;margin-bottom:20px}
.kk-brand img{height:72px;width:auto;display:block}
.kk-title{margin:0;text-align:center;line-height:1.24;color:{{opt.title_color}};
  font-weight:800;word-break:break-word;white-space:normal;font-size:56px}

/* WORD-COUNT AUTO SIZING (pass wc via opt.wc) */
/* default: 56px; <=8 words → 72px; 9–12 → 64px; 13+ → 52px */
.kk-card[data-wc="0"] .kk-title,
.kk-card[data-wc="1"] .kk-title,
.kk-card[data-wc="2"] .kk-title,
.kk-card[data-wc="3"] .kk-title,
.kk-card[data-wc="4"] .kk-title,
.kk-card[data-wc="5"] .kk-title,
.kk-card[data-wc="6"] .kk-title,
.kk-card[data-wc="7"] .kk-title,
.kk-card[data-wc="8"] .kk-title{font-size:72px}
.kk-card[data-wc="9"]  .kk-title,
.kk-card[data-wc="10"] .kk-title,
.kk-card[data-wc="11"] .kk-title,
.kk-card[data-wc="12"] .kk-title{font-size:64px}
.kk-card[data-wc="13"] .kk-title,
.kk-card[data-wc="14"] .kk-title,
.kk-card[data-wc="15"] .kk-title,
.kk-card[data-wc="16"] .kk-title,
.kk-card[data-wc="17"] .kk-title,
.kk-card[data-wc="18"] .kk-title,
.kk-card[data-wc="19"] .kk-title,
.kk-card[data-wc="20"] .kk-title{font-size:52px}

/* FOOTER: CTA left, domain right (uses opt.domain) */
.kk-footer{margin-top:auto;display:flex;align-items:center;justify-content:space-between;padding:20px 40px 24px 40px;
  background:transparent}
.kk-cta{display:flex;align-items:center;gap:12px;font-size:28px;font-weight:700;color:#E5E7EB}
.kk-cta .dot{width:12px;height:12px;border-radius:50%;background:#fff;display:inline-block}
.kk-domain{font-size:28px;font-weight:700;color:{{opt.domain_color}};display:flex;align-items:center;gap:10px}
.kk-domain::before{content:"🌐";font-size:28px}

/* ===== AD-AWARE LAYOUT ===== */
/* Banner block (bottom). Only shown when ad URL present via data-banner */
.kk-banner{position:absolute;left:0;right:0;bottom:0;height:150px;display:none;
  align-items:center;justify-content:center;background:{{opt.ad_bg}};z-index:10000;border-top:1px solid #ddd;overflow:hidden}
.kk-banner img{width:100%;height:100%;object-fit:cover;display:block}

/* Show banner if data-banner is non-empty */
.kk-card:not([data-banner=""]) .kk-banner{display:flex}

/* When ad is present:
   - shorten the photo a bit,
   - give the body extra bottom padding so title/footer never collide with banner,
   - and lift the footer above the banner (absolute with bottom:150px). */
.kk-card:not([data-banner=""]) .kk-top{height:560px}
.kk-card:not([data-banner=""]) .kk-body{padding-bottom:170px;background:{{opt.bg_dark}}}
.kk-card:not([data-banner=""]) .kk-footer{position:absolute;left:0;right:0;bottom:150px;padding:20px 40px;background:transparent}

/* Also nudge title sizes down slightly in ad mode to avoid wrapping into footer */
.kk-card:not([data-banner=""]) .kk-title{font-size:52px}
.kk-card:not([data-banner=""])[data-wc="0"]  .kk-title,
.kk-card:not([data-banner=""])[data-wc="1"]  .kk-title,
.kk-card:not([data-banner=""])[data-wc="2"]  .kk-title,
.kk-card:not([data-banner=""])[data-wc="3"]  .kk-title,
.kk-card:not([data-banner=""])[data-wc="4"]  .kk-title,
.kk-card:not([data-banner=""])[data-wc="5"]  .kk-title,
.kk-card:not([data-banner=""])[data-wc="6"]  .kk-title,
.kk-card:not([data-banner=""])[data-wc="7"]  .kk-title,
.kk-card:not([data-banner=""])[data-wc="8"]  .kk-title{font-size:68px}
.kk-card:not([data-banner=""])[data-wc="9"]  .kk-title,
.kk-card:not([data-banner=""])[data-wc="10"] .kk-title,
.kk-card:not([data-banner=""])[data-wc="11"] .kk-title,
.kk-card:not([data-banner=""])[data-wc="12"] .kk-title{font-size:60px}
',

  'html' =>
'<div class="kk-card" data-wc="{{opt.wc}}" data-banner="{{opt.ad_banner}}" data-corner="{{opt.corner_icon}}">
  <div class="kk-top">
    <div class="kk-photo"><img src="{{image}}" alt="{{title}}" crossOrigin="anonymous" /></div>
    <div class="kk-date">{{date}}</div>
    <div class="kk-corner"><img src="{{opt.corner_icon}}" alt="" crossOrigin="anonymous" /></div>
  </div>

  <div class="kk-body">
    <div class="kk-brand"><img src="{{opt.logo}}" alt="brand" crossOrigin="anonymous" /></div>
    <div class="kk-title">{{title}}</div>
  </div>

  <div class="kk-footer">
    <div class="kk-cta"><span class="dot"></span><span>{{opt.cta_text}}</span></div>
    <div class="kk-domain">{{opt.domain}}</div>
  </div>

  <div class="kk-banner">
    <img src="{{opt.ad_banner}}" alt="Ad Banner" crossOrigin="anonymous" />
  </div>
</div>'
];
