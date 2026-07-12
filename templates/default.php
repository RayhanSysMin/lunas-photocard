<?php
return [
  'key'   => 'default',
  'name'  => 'Default Template',
  'fields'=> [
    'logo'          => ['type'=>'image', 'label'=>'Logo (Top Center)', 'default'=>''],
    'brand_bg'      => ['type'=>'color', 'label'=>'Top Background (White)', 'default'=>'#ffffff'],
    'brand_accent'  => ['type'=>'color', 'label'=>'Bottom Accent Color', 'default'=>'#A00000'],
    'border_color'  => ['type'=>'color', 'label'=>'Image Border Color', 'default'=>'#F4B800'],
    'bg_color'      => ['type'=>'color', 'label'=>'Image Box Background', 'default'=>'#ffffff'],
    'title_color'   => ['type'=>'color', 'label'=>'Title Text Color', 'default'=>'#ffffff'],
    'date_color'    => ['type'=>'color', 'label'=>'Date Color', 'default'=>'#ffffff'],
    'site_color'    => ['type'=>'color', 'label'=>'Domain Color', 'default'=>'#ffffff'],
    'cta_text'      => ['type'=>'text',  'label'=>'CTA Text', 'default'=>'বিস্তারিত কমেন্টে'],
    'image_overlay' => ['type'=>'color', 'label'=>'Image Overlay (RGBA)', 'default'=>'rgba(0,0,0,0.15)'],
  ],

  'css' => '
/* =======================================================
   Default Template — News PhotoCard
   ======================================================= */
.nag-card {
  width:1080px;height:1080px;
  font-family:"SolaimanLipi",sans-serif;
  overflow:hidden;position:relative;
  background:{{opt.brand_accent}};
}

/* White top area */
.nag-top-white {
  position:absolute;top:0;left:0;right:0;height:600px;
  background:{{opt.brand_bg}};z-index:1;
}

/* Top logo */
.nag-top {
  height:130px;z-index:5;
  display:flex;align-items:center;justify-content:center;
  position:relative;margin-bottom:8px;
}
.nag-top img {height:72px;width:auto;object-fit:contain;}

/* Fixed image box (default 940×620) */
.nag-image-wrap {
  position:relative;z-index:10;
  display:flex;align-items:center;justify-content:center;
  height:620px;margin-top:-30px;
}
.nag-image {
  width:940px;height:620px;
  border:8px solid {{opt.border_color}};
  border-radius:12px;overflow:hidden;
  background:{{opt.bg_color}};
  box-shadow:0 10px 28px rgba(0,0,0,0.22);
  position:relative;
}
.nag-image img {
  width:100%;height:100%;object-fit:cover;object-position:center;display:block;
}
.nag-image::after {content:"";position:absolute;inset:0;background:{{opt.image_overlay}};}

/* Title area */
.nag-bottom {
  position:relative;z-index:3;
  padding:16px 70px 130px;
  text-align:center;background:{{opt.brand_accent}};
}

.nag-title {
  color:{{opt.title_color}};font-weight:800;line-height:1.22;
  margin:0 auto;max-width:92%;word-break:break-word;white-space:normal;
  font-size:46px;
}

/* Word-based sizes */
.nag-card[data-wc="0"]  .nag-title,
.nag-card[data-wc="1"]  .nag-title,
.nag-card[data-wc="2"]  .nag-title,
.nag-card[data-wc="3"]  .nag-title,
.nag-card[data-wc="4"]  .nag-title {font-size:76px;}
.nag-card[data-wc="5"]  .nag-title,
.nag-card[data-wc="6"]  .nag-title,
.nag-card[data-wc="7"]  .nag-title,
.nag-card[data-wc="8"]  .nag-title {font-size:62px;}

/* Footer — ALWAYS visible */
.nag-footer {
  position:absolute;left:0;right:0;bottom:0;
  height:110px;z-index:9000;
  display:grid;grid-template-columns:1fr auto 1fr;align-items:center;
  padding:16px 70px;column-gap:16px;
  background:{{opt.brand_accent}};font-size:30px;font-weight:700;
}
.nag-date {color:{{opt.date_color}};justify-self:start;white-space:nowrap;}
.nag-cta  {color:{{opt.site_color}};justify-self:center;font-size:24px;font-weight:800;display:flex;align-items:center;gap:8px;white-space:nowrap;}
.nag-cta::before{content:"«";font-size:26px;}
.nag-cta::after {content:"»";font-size:26px;}
.nag-site {color:{{opt.site_color}};justify-self:end;white-space:nowrap;text-align:right;text-transform:lowercase;}
',

  'html' => '
<div class="nag-card" data-wc="{{opt.wc}}">
  <!-- White top background -->
  <div class="nag-top-white"></div>

  <!-- Top logo -->
  <div class="nag-top">
    <img src="{{opt.logo}}" alt="{{site}}" crossOrigin="anonymous" />
  </div>

  <!-- Fixed 940×620 Image -->
  <div class="nag-image-wrap">
    <div class="nag-image">
      <img src="{{image}}" alt="{{title}}" crossOrigin="anonymous" />
    </div>
  </div>

  <!-- Title -->
  <div class="nag-bottom">
    <div class="nag-title">{{title}}</div>
  </div>

  <!-- Footer -->
  <div class="nag-footer">
    <div class="nag-date">{{date}}</div>
    <div class="nag-cta">{{opt.cta_text}}</div>
    <div class="nag-site">{{opt.domain}}</div>
  </div>
</div>
'
];

