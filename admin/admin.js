(function($){
  const cfg = window.DNNBPCAdmin || {};

  function mediaPicker(inputId){
    const frame = wp.media({title:'Select Image', button:{text:'Use this image'}, multiple:false});
    frame.on('select', function(){
      const attachment = frame.state().get('selection').first().toJSON();
      $('#' + inputId).val(attachment.url || '');
    });
    frame.open();
  }

  function renderFields(schema, values){
    const $wrap = $('#dnnbpc-dynamic-fields').empty();

    Object.keys(schema).forEach(function(key){
      const field = schema[key];
      const id = 'tpl_' + key;
      const val = (values && values[key]) || field.default || '';
      const $row = $('<div/>', {'class':'dnnbpc-field-row'});
      const $label = $('<label/>', {'class':'dnnbpc-label', 'for':id}).text(field.label || key);
      let $input;

      if(field.type === 'color'){
        $input = $('<input/>', {type:'text', 'class':'dnnbpc-color', id:id, name:id}).val(val);
      } else if(field.type === 'image'){
        const $field = $('<div/>', {'class':'dnnbpc-image-field'});
        const $text = $('<input/>', {type:'text', id:id, name:id}).val(val);
        const $button = $('<button/>', {type:'button', 'class':'button dnnbpc-pick'}).attr('data-target', id).text('Select');
        $field.append($text, $button);
        $input = $field;
      } else {
        $input = $('<input/>', {type:'text', id:id, name:id}).val(val);
      }

      $row.append($label, $input);
      $wrap.append($row);
    });

    $('.dnnbpc-color').wpColorPicker();
    $wrap.off('click', '.dnnbpc-pick').on('click', '.dnnbpc-pick', function(e){
      e.preventDefault();
      mediaPicker($(this).data('target'));
    });
  }

  function loadTemplateUI(){
    const templates = cfg.templates || {};
    const active = $('#dnnbpc-active-template').val();
    const schema = (templates[active] && templates[active].fields) || {};

    renderFields(schema, {});

    $.post(cfg.ajax, {
      action: cfg.action || 'dnnbpc_get_saved_options',
      key: active,
      _ajax_nonce: cfg.nonce || ''
    }, function(resp){
      if(resp && resp.success && resp.data){
        renderFields(schema, resp.data);
      }
    }, 'json');
  }

  $(document).on('change', '#dnnbpc-active-template', loadTemplateUI);
  $(document).ready(loadTemplateUI);
})(jQuery);
