<?php
return [
  'key'   => 'jagonews-unique-1080',
  'name'  => 'JagoNews Unique (1080×1080, Larger Hero & Footer CTA)',
  'fields'=> [
    // Canvas / surfaces
    'canvas_bg'       => ['type'=>'color','label'=>'Canvas Background Color','default'=>'#FFFFFF'],
    'canvas_bg_image' => ['type'=>'image','label'=>'Canvas Background Image (optional)','default'=>''],
    'surface_bg'      => ['type'=>'color','label'=>'Surface Background (Top/Title/Footer)','default'=>'#FFFFFF'],

    // Branding / colors
    'logo'         => ['type'=>'image','label'=>'Logo (top-right)','default'=>''],
    'title_color'  => ['type'=>'color','label'=>'Title Color','default'=>'#0B0F19'],
    'text_color'   => ['type'=>'color','label'=>'Body Text Color','default'=>'#111111'],
    'accent'       => ['type'=>'color','label'=>'Accent Red','default'=>'#E31B23'],
    'bg_tint'      => ['type'=>'color','label'=>'Hero Background Tint','default'=>'#F7F9FC'],

    // Divider icon
    'divider_icon' => ['type'=>'image','label'=>'Divider Icon (bottom-left on image)','default'=>''],

    // CTA
    'cta_text'     => ['type'=>'text','label'=>'CTA Text','default'=>'— বিস্তারিত কমেন্টে —'],
    'cta_color'    => ['type'=>'color','label'=>'CTA Color','default'=>'#6B7280'],

    // Banner (ad)
    'banner'       => ['type'=>'image','label'=>'Bottom Banner (1080×150)','default'=>''],
    'banner_bg'    => ['type'=>'color','label'=>'Banner Background','default'=>'#FFFFFF'],
  ],

  'css' => '
.jnx-card {
  position:relative;width:1080px;height:1080px;
  background-color: {{opt.canvas_bg}};
  background-image: url({{opt.canvas_bg_image}});
  background-size: cover;background-position: center;background-repeat: no-repeat;
  overflow:hidden;font-family:\'SolaimanLipi\',sans-serif;color:{{opt.text_color}};
  display:flex;flex-direction:column;
  --hero-h: 600px;
  --icon: 56px;
  --icon-br:4px;
  --footer-h: 54px;
}
.jnx-card *{box-sizing:border-box}
.jnx-top{display:flex;align-items:center;justify-content:space-between;padding:16px 36px 10px 36px;border-bottom:1px solid #F1F2F4;background:{{opt.surface_bg}};}
.jnx-date{font-size:28px;font-weight:700;color:#111827}
.jnx-logo img{height:54px;width:auto;display:block}
.jnx-hero-wrap{position:relative;padding:16px 24px 28px 24px;background:{{opt.surface_bg}}}
.jnx-hero{position:relative;height:var(--hero-h);border-radius:20px;overflow:visible;border:1px solid #ECEFF3;box-shadow:0 8px 20px rgba(17,24,39,.06)}
.jnx-hero-inner{position:absolute;inset:0;border-radius:inherit;overflow:hidden;background:{{opt.bg_tint}}}
.jnx-hero-inner img{width:100%;height:100%;object-fit:cover;object-position:center;display:block}
.jnx-hero-inner::after{content:"";position:absolute;left:0;right:0;bottom:0;height:6px;background:{{opt.accent}}}
.jnx-icon{position:absolute;left:calc(-0.2 * var(--icon));bottom:calc(-0.2 * var(--icon));width:var(--icon);height:var(--icon);border-radius:50%;background:#ffffff;border:var(--icon-br) solid #0B0F19;display:flex;align-items:center;justify-content:center;z-index:9999;}
.jnx-icon img{width:80%;height:80%;object-fit:contain;display:block}
.jnx-card[data-icon=""] .jnx-icon{display:none}
.jnx-title-wrap{padding:0 56px;flex:1 1 auto;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;background:{{opt.surface_bg}};}
.jnx-title{text-align:center;color:{{opt.title_color}};font-weight:900;letter-spacing:-.3px;line-height:1.18;margin:0;word-break:break-word;text-wrap:balance;font-size:50px}
.jnx-title::before{content:"“";margin-right:6px}
.jnx-title::after{content:"”";margin-left:6px}
.jnx-card[data-wc="0"]  .jnx-title,
.jnx-card[data-wc="1"]  .jnx-title,
.jnx-card[data-wc="2"]  .jnx-title,
.jnx-card[data-wc="3"]  .jnx-title,
.jnx-card[data-wc="4"]  .jnx-title,
.jnx-card[data-wc="5"]  .jnx-title,
.jnx-card[data-wc="6"]  .jnx-title,
.jnx-card[data-wc="7"]  .jnx-title,
.jnx-card[data-wc="8"]  .jnx-title{font-size:72px}
.jnx-cta{display:none;text-align:center;color:{{opt.cta_color}};font-size:24px;font-weight:700}
.jnx-banner{height:150px;display:none;align-items:center;justify-content:center;background:{{opt.banner_bg}};overflow:hidden;border-top:1px solid #F1F2F4}
.jnx-banner img{width:100%;height:100%;object-fit:cover;display:block}
.jnx-card[data-banner]:not([data-banner=""]) .jnx-banner{display:flex}
.jnx-card[data-banner]:not([data-banner=""]) .jnx-title-wrap .jnx-cta{display:block}
.jnx-card[data-banner]:not([data-banner=""]) .jnx-footer{display:none}
.jnx-footer{display:flex;align-items:center;justify-content:center;height:var(--footer-h);padding:10px 36px;border-top:1px solid #F1F2F4;background:{{opt.surface_bg}}}
.jnx-footer .jnx-cta{display:block;margin:0}
',

  'html' => '
<div class="jnx-card"
     data-wc="{{opt.wc}}"
     data-banner="{{opt.banner}}"
     data-icon="{{opt.divider_icon}}">

  <div class="jnx-top">
    <div class="jnx-date">{{date}}</div>
    <div class="jnx-logo"><img src="{{opt.logo}}" alt="{{site}}" crossOrigin="anonymous" /></div>
  </div>

  <div class="jnx-hero-wrap">
    <div class="jnx-hero">
      <div class="jnx-hero-inner">
        <img src="{{image}}" alt="{{title}}" crossOrigin="anonymous" />
      </div>
      <div class="jnx-icon" aria-hidden="true">
        <img src="{{opt.divider_icon}}" alt="" crossOrigin="anonymous" />
      </div>
    </div>
  </div>

  <div class="jnx-title-wrap">
    <div class="jnx-title">{{title}}</div>
    <div class="jnx-cta">{{opt.cta_text}}</div>
  </div>

  <div class="jnx-banner">
    <img src="{{opt.banner}}" alt="Banner" crossOrigin="anonymous" />
  </div>

  <div class="jnx-footer">
    <div class="jnx-cta">{{opt.cta_text}}</div>
  </div>
</div>
'
];
