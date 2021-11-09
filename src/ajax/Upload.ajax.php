<?php
//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$File = explode('.', basename($_SERVER['PHP_SELF']))[0];

function ToastError($ErrMsg, $ErrNo = null)
{
  $CssClass = ($ErrNo == E_USER_NOTICE ? 'trigger_info' : ($ErrNo == E_USER_WARNING ? 'trigger_alert' : ($ErrNo == E_USER_ERROR ? 'trigger_error' : 'trigger_success')));
  return "<div class='trigger trigger_ajax {$CssClass}'>{$ErrMsg}<span class='ajax_close'></span><span class='ajax_close'></span><div class='trigger_progress'></div></div>";
}

//VALIDA AÇÃO
if ($PostData && $PostData['AjaxFile'] == $File):
  //PREPARA OS DADOS
  $Case = $PostData['AjaxAction'];
  unset($PostData['AjaxAction'], $PostData['AjaxFile']);

  //SELECIONA AÇÃO
  switch ($Case):
    case 'up':
      if (!empty($PostData['path'])) {
        $attachment = ( isset($_FILES['attachment']['tmp_name']) ? $_FILES['attachment'] : null );

        if (!empty($attachment['tmp_name'])) {
          switch ($attachment['type']):
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'image/png':
            case 'image/x-png':
              $fileName = time() . str_pad(rand(0, 999), 3, "0", STR_PAD_LEFT) . '.' . pathinfo($attachment['name'], PATHINFO_EXTENSION);

              if ($PostData['path'] != "/") {
                $PostData['path'] = $PostData['path'] . "/";
              }

              $PostData['path'] = explode("/", $PostData['path']);
              unset($PostData['path'][0]);
              $PostData['path'] = implode("/", $PostData['path']);

              if (move_uploaded_file($attachment['tmp_name'], __DIR__ . "/../../{$PostData['path']}{$fileName}")) {
                $jSON['trigger'] = ToastError("Sucesso no upload");
                $jSON['reset']['#UploadForm'] = true;
                $jSON['open'] = ["{$PostData['path']}{$fileName}" => "_blank"];
                $jSON['fadeOut']['#Modal'] = true;
                $jSON['append']['ul'] = '<li>
                  <a href="'.$PostData['path'].$fileName.'" target="_blank">
                    <i class="far fa-file"></i>
                    <p>'.$fileName.'</p>
                  </a>
                </li>';
              } else {
                $jSON['trigger'] = ToastError("Erro ao fazer upload", E_USER_WARNING);
              }
              break;
            default:
              $jSON['trigger'] = ToastError("Tipo de arquivo inválido, envie imagens JPG ou PNG!", E_USER_WARNING);
          endswitch;
        }
      }
      break;
  endswitch;

  //RETORNA O CALLBACK
  if ($jSON):
    echo json_encode($jSON);
  else:
    $jSON['trigger'] = ToastError("Erro", E_USER_ERROR);
    echo json_encode($jSON);
  endif;
else:
  //ACESSO DIRETO
  die("<br><br><br><center><h1>Acesso restrito</h1></center>");
endif;
