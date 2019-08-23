<?php
namespace SageAccounting;
class ExceptionHandler
{
    static function raiseError(string $exceptionName, string $exceptionMessage) {
      echo "ExceptionHandler: ";
      echo $exceptionName;
      echo $exceptionMessage;
      exit(1);
    }
}
?>
