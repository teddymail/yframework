<?php
/**
 * CoreHttpException.php
 * Date: 2018/1/30 上午9:52
 * @since 2.8.1
 * @author yuekang<iyuekang@gmail.com>
 * @copyright © 2010 - 2018. Orivon,inc. All rights reserved.
 */

namespace Framework;

use Exception;
use Throwable;

class CoreHttpException extends Exception
{
    /**
     * http异常代码
     * @var array
     */
    private $_httpCode = [
        '400' => 'Bad Request',
        '403' => 'Forbidden',
        '404' => 'Not Found',
        '500' => 'Internet Server Error',
        '503' => 'Service Unavailable',
    ];

    /**
     * 全局异常构造
     * @param string $message
     * @param int $code
     */
    public function __construct($message = "", $code = 200)
    {
        $this->code = $code;
        if (empty($message)){
            $this->message = $this->_httpCode[$code];
            return;
        }
        $this->message = $message.' '.$this->_httpCode[$code];
    }

    /**
     * restful 响应
     *
     * @author yuekang<iyuekang@gmail.com>
     * @date 2018/1/30 上午10:02
     */
    public function response(){
        $data = [
            '__coreError' => [
                'code' => $this->getCode(),
                'message' => $this->getMessage(),
                'info' => [
                    'file' => $this->getFile(),
                    'line' => $this->getLine(),
                    'trace' => $this->getTrace()
                ]
            ]
        ];
        //写入日志


        //写入json公共头信息
        header('Content-Type:Application/json; Charset=utf-8');

        //输出错误(输出json错误并制定不要编码中文）
        die(json_encode($data,JSON_UNESCAPED_UNICODE));
    }

    /**
     * restful http异常响应
     * @param Exception $e  异常
     * @author yuekang<iyuekang@gmail.com>
     * @date 2018/1/30 上午10:09
     */
    public static function reponseErr(Exception $e){
        $data = [
            '__coreError' => [
                'code' => 500,
                'message' => $e->getMessage(),
                'info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]
        ];

        header('Content-Type:Application/json; Charset=utf-8');
        //输出错误但不禁止unicode转码
        die(json_encode($data));
    }


}