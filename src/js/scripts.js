$(function () {
  const BASE = $('link[rel="base"]').attr('href');
  var timeMessage = 3000;

  // TRIGGER

  function ToastError(ErrMsg, ErrNo = null){
    var CssClass = (ErrNo == 'E_USER_NOTICE' ? 'trigger_info' : (ErrNo == 'E_USER_WARNING' ? 'trigger_alert' : (ErrNo == 'E_USER_ERROR' ? 'trigger_error' : 'trigger_success')));
    return "<div class='trigger trigger_ajax "+CssClass+"'>"+ErrMsg+"<span class='ajax_close'></span><div class='trigger_progress'></div></div>";
  }

  function Trigger(Message) {
    $('.trigger_ajax').fadeOut('fast', function () {
      $(this).remove();
    });
    $('body').before("<div class='trigger_modal'>" + Message + "</div>");
    $('.trigger_ajax').fadeIn();

    $('.trigger_progress').animate({"width": "100%"}, timeMessage, "linear", function () {
      $('.trigger_modal').fadeOut();
    });
  }

  $('.j_btn_mobile').click(function () {
    $('.main-menu').toggle('fast');
  });

  function TriggerClose(thistrigger) {
    thistrigger.fadeOut('fast', function () {
      $(this).remove();
    });
  }

  $(document).on('click', '.trigger_modal', function(){
    TriggerClose($(this));
  });

  // LOADING

  function Loading(bool) {
    if (bool) {
      $('body').append('<div class="load_alpha" style="position: fixed; width: 100%; height: 100vh; z-index: 99999; background: url(src/assets/images/bg.png) rgba(0,0,0,0.5); top: 0; left: 0;">'+
      '   <div class="ajax_load" style="z-index: 1101; width: 100%; height: 100vh; background: url(src/assets/images/load_w.gif); background-repeat: no-repeat; background-position: center;"></div>'+
      '</div>');
    }else {
      $('.load_alpha').remove();
    }
  }

  $('.j_close_modal').click(function () {
    $('.modal').fadeOut();
  });

  function Ajax(AjaxFile, AjaxData) {
    var contentType = 'application/x-www-form-urlencoded';
    if (typeof(AjaxData) == 'object') {
      contentType = false;
    }

    $.ajax({
      method: 'POST',
      url: BASE + '/src/ajax/' + AjaxFile + '.ajax.php',
      data: AjaxData,
      dataType: 'json',
      processData: false,
      contentType: contentType,
      beforeSend: function () {
        Loading(true);
      },
      success: function (data) {
        Loading(false);

        if (data.trigger) {
          Trigger(data.trigger);
        }

        if (data.location) {
          if (data.trigger) {
            setTimeout(function(){ location.href = data.location; }, timeMessage);
          }else{
            location.href = data.location;
          }
        }

        if (data.reload) {
          if (data.trigger) {
            setTimeout(function(){ location.reload(); }, timeMessage);
          }else{
            location.reload();
          }
        }

        if (data.content) {
          $.each(data.content, function (key, value) {
            $(key).fadeTo('300', '0.5', function () {
              $(this).html(value).fadeTo('300', '1');
            });
          });
        }

        if (data.append) {
          $.each(data.append, function (key, value) {
            $(key).append(value);
          });
        }

        if (data.reset) {
          $.each(data.reset, function (key, value) {
            $(key)[0].reset();
            $(key).find('input[name="id"]').val("");
          });
        }

        if (data.fadeIn) {
          $.each(data.fadeIn, function (key, value) {
            $(key).fadeIn();
          });
        }

        if (data.fadeOut) {
          $.each(data.fadeOut, function (key, value) {
            $(key).fadeOut();
          });
        }

        if (data.form) {
          $.each(data.form, function (key, value) {
            $.each(value, function(key2, value2){
              if ($(key).find("input[name='"+key2+"']").attr('type') == 'checkbox') {
                if (value2 == 1) {
                  $(key).find("input[name='"+key2+"']").prop('checked', true);
                }else {
                  $(key).find("input[name='"+key2+"']").prop('checked', false);
                }
              }else {
                $(key).find("input[name='"+key2+"']").val(value2);
                $(key).find("select[name='"+key2+"']").val(value2);
              }
            });
          });
        }

        if (data.removeClass) {
          $.each(data.removeClass, function (key, value) {
            $(key).removeClass(value);
          });
        }

        if (data.addClass) {
          $.each(data.addClass, function (key, value) {
            $(key).addClass(value);
          });
        }

        if (data.attr) {
          $.each(data.attr, function (key, value) {
            $.each(value, function (key2, value2) {
              $(key).attr(key2, value2);
            });
          });
        }

        if (data.tiny) {
          $.each(data.tiny, function (key, value) {
            tinymce.get(key).execCommand('mceCleanup');
            tinymce.get(key).execCommand('mceInsertContent', false, value);
          });
        }

        if (data.open) {
          $.each(data.open, function (key, value) {
            var win = window.open(key, value);
            if (win) {
              //Browser has allowed it to be opened
              win.focus();
            } else {
              //Browser has blocked it
              alert('Por favor, permita pop-ups para este site');
            }
          });
        }
      },
      error: function(){
        Loading(false);
        Trigger(ToastError("Erro desconhecido, contate o desenvolvedor", "E_USER_ERROR"));
      }
    });
  }

  $('.j_form').submit(function(e){
    e.preventDefault();

    var AjaxFile = $(this).find('input[name="AjaxFile"]').val();
    var AjaxData = new FormData(this);

    Ajax(AjaxFile, AjaxData);
  });

  $(document).on("click", '.j_ajax_generic', function(){
    var AjaxFile = $(this).attr("ajaxfile");
    var AjaxAction = $(this).attr("ajaxaction");
    var AjaxData = $(this).attr("ajaxdata") + '&AjaxFile=' + AjaxFile + '&AjaxAction=' + AjaxAction;

    Ajax(AjaxFile, AjaxData);
  });

  $(".j_open_modal").click(function(e) {
    e.preventDefault();

    let folder = $(this).attr("path");
    $('#UploadForm')[0].reset();
    $('#UploadForm').find("input[name='path']").val(folder);
    $('#Modal').fadeIn();
  });
})
