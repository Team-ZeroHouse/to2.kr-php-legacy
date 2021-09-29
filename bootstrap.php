<?php
require_once('constants.php');

register_shutdown_function(function ()
{
  $e = error_get_last();
  if ($e)
  {
    exit_with_code(500, false);
  }
});

if (DEBUG)
{
  error_reporting(E_ALL);
}
else
{
  error_reporting(0);
}

if (BLACKLIST)
{
  header('Content-Type: text/plain; charset=utf-8');
  $blacklist = [];
  if (file_exists(__DIR__ . '/blacklist'))
  {
    $file = fopen(__DIR__ . '/blacklist', 'r');
    while (!feof($file))
    {
      $line = trim(fgets($file));
      if (!$line) continue;
      if ($line[0] == '#') continue;
      if (mb_strlen($line) != 2) continue;
      array_push($blacklist, $line);
    }
    fclose($file);
  }
}


spl_autoload_register(function ($classname)
{
  require_once('classes/' . $classname . '.class.php');
});

require_once('functions.php');
require_once('route.php');
