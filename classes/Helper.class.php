<?php

class Helper
{
  private static $codes = [
    'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'm', 'n',
    'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
    'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    '1', '2', '3', '4', '5', '6', '7', '8', '9'
  ];

  public static function is_valid_web_url($url)
  {
    if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0)
      {
      if (filter_var($url, FILTER_VALIDATE_URL))
      {
        return true;
      }
    }
    return false;
  }

  public static function is_valid_recaptcha($recaptcha)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
      'secret' => RECAPTCHA_SECRET,
      'response' => $recaptcha,
      'remoteip' => Request::get_instance()->ip
    ]);
    $output = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($output);
    return $json->success;
  }

  public static function id_to_code($num)
  {
    $base = count(self::$codes);
    $num--;
    $temp = $num;
    $p = 1;

    while ($temp >= $base)
    {
      $temp /= $base;
      $p++;
    }

    if ($p < 3) $p = 3;

    $code = '';

    for ($p--; $p > 0; $p--)
    {
      $x = pow($base, $p);
      if ($num >= $x)
      {
        $q = floor($num / $x);
        $num -= $q * $x;
        $code .= self::$codes[$q];
      } else {
        $code .= self::$codes[0];
      }
    }

    $code .= self::$codes[$num];
    return $code;
  }

  public static function code_to_id($code)
  {
    $base = count(self::$codes);
    $p = strlen($code);
    $rev = strrev($code);
    $id = 1;

    for ($i = 0; $i < $p; $i++)
    {
      $id += pow($base, $i) * array_search($rev[$i], self::$codes);
    }

    return $id;
  }

  public static function get_real_ip()
  {
    $ip = null;
    if (isset($_SERVER['X-Forwarded-For']))
    {
      $ip = trim(explode(',', $_SERVER['X-Forwarded-For'])[0]);
    }
    else if (isset($_SERVER['Proxy-Client-IP']))
    {
      $ip = trim(explode(',', $_SERVER['Proxy-Client-IP'])[0]);
    }
    else if (isset($_SERVER['WL-Proxy-Client-IP']))
    {
      $ip = trim(explode(',', $_SERVER['WL-Proxy-Client-IP'])[0]);
    }
    else if (isset($_SERVER['HTTP_CLIENT_IP']))
    {
      $ip = trim(explode(',', $_SERVER['HTTP_CLIENT_IP'])[0]);
    }
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
      $ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    }
    else if (isset($_SERVER['X-Real-IP']))
    {
      $ip = trim(explode(',', $_SERVER['X-Real-IP'])[0]);
    }
    else if (isset($_SERVER['X-RealIP']))
    {
      $ip = trim(explode(',', $_SERVER['X-RealIP'])[0]);
    }

    if ($ip)
    {
      return $ip;
    }

    return $_SERVER['REMOTE_ADDR'];
  }
}
