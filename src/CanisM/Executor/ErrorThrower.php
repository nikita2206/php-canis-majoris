<?php

namespace CanisM\Executor;

class ErrorThrower implements ErrorHandler
{

    public function writeError(Executor $executor, $level, $message, $currentLine, $context = array())
    {
        $message = strtr($message, $context);

        $executor->sendOutput(sprintf(
            "%s error: %s", $executor->errors[$level], $message
        ) . ($level === self::ERROR_PARSE ? "" : " on line " . $currentLine) . "\n");
    }

}
