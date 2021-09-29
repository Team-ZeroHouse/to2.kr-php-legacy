<?php

function view($view_name)
{
  return new View($view_name);
}

function redirect($uri = null)
{
  if (empty($uri)) {
    header("Location: " . ORIGIN);
  } else {
    header("Location: " . $uri);
  }
  exit_with_code(302);
}

function exit_with_code($code, $clear = true)
{
  if ($clear) ob_clean();
  if (is_string($clear)) {
    // in this case, $clear is a output body message(plain/text)
    echo ($clear);
  }
  http_response_code($code);
  exit();
}
