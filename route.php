<?php

Route::get('', view('index.html'));

Route::get(CODE_REGEX, function (Request $req)
{
  $code = substr($req->path, 1);
  $id = Helper::code_to_id($code);

  $db = DB::get_instance();
  $query = "SELECT * FROM `shortUrl` WHERE `id`={$id}";
  $result = $db->query($query);
  if ($result->num_rows)
  {
    $query = "UPDATE `shortUrl` SET `rCount`=`rCount`+1 WHERE `id`={$id}";
    $db->real_query($query);

    $row = $result->fetch_assoc();

    if (isset($_SERVER['ADDI_QUERY']))
    {
      $_SERVER['ADDI_QUERY'] = str_replace('a&', '', $_SERVER['ADDI_QUERY']);
      // https://to2.kr/bFw?addi=Y&id=0-1

      if (strpos($row['url'], '?') === false)
      {
        $row['url'] .= '?' . $_SERVER['ADDI_QUERY'];
      }
      else
      {
        $row['url'] .= '&' . $_SERVER['ADDI_QUERY'];
      }
    }
    redirect($row['url']);
  }
  else
  {
    redirect();
  }
});

Route::get('.*', function () {
  redirect();
});

Route::post('shorten', function (Request $req)
{

  if (!$req->msg->has('url')) exit_with_code(400, 'url 없음');
  $url = $req->msg->get('url');
  if (!Helper::is_valid_web_url($url)) exit_with_code(400, 'url 비정상');

  if (!$req->msg->has('recaptcha')) exit_with_code(400, 'reCaptcha 없음');
  $recaptcha = $req->msg->get('recaptcha');
  if (!Helper::is_valid_recaptcha($recaptcha)) exit_with_code(400, 'reCaptcha 비정상');

  $db = DB::get_instance();
  $url = $db->escape_string($url);

  $query = "SELECT * FROM `shortUrl` WHERE `url`='{$url}'";
  $result = $db->query($query);
  if ($result->num_rows)
  {
    $row = $result->fetch_assoc();
    $query = "UPDATE `shortUrl` SET `sCount`=`sCount`+1 WHERE `id`={$row['id']}";
    $db->real_query($query);
    echo (ORIGIN . '/' . $row['code']);
  }
  else
  {
    $query = "INSERT INTO `shortUrl` (`regDate`, `url`, `code`, `ip`) VALUES (NOW(), '{$url}', '', '{$req->ip}')";
    $db->real_query($query);
    $id = $db->insert_id;
    $code = Helper::id_to_code($id);
    $query = "UPDATE `shortUrl` SET `code`='{$code}' WHERE `id`={$id}";
    $db->real_query($query);
    echo (ORIGIN . '/' . $code);
  }
});
