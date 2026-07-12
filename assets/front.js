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
          setTimeout(function(){ resolve(built); }, 160);
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
