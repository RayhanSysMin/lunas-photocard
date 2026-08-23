<?php
return [
  'key'   => 'daily-new-nation-bangla',
  'name'  => 'Daily New Nation Bangla',
  'fields'=> [
    'logo'         => ['type'=>'image', 'label'=>'Logo Image (top-right)', 'default'=>''],
    'logo_text'    => ['type'=>'text',  'label'=>'Fallback Logo Text', 'default'=>'Daily New Nation'],
    'logo_badge'   => ['type'=>'text',  'label'=>'Fallback Logo Badge', 'default'=>'Bangla'],
    'seal_icon'    => ['type'=>'image', 'label'=>'Round Icon (image-left)', 'default'=>''],
    'seal_letter'  => ['type'=>'text',  'label'=>'Fallback Icon Letter', 'default'=>'D'],
    'canvas_bg'    => ['type'=>'color', 'label'=>'Canvas Background', 'default'=>'#fffafa'],
    'date_color'   => ['type'=>'color', 'label'=>'Date Color', 'default'=>'#5f927d'],
    'shoulder_color' => ['type'=>'color', 'label'=>'Shoulder Color', 'default'=>'#111111'],
    'title_color'  => ['type'=>'color', 'label'=>'Main Title Color', 'default'=>'#e60000'],
    'subheading_color' => ['type'=>'color', 'label'=>'Subheading Color', 'default'=>'#202020'],
    'cta_text'     => ['type'=>'text',  'label'=>'CTA Text', 'default'=>'-- বিস্তারিত কমেন্টে --'],
    'cta_color'    => ['type'=>'color', 'label'=>'CTA Color', 'default'=>'#b56565'],
  ],

  'css' => <<<'CSS'
.jn24-card {
  position:relative;width:1080px;height:1080px;
  overflow:hidden;background:{{opt.canvas_bg}};
  font-family:"SolaimanLipi","Noto Serif Bengali","Noto Sans Bengali",sans-serif;
  color:#111;letter-spacing:0;
}
.jn24-card *{box-sizing:border-box;letter-spacing:0}
.jn24-top {
  position:absolute;left:60px;right:60px;top:52px;height:72px;
  display:flex;align-items:center;justify-content:space-between;
}
.jn24-date {
  color:{{opt.date_color}};font-size:31px;line-height:1;
  font-weight:800;white-space:nowrap;
}
.jn24-logo {
  height:72px;min-width:330px;display:flex;align-items:center;justify-content:flex-end;
}
.jn24-logo img {
  max-height:68px;max-width:410px;width:auto;display:block;object-fit:contain;
}
.jn24-logo-fallback {
  display:flex;align-items:center;justify-content:flex-end;
  font-family:Arial,Helvetica,sans-serif;font-weight:900;line-height:1;
}
.jn24-logo-mark {
  position:relative;width:50px;height:50px;margin-right:8px;border-radius:50%;
  background:#fff;border:3px solid #202020;
}
.jn24-logo-mark::before {
  content:"";position:absolute;left:13px;top:9px;width:17px;height:27px;
  border-left:8px solid #e30613;border-bottom:8px solid #e30613;border-radius:0 0 0 18px;
}
.jn24-logo-mark::after {
  content:"";position:absolute;left:20px;top:-7px;width:9px;height:9px;
  border-radius:50%;background:#202020;
}
.jn24-logo-main {
  color:#171717;font-size:45px;font-weight:900;letter-spacing:0;
}
.jn24-logo-badge {
  margin-left:8px;padding:5px 8px 4px;border-radius:2px;
  background:#d91920;color:#fff;font-size:25px;font-weight:900;letter-spacing:0;
}
.jn24-card[data-logo=""] .jn24-logo-img{display:none}
.jn24-card:not([data-logo=""]) .jn24-logo-fallback{display:none}

.jn24-hero {
  position:absolute;left:60px;top:160px;width:960px;height:500px;
  overflow:hidden;background:#f0f0f0;
  background-image:url({{image}});
  background-size:cover;background-position:center;background-repeat:no-repeat;
}
.jn24-hero::before {
  content:"";position:absolute;inset:-60px;z-index:0;
  background-image:inherit;background-size:cover;background-position:center;background-repeat:no-repeat;
  filter:blur(52px) saturate(.58) brightness(.84);transform:scale(1.2);opacity:.62;
  box-shadow:inset 0 0 0 999px rgba(255,250,250,.44);
}
.jn24-hero::after {
  content:"";position:absolute;inset:0;z-index:1;
  background-image:inherit;background-size:contain;background-position:center;background-repeat:no-repeat;
}
.jn24-hero img {
  display:none;
}
.jn24-seal {
  position:absolute;left:43px;top:577px;width:103px;height:103px;
  border-radius:50%;background:#fff;border:4px solid #171717;
  display:flex;align-items:center;justify-content:center;z-index:5;
  box-shadow:0 0 0 8px #fff;
}
.jn24-seal img {
  width:78%;height:78%;object-fit:contain;display:block;
}
.jn24-seal-fallback {
  color:#e30613;font-family:Arial,Helvetica,sans-serif;
  font-size:72px;font-weight:900;line-height:.8;transform:translateY(-2px);
}
.jn24-card[data-seal=""] .jn24-seal-img{display:none}
.jn24-card:not([data-seal=""]) .jn24-seal-fallback{display:none}

.jn24-copy {
  position:absolute;left:56px;right:56px;top:682px;bottom:50px;
  display:flex;flex-direction:column;align-items:center;text-align:center;
}
.jn24-shoulder {
  margin:0 0 18px;color:{{opt.shoulder_color}};
  font-size:44px;font-weight:900;line-height:1.15;
}
.jn24-card[data-shoulder=""] .jn24-shoulder{display:none}
.jn24-title {
  margin:0 auto;color:{{opt.title_color}};font-size:60px;
  line-height:1.18;font-weight:900;max-width:960px;
  word-break:normal;overflow-wrap:break-word;text-align:center;
}
.jn24-card[data-title-bucket="short"] .jn24-title{
  font-size:74px;line-height:1.16;max-width:960px;text-wrap:wrap;
}
.jn24-card[data-title-bucket="very_short"] .jn24-title{
  font-size:78px;line-height:1.16;font-weight:900;max-width:760px;text-wrap:balance;
}
.jn24-card[data-title-bucket="very_short"][data-shoulder=""][data-subheading=""] .jn24-title{
  margin-top:38px;
}
.jn24-card[data-title-bucket="very_short"][data-shoulder=""]:not([data-subheading=""]) .jn24-title{
  margin-top:26px;
}
.jn24-card[data-title-bucket="balanced_short"] .jn24-title{
  font-size:74px;line-height:1.16;font-weight:900;max-width:940px;text-wrap:wrap;
}
.jn24-card[data-title-bucket="wide_short"] .jn24-title{
  font-size:72px;line-height:1.16;font-weight:900;max-width:940px;text-wrap:wrap;
}
.jn24-card[data-title-bucket="compact"] .jn24-title{
  font-size:78px;line-height:1.16;font-weight:900;max-width:940px;text-wrap:wrap;
}
.jn24-card[data-title-bucket="medium"] .jn24-title{
  font-size:66px;line-height:1.16;max-width:960px;text-wrap:wrap;
}
.jn24-card[data-title-bucket="long"] .jn24-title{
  font-size:58px;line-height:1.16;max-width:960px;text-wrap:wrap;
}
.jn24-card[data-title-bucket="xlong"] .jn24-title{
  font-size:52px;line-height:1.16;max-width:960px;text-wrap:wrap;
}
.jn24-subheading {
  margin:18px auto 0;color:{{opt.subheading_color}};
  font-size:34px;line-height:1.22;font-weight:800;max-width:880px;
  word-break:normal;overflow-wrap:break-word;text-align:center;text-wrap:balance;
}
.jn24-card[data-subheading=""] .jn24-subheading{display:none}
.jn24-card[data-subheading-bucket="medium"] .jn24-subheading{
  font-size:31px;line-height:1.2;max-width:920px;
}
.jn24-card[data-subheading-bucket="long"] .jn24-subheading{
  font-size:28px;line-height:1.18;max-width:940px;margin-top:14px;
}
.jn24-card[data-copy-density="subheading_dense"] .jn24-subheading{
  font-size:29px;line-height:1.16;max-width:940px;margin-top:12px;
}
.jn24-card[data-copy-density="subheading_tight"] .jn24-subheading{
  font-size:25px;line-height:1.14;max-width:950px;margin-top:8px;
}
.jn24-card[data-copy-density="subheading_dense"][data-title-bucket="compact"] .jn24-title,
.jn24-card[data-copy-density="subheading_dense"][data-title-bucket="very_short"] .jn24-title{
  font-size:72px;line-height:1.12;
}
.jn24-card[data-copy-density="subheading_dense"][data-title-bucket="wide_short"] .jn24-title,
.jn24-card[data-copy-density="subheading_dense"][data-title-bucket="balanced_short"] .jn24-title,
.jn24-card[data-copy-density="subheading_dense"][data-title-bucket="short"] .jn24-title{
  font-size:66px;line-height:1.12;
}
.jn24-card[data-copy-density="subheading_dense"][data-title-bucket="medium"] .jn24-title{font-size:58px;line-height:1.12}
.jn24-card[data-copy-density="subheading_dense"][data-title-bucket="long"] .jn24-title{font-size:50px;line-height:1.12}
.jn24-card[data-copy-density="subheading_dense"][data-title-bucket="xlong"] .jn24-title{font-size:45px;line-height:1.12}
.jn24-card[data-copy-density="subheading_tight"] .jn24-title{
  font-size:44px;line-height:1.1;max-width:960px;
}
.jn24-card[data-copy-density="subheading_tight"][data-title-bucket="compact"] .jn24-title,
.jn24-card[data-copy-density="subheading_tight"][data-title-bucket="very_short"] .jn24-title{
  font-size:62px;line-height:1.1;
}
.jn24-card[data-copy-density="subheading_tight"][data-title-bucket="wide_short"] .jn24-title,
.jn24-card[data-copy-density="subheading_tight"][data-title-bucket="balanced_short"] .jn24-title,
.jn24-card[data-copy-density="subheading_tight"][data-title-bucket="short"] .jn24-title{
  font-size:58px;line-height:1.1;
}
.jn24-cta {
  position:absolute;left:0;right:0;bottom:0;
  color:{{opt.cta_color}};font-size:31px;font-weight:800;line-height:1.15;text-align:center;
}
.jn24-cta::after {
  content:"";display:block;width:16px;height:16px;margin:7px auto 0;
  border-right:2px solid currentColor;border-bottom:2px solid currentColor;
  transform:rotate(45deg);
}
.jn24-card[data-cta=""] .jn24-cta{display:none}
CSS,

  'html' => <<<'HTML'
<div class="jn24-card"
     data-wc="{{opt.wc}}"
     data-title-bucket="{{opt.title_bucket}}"
     data-subheading-bucket="{{opt.subheading_bucket}}"
     data-copy-density="{{opt.copy_density}}"
     data-logo="{{opt.logo}}"
     data-seal="{{opt.seal_icon}}"
     data-shoulder="{{shoulder}}"
     data-subheading="{{subheading}}"
     data-cta="{{opt.cta_text}}">
  <div class="jn24-top">
    <div class="jn24-date">{{date}}</div>
    <div class="jn24-logo">
      <img class="jn24-logo-img" src="{{opt.logo}}" alt="{{site}}" crossOrigin="anonymous" />
      <div class="jn24-logo-fallback" aria-label="{{opt.logo_text}} {{opt.logo_badge}}">
        <span class="jn24-logo-mark"></span>
        <span class="jn24-logo-main">{{opt.logo_text}}</span>
        <span class="jn24-logo-badge">{{opt.logo_badge}}</span>
      </div>
    </div>
  </div>

  <div class="jn24-hero">
    <img src="{{image}}" alt="{{title}}" crossOrigin="anonymous" />
  </div>

  <div class="jn24-seal" aria-hidden="true">
    <img class="jn24-seal-img" src="{{opt.seal_icon}}" alt="" crossOrigin="anonymous" />
    <span class="jn24-seal-fallback">{{opt.seal_letter}}</span>
  </div>

  <div class="jn24-copy">
    <div class="jn24-shoulder">{{shoulder}}</div>
    <div class="jn24-title">{{title}}</div>
    <div class="jn24-subheading">{{subheading}}</div>
    <div class="jn24-cta">{{opt.cta_text}}</div>
  </div>
</div>
HTML
];
