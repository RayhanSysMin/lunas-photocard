(function($){
  const cfg = window.DNNBPCF || {};

  function waitForFonts(){
    return (document.fonts && document.fonts.ready) ? document.fonts.ready : Promise.resolve();
  }

  function escapeHtml(value){
    return decodeEntities(value).replace(/[&<>"']/g, function(char){
      return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char];
    });
  }

  function decodeEntities(value){
    const textarea = document.createElement('textarea');
    textarea.innerHTML = String(value == null ? '' : value);
    return textarea.value;
  }

  function cleanCssValue(value){
    return String(value == null ? '' : value).replace(/[<>{}\r\n]/g, '');
  }

  function downloadCanvas(canvas, filename){
    const a = document.createElement('a');
    a.download = filename;
    a.href = canvas.toDataURL('image/png');
    document.body.appendChild(a);
    a.click();
    a.remove();
  }

  function showOverlay(){
    if ($('#dnnbpc-overlay').length) return;
    const overlay = $(`
      <div id="dnnbpc-overlay" style="
        position:fixed;top:0;left:0;width:100%;height:100%;
        background:rgba(0,0,0,0.85);color:#fff;display:flex;
        flex-direction:column;align-items:center;justify-content:center;
        z-index:999999;font-family:system-ui,sans-serif;font-size:20px;">
        <div class="spinner" style="
          border:4px solid rgba(255,255,255,0.3);
          border-top:4px solid #fff;
          border-radius:50%;
          width:60px;height:60px;
          animation:dnnbpc-spin 1s linear infinite;
          margin-bottom:20px;">
        </div>
        <div>Preparing photo card</div>
      </div>
      <style>
        @keyframes dnnbpc-spin { from{transform:rotate(0deg);} to{transform:rotate(360deg);} }
      </style>
    `);
    $('body').append(overlay);
  }

  function hideOverlay(){
    $('#dnnbpc-overlay').fadeOut(200, function(){ $(this).remove(); });
  }

  function applyPlaceholders(str, data, context){
    if(!str) return '';
    const html = context === 'html';
    const val = function(value){
      return html ? escapeHtml(value) : cleanCssValue(value);
    };

    str = str.replace(/\{\{\s*title\s*\}\}/g, val(data.title));
    str = str.replace(/\{\{\s*date\s*\}\}/g, val(data.date));
    str = str.replace(/\{\{\s*image\s*\}\}/g, val(data.image));
    str = str.replace(/\{\{\s*site\s*\}\}/g, val(data.site));
    str = str.replace(/\{\{\s*shoulder\s*\}\}/g, val(data.shoulder || ''));
    str = str.replace(/\{\{\s*subheading\s*\}\}/g, val(data.subheading || ''));
    str = str.replace(/\{\{\s*opt\.([a-zA-Z0-9_\-]+)\s*\}\}/g, function(_, key){
      const value = data.opts && data.opts[key] ? data.opts[key] : '';
      return val(value);
    });

    return str;
  }

  function buildFromTemplate(data){
    const wrap = document.createElement('div');
    wrap.style.position = 'fixed';
    wrap.style.left = '-99999px';
    wrap.style.top = '0';
    wrap.style.zIndex = '-1';

    const style = document.createElement('style');
    style.textContent = applyPlaceholders(data.tpl_def.css, data, 'css');

    const holder = document.createElement('div');
    holder.innerHTML = applyPlaceholders(data.tpl_def.html, data, 'html');

    const card = holder.firstElementChild;
    if(card){
      card.style.width = '1080px';
      card.style.height = '1080px';
    }

    wrap.appendChild(style);
    wrap.appendChild(holder);
    document.body.appendChild(wrap);

    return {wrap:wrap, card:card || holder};
  }

  function cssNumber(el, prop){
    return parseFloat(window.getComputedStyle(el).getPropertyValue(prop)) || 0;
  }

  function px(value){
    return Math.round(value * 100) / 100 + 'px';
  }

  function clamp(value, min, max){
    return Math.max(min, Math.min(max, value));
  }

  function isVisibleText(el){
    return el && window.getComputedStyle(el).display !== 'none' && el.textContent.trim() !== '';
  }

  function scalePx(el, prop, scale, minValue, maxValue){
    if(!el){
      return;
    }

    const current = cssNumber(el, prop);
    if(current <= 0){
      return;
    }

    const max = typeof maxValue === 'number' ? maxValue : current;
    const next = Math.max(minValue, Math.min(max, current * scale));
    const styleProp = prop.replace(/-([a-z])/g, function(_, letter){
      return letter.toUpperCase();
    });
    el.style[styleProp] = px(next);
  }

  function getFitScale(currentHeight, targetHeight, minScale){
    if(currentHeight <= 0 || targetHeight <= 0){
      return 1;
    }

    return clamp(targetHeight / currentHeight, minScale, 1);
  }

  function fitCardCopy(card){
    if(!card || !card.querySelector){
      return;
    }

    const copy = card.querySelector('.jn24-copy');
    const hero = card.querySelector('.jn24-hero');
    const title = card.querySelector('.jn24-title');
    const subheading = card.querySelector('.jn24-subheading');
    const cta = card.querySelector('.jn24-cta');
    if(!copy || !hero || !title || !cta){
      return;
    }

    const shoulder = card.querySelector('.jn24-shoulder');
    const minGapFromHero = 52;
    const minGapToCta = 52;

    function getTextMetrics(){
      const visibleBlocks = [shoulder, title, subheading].filter(isVisibleText);
      if(!visibleBlocks.length){
        return null;
      }

      const firstBox = visibleBlocks[0].getBoundingClientRect();
      const lastBox = visibleBlocks[visibleBlocks.length - 1].getBoundingClientRect();
      const heroBottom = hero.getBoundingClientRect().bottom;
      const ctaTop = cta.getBoundingClientRect().top;
      const availableTop = heroBottom + minGapFromHero;
      const availableBottom = ctaTop - minGapToCta;

      return {
        firstBox: firstBox,
        lastBox: lastBox,
        ctaTop: ctaTop,
        availableTop: availableTop,
        availableBottom: availableBottom,
        availableHeight: Math.max(0, availableBottom - availableTop),
        textHeight: lastBox.bottom - firstBox.top,
        bottomGap: ctaTop - lastBox.bottom
      };
    }

    function scaleTextGroup(scale){
      const subheadingVisible = isVisibleText(subheading);
      const shoulderVisible = isVisibleText(shoulder);

      scalePx(title, 'font-size', scale, 44);
      scalePx(title, 'margin-top', scale, 0);
      title.style.lineHeight = scale < 1 ? '1.1' : title.style.lineHeight;

      if(shoulderVisible){
        scalePx(shoulder, 'font-size', scale, 34);
        scalePx(shoulder, 'margin-bottom', scale, 8);
        shoulder.style.lineHeight = scale < 1 ? '1.1' : shoulder.style.lineHeight;
      }

      if(subheadingVisible){
        scalePx(subheading, 'font-size', scale, 24);
        scalePx(subheading, 'margin-top', scale, 6);
        subheading.style.lineHeight = scale < 1 ? '1.12' : subheading.style.lineHeight;
      }
    }

    let metrics = getTextMetrics();
    if(!metrics){
      return;
    }

    if(metrics.availableHeight > 0 && metrics.textHeight > metrics.availableHeight){
      const scale = getFitScale(metrics.textHeight, metrics.availableHeight, 0.78);
      scaleTextGroup(scale);
    }

    metrics = getTextMetrics();
    if(metrics && metrics.bottomGap < minGapToCta){
      const targetHeight = metrics.textHeight - (minGapToCta - metrics.bottomGap);
      const scale = getFitScale(metrics.textHeight, targetHeight, 0.86);
      scaleTextGroup(scale);
    }

    metrics = getTextMetrics();
    if(!metrics || metrics.availableHeight <= 0){
      return;
    }

    const balancedTop = metrics.availableTop + (Math.max(0, metrics.availableHeight - metrics.textHeight) / 2);
    copy.style.top = px(cssNumber(copy, 'top') + balancedTop - metrics.firstBox.top);
  }

  function fetchViaAjax(postId){
    return new Promise(function(resolve, reject){
      $.post(cfg.ajax, {
        action: cfg.ajax_action || 'dnnbpc_get_card',
        nonce: cfg.nonce || '',
        post_id: postId
      }, function(resp){
        if(resp && resp.success && resp.data){
          resolve(resp.data);
          return;
        }

        reject((resp && resp.data && resp.data.message) || 'Failed to prepare card');
      }, 'json').fail(function(){
        reject('Failed to prepare card');
      });
    });
  }

  function fetchViaRest(postId){
    if(!cfg.rest || !window.fetch){
      return fetchViaAjax(postId);
    }

    const url = cfg.rest + '?post_id=' + encodeURIComponent(postId);
    return fetch(url, {
      credentials: 'same-origin',
      headers: {'X-WP-Nonce': cfg.rest_nonce || ''}
    }).then(function(resp){
      if(!resp.ok){
        throw new Error('REST request failed');
      }
      return resp.json();
    }).catch(function(){
      return fetchViaAjax(postId);
    });
  }

  function getCardData(postId){
    if(cfg.preload && String(cfg.preload.post_id) === String(postId)){
      return Promise.resolve(cfg.preload);
    }

    return fetchViaRest(postId);
  }

  $(document).on('click', '.dnnbpc-generate', function(){
    const postId = $(this).data('post-id') || 0;
    showOverlay();

    getCardData(postId).then(function(data){
      const built = buildFromTemplate(data);
      return waitForFonts().then(function(){
        return new Promise(function(resolve){
          setTimeout(function(){
            fitCardCopy(built.card);
            resolve(built);
          }, 160);
        });
      });
    }).then(function(built){
      return html2canvas(built.card, {
        backgroundColor: null,
        useCORS: true,
        width: 1080,
        height: 1080,
        scale: 1
      }).then(function(canvas){
        downloadCanvas(canvas, 'daily-new-nation-bangla-photocard-' + Date.now() + '.png');
        built.wrap.remove();
        hideOverlay();
      }).catch(function(){
        built.wrap.remove();
        throw new Error('Rendering failed.');
      });
    }).catch(function(error){
      hideOverlay();
      alert(error && error.message ? error.message : error);
    });
  });
})(jQuery);
