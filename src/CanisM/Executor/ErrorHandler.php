<?php

namespace CanisM\Executor;

interface ErrorHandler
{

    const ERROR_FATAL   = E_ERROR,
          ERROR_WARNING = E_WARNING,
          ERROR_PARSE   = E_PARSE,
          ERROR_NOTICE  = E_NOTICE,

          ERROR_USER_FATAL   = E_USER_ERROR,
          ERROR_USER_WARNING = E_USER_WARNING,
          ERROR_USER_NOTICE  = E_USER_WARNING,

          ERROR_ALL = E_ALL;

    public function writeError(Executor $executor, $level, $message, $currentLine, $context = array());

}
