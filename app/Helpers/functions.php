<?php
/**
 * Created by PhpStorm.
 * User: CooperFu
 * Date: 2018/9/19
 * Time: 14:50
 */

/**
 *  判断参数是否存在，如果存在返回$trueParams，否则返回$falseParams
 * @param $params
 * @param $falseParams
 * @param null $trueParams
 * @return null
 */
function issetParams($params, $falseParams, $trueParams = null)
{
    if (is_array($params)) {
        foreach ($params as $requestArr => $requestKey) {
            $requestValue = \Illuminate\Support\Facades\Input::get($requestArr);
            return isset($requestValue[$requestKey]) ? $requestValue[$requestKey]: $falseParams;
        }
    }
    $params = \Illuminate\Support\Facades\Input::get($params);
    if (!empty($params)) {
        if ($trueParams === null)
            return $params;
        return $trueParams;
    }
    return $falseParams;
}

/**
 * 返回restful架构风格数据
 * @param $data
 * @param int $code
 * @param string $message
 * @return string
 */
function returnJson($data, $code = 200, $message = '')
{
    return json_encode(['code' => $code, 'message' => $message, 'data' => $data]);
}

/**
 * 时间格式化
 * @param $time
 * @param string $format
 * @return false|string
 */
function dateFormat($time, $format = 'Y-m-d H:i:s')
{
    return date($format, $time);
}

/**
 * value为空返回defaultMark，否则返回value
 * @param $value
 * @param string $defaultMark
 * @return string
 */
function ifValueEmpty($value, $defaultMark = '--') {
    if (empty($value)) {
        return $defaultMark;
    }
    return $value;
}