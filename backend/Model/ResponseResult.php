<?php
namespace backend\Model;

/**
 * 输出结果
 */
class ResponseResult
{

    public $json = '';

    public function __toString()
    {
        return $this->json;
    }

    /**
     * 构造
     * @param int    $suc      状态：0失败，1成功
     * @param string $info     信息
     * @param string $err_code 错误代码
     * @param array  $data     数据
     */
    public function __construct($suc, $info, $err_code = '', $data = [], $ext_info = null, $push = true)
    {
        $result = array(
            'suc' => $suc,
            'info' => $info,
            'err_code' => $err_code,
            'data' => $data
        );

        if ($suc == 0 && $err_code != '') {
            $result['info'] .= '[' . $err_code . ']';
        }

        if ($ext_info != null) {
            $result['info'] .= '[' . $ext_info . ']';
            $result['ext_info'] = $ext_info;
        }

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
