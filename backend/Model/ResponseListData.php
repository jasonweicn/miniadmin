<?php
namespace backend\Model;

/**
 * 输出结果
 */
class ResponseListData
{

    public $json = '';

    public function __toString()
    {
        return $this->json;
    }

    /**
     * 构造
     * 
     * @param int       $code
     * @param string    $msg
     * @param int       $count
     * @param array     $data
     * @param boolean   $push
     * @return string
     */
    public function __construct($code, $msg, $count, $data = [], $push = true)
    {
        $result = array(
            'code' => $code,
            'msg' => $msg,
            'count' => $count,
            'data' => $data
        );
        
        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            $this->json = json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            $this->json = json_encode($result);
        }

        if ($push === true) {
            header("Content-Type: application/json; charset=utf-8");
            echo $this->json;
            die();
        }

        return $this->json;
    }
}
