<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/29
 * Time: 16:32
 * Email:499873958@qq.com
 */

namespace Xavier\Exceptions;


use Throwable;

class DbException extends BaseException
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}