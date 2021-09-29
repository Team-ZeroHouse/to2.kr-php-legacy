<?php

class Route
{
  private static $methods = [
    'GET' => [],
    'POST' => []
  ];

  private static function process_response(Request $req, $res)
  {
    if (is_callable($res))
    {
      $res($req);
      return;
    }

    if ($res instanceof View)
    {
      $res->show();
      return;
    }

    exit_with_code(501);
  }

  public static function routing(Request $req)
  {
    header('Content-Type: text/plain; charset=utf-8');
    if (array_key_exists($req->method, self::$methods))
    {
      $method = self::$methods[$req->method];
      foreach ($method as $regex => $res)
      {
        if (preg_match($regex, $req->path))
        {
          self::process_response($req, $res);
          return;
        }
      }

      exit_with_code(404);
    }
    else
    {
      exit_with_code(405);
    }
  }

  private static function regex($regex)
  {
    return '/^\/' . $regex . '$/';
  }

  public static function get($regex, $res)
  {
    self::$methods['GET'][self::regex($regex)] = $res;
  }

  public static function post($regex, $res)
  {
    self::$methods['POST'][self::regex($regex)] = $res;
  }

  private function __construct()
  {
  }
}
