<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/28
 * Time: 10:44
 * Email:499873958@qq.com
 */
declare(strict_types=1);

namespace Xavier\Exceptions;


use Throwable;

class BaseException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}