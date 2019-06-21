<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2019/1/28
 * Time: 11:05
 */
namespace app\common\exception;

use Exception;
use think\facade\App;
use think\Facade\Env;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;

class Http extends Handle
{
    protected $convert = [
        404 => '您访问的页面不存在哦~',
        500 => '服务器开小差去了~',
        502 => '服务器开小差去了~',
    ];

    public function render(Exception $e)
    {
        // 参数验证错误
        if ($e instanceof ValidateException) {
            return json($e->getError(), 422);
        }

        // 请求异常
        if ($e instanceof HttpException && request()->isAjax()) {
            return parent::render($e);
        } else if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            return $this->renderHttpException(new \think\exception\HttpException($statusCode, App::isDebug() ? $e->getMessage() : $this->convert[$statusCode]));
        } else {
            return $this->convertExceptionToResponse($e);
        }
    }
}