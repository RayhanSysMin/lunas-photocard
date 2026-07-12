<?php
return [
  'key' => 'dhaka-post',
  'name' => 'Dhaka Post Classic',
  'fields' => [
    'logo'         => ['type'=>'image', 'label'=>'Logo (top-right)', 'default'=>''],
    'top_bg'       => ['type'=>'color', 'label'=>'Top Bar Background', 'default'=>'#ffffff'],
    'date_color'   => ['type'=>'color', 'label'=>'Date Text Color', 'default'=>'#0a0a0a'],
    'bottom_bg'    => ['type'=>'color', 'label'=>'Bottom (Title) Background', 'default'=>'#ffffff'],
    'title_color'  => ['type'=>'color', 'label'=>'Title Text Color', 'default'=>'#111111'],
    'footer_bg'    => ['type'=>'color', 'label'=>'Footer Background', 'default'=>'#ffffff'],
    'footer_text'  => ['type'=>'text',  'label'=>'Footer Text (CTA)', 'default'=>'« « বিস্তারিত কমেন্টে » »'],
    'footer_image' => ['type'=>'image', 'label'=>'Footer Ad Banner (1080×150)', 'default'=>''],
  ],

  'css' => '
/* =======================================================
   Advance (Footer Image Ad Banner)
   ======================================================= */
.npp-card {
  position:relative;display:flex;flex-direction:column;
  width:1080px;height:1080px;
  overflow:hidden;font-family:\'SolaimanLipi\',sans-serif;
  background:#fff;
}

/* ===== TOP BAR ===== */
.npp-top {
  height:140px;flex:0 0 auto;
  display:flex;align-items:center;justify-content:space-between;
  padding:26px 40px;background:{{opt.top_bg}};
}
.npp-date {font-size:34px;font-weight:700;color:{{opt.date_color}};}
.npp-logo img {height:60px;width:auto;display:block;}

/* ===== HERO IMAGE (increased height) ===== */
.npp-bg {
  flex:1 1 auto;
  height:700px; /* increased for better visual balance */
  background-image:url({{image}});
  background-size:cover;background-position:center;
}

/* ===== TITLE AREA ===== */
.npp-bottom {
  flex:0 0 auto;
  background:{{opt.bottom_bg}};
  padding:40px 64px;text-align:center;
}
.npp-title {
  color:{{opt.title_color}};
  font-weight:800;line-height:1.25;
  word-wrap:break-word;white-space:normal;
  font-size:45px;margin:0 auto;max-width:92%;
}
/* Dynamic font-size based on word count (≤8 → 65px, else 45px) */
.npp-card[data-wc="0"]  .npp-title,
.npp-card[data-wc="1"]  .npp-title,
.npp-card[data-wc="2"]  .npp-title,
.npp-card[data-wc="3"]  .npp-title,
.npp-card[data-wc="4"]  .npp-title,
.npp-card[data-wc="5"]  .npp-title,
.npp-card[data-wc="6"]  .npp-title,
.npp-card[data-wc="7"]  .npp-title,
.npp-card[data-wc="8"]  .npp-title {font-size:65px;}

/* ===== FOOTER AD BANNER ===== */
.npp-footer {
  flex:0 0 auto;
  position:relative;
  height:180px;
  background:{{opt.footer_bg}};
  display:flex;flex-direction:column;align-items:center;justify-content:flex-start;
  overflow:hidden;
  border-top:4px solid #f2f2f2;
}
.npp-footer-text {
  font-size:32px;font-weight:700;color:#111;text-align:center;
  padding:16px 0 10px;
}
.npp-footer img.fi {
  width:100%;height:120px;object-fit:cover;object-position:center;display:block;
  border-top:2px solid #ddd;
}
',

  'html' => '
<div class="npp-card" data-wc="{{opt.wc}}">
  <!-- TOP -->
  <div class="npp-top">
    <div class="npp-date">{{date}}</div>
    <div class="npp-logo"><img src="{{opt.logo}}" alt="{{site}}" crossOrigin="anonymous" /></div>
  </div>

  <!-- MAIN IMAGE -->
  <div class="npp-bg"></div>

  <!-- TITLE -->
  <div class="npp-bottom">
    <div class="npp-title">{{title}}</div>
  </div>

  <!-- FOOTER with TEXT + AD IMAGE -->
  <div class="npp-footer">
    <div class="npp-footer-text">{{opt.footer_text}}</div>
    <img class="fi" src="{{opt.footer_image}}" alt="Ad Banner" crossOrigin="anonymous" />
  </div>
</div>
'
];
