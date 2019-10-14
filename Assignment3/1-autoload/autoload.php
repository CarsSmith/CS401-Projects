<?php

\spl_autoload_register(function ($class) {

  $file = __DIR__ . DIRECTORY_SEPARATOR . $class . ".php";
  str_replace("/", DIRECTORY_SEPARATOR, $file);
  $filelower = strtolower($file);
  if (file_exists($filelower)) {
		include $filelower;
	} else {
    throw new Exception("Expected to find: <$file> for: <$class>");
  }
})
?>