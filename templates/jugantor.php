<?php
return [
  'key'   => 'jugantor-10820-pixel',
  'name'  => 'Jugantor (1080×1080 • Pixel Perfect • Ad-aware)',
  'fields'=> [
    'logo'         => ['type'=>'image', 'label'=>'Logo (bottom-right)', 'default'=>''],
    'title_color'  => ['type'=>'color', 'label'=>'Title Color',         'default'=>'#FFFFFF'],
    'date_color'   => ['type'=>'color', 'label'=>'Date Color',          'default'=>'#FFFFFF'],
    'domain_color' => ['type'=>'color', 'label'=>'Domain Color',        'default'=>'#FFFFFF'],
    'cta_text'     => ['type'=>'text',  'label'=>'CTA Text',            'default'=>'বিস্তারিত কমেন্টে ...'],
    'bg_dark'      => ['type'=>'color', 'label'=>'Dark Base',           'default'=>'#0A0E14'],
    'grad_black'   => ['type'=>'color', 'label'=>'Gradient Top (black)','default'=>'#000000'],
    'grad_red'     => ['type'=>'color', 'label'=>'Gradient Bottom (red)','default'=>'#6D0011'],
    'ad_banner'    => ['type'=>'image', 'label'=>'Ad Banner (1080×150)', 'default'=>''],
    'ad_bg'        => ['type'=>'color', 'label'=>'Ad Background',        'default'=>'#FFFFFF'],
  ],

  'css' =>
'/* Canvas & layout vars */
.jg-card{position:relative;width:1080px;height:1080px;background:{{opt.bg_dark}};
  overflow:hidden;font-family:"SolaimanLipi",sans-serif;color:#fff;
  --top-h:640px; --footer-h:100px; --banner-h:0px;}
.jg-card:not([data-banner=""]){--top-h:600px; --banner-h:150px;}

/* Top photo with deep fade */
.jg-top{position:absolute;left:0;right:0;top:0;height:var(--top-h);background:{{opt.bg_dark}}}
.jg-photo{position:absolute;inset:0;overflow:hidden}
.jg-photo img{width:100%;height:100%;object-fit:cover;object-position:center;display:block}
.jg-photo::after{content:"";position:absolute;left:0;right:0;bottom:0;height:30%;
  background:linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,.92) 85%, {{opt.bg_dark}} 100%);}
.jg-date{position:absolute;left:50%;bottom:20px;transform:translateX(-50%);
  font-size:32px;line-height:1;font-weight:700;color:{{opt.date_color}}}

/* Gradient base spans behind footer too */
.jg-base{position:absolute;left:0;right:0;top:var(--top-h);bottom:0;margin-top:-1px;
  background:linear-gradient(180deg, {{opt.grad_black}} 0%, {{opt.grad_red}} 100%);}

/* Title centered in the true gap (photo→footer) */
.jg-center{position:absolute;left:0;right:0;top:0;bottom:calc(var(--footer-h) + var(--banner-h));
  display:flex;align-items:center;justify-content:center;padding:0 64px}
.jg-title{margin:0;text-align:center;color:{{opt.title_color}};font-weight:800;
  line-height:1.20;max-width:960px;font-size:55px}  /* default (>8 words) */

/* Word-count buckets (PASS opt.wc) */
.jg-card[data-wc="0"] .jg-title,
.jg-card[data-wc="1"] .jg-title,
.jg-card[data-wc="2"] .jg-title,
.jg-card[data-wc="3"] .jg-title,
.jg-card[data-wc="4"] .jg-title,
.jg-card[data-wc="5"] .jg-title,
.jg-card[data-wc="6"] .jg-title,
.jg-card[data-wc="7"] .jg-title,
.jg-card[data-wc="8"] .jg-title{font-size:70px}

/* Ad-mode nudges */
.jg-card:not([data-banner=""])[data-wc="0"] .jg-title,
.jg-card:not([data-banner=""])[data-wc="1"] .jg-title,
.jg-card:not([data-banner=""])[data-wc="2"] .jg-title,
.jg-card:not([data-banner=""])[data-wc="3"] .jg-title,
.jg-card:not([data-banner=""])[data-wc="4"] .jg-title,
.jg-card:not([data-banner=""])[data-wc="5"] .jg-title,
.jg-card:not([data-banner=""])[data-wc="6"] .jg-title,
.jg-card:not([data-banner=""])[data-wc="7"] .jg-title,
.jg-card:not([data-banner=""])[data-wc="8"] .jg-title{font-size:65px}
.jg-card:not([data-banner=""]) .jg-title{font-size:50px} /* >8 words with ad */

/* Footer overlays the gradient */
.jg-footer{position:absolute;left:0;right:0;bottom:var(--banner-h);height:var(--footer-h);
  display:flex;align-items:flex-end;justify-content:space-between;padding:0 44px;background:transparent}
.jg-left{display:flex;flex-direction:column;gap:8px;margin-bottom:18px}
.jg-cta{font-size:25px;font-weight:700}
.jg-domain{font-size:27px;font-weight:800;color:{{opt.domain_color}};letter-spacing:.35px}
.jg-logo img{height:70px;width:auto;display:block;margin-bottom:10px}

/* Ad banner */
.jg-banner{position:absolute;left:0;right:0;bottom:0;height:150px;display:none;
  align-items:center;justify-content:center;background:{{opt.ad_bg}};
  z-index:10000;border-top:1px solid #ddd;overflow:hidden}
.jg-banner img{width:100%;height:100%;object-fit:cover;display:block}
.jg-card:not([data-banner=""]) .jg-banner{display:flex}
',

  'html' =>
'<div class="jg-card" data-wc="{{opt.wc}}" data-banner="{{opt.ad_banner}}">
  <!-- Photo + date -->
  <div class="jg-top">
    <div class="jg-photo"><img src="{{image}}" alt="{{title}}" crossOrigin="anonymous" /></div>
    <div class="jg-date">{{date}}</div>
  </div>

  <!-- Gradient base (behind footer too) -->
  <div class="jg-base">
    <!-- Title centered between photo bottom and footer top -->
    <div class="jg-center">
      <div class="jg-title">{{title}}</div>
    </div>
  </div>

  <!-- Footer row -->
  <div class="jg-footer">
    <div class="jg-left">
      <div class="jg-cta">{{opt.cta_text}}</div>
      <div class="jg-domain">{{opt.domain}}</div>
    </div>
    <div class="jg-logo"><img src="{{opt.logo}}" alt="logo" crossOrigin="anonymous" /></div>
  </div>

  <!-- Optional Ad -->
  <div class="jg-banner">
    <img src="{{opt.ad_banner}}" alt="Ad Banner" crossOrigin="anonymous" />
  </div>
</div>'
];
