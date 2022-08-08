<?php
set_exception_handler([new App\Exception\ExceptionHandler(), 'convertWarningsAndNoticesException']);
set_exception_handler([new App\Exception\ExceptionHandler(), 'handler']);
