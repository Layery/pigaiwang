<?php
/**
 * Created by PhpStorm.
 * User: llf
 * Date: 2018/8/6
 * Time: 17:52
 */
namespace app\haoyi\behavior;

use think\Request;
use traits\controller\Jump;

class LogWriter
{
    use Jump;
    public function run(Request $request, $params)
    {
        

        p('logwriter');

    }
}