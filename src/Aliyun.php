<?php
/**
 * @author    : Death-Satan
 * @date      : 2021/8/19
 * @createTime: 19:11
 * @company   : Death撒旦
 * @link      https://www.cnblogs.com/death-satan
 */

namespace SaTan\Think\Sms\driver;

use Darabonba\OpenApi\Models\Config;
use SaTan\Think\Sms\Driver;
use Satan\Think\Sms\interfaces\SmsInterface;

class Aliyun extends Driver
{

    protected function createSms (): SmsInterface
    {
        if (app()->isDebug())
        {
            \think\facade\Log::debug('aliyun sms config:',$this->config);
        }
        return new AlunInterFaces(new Config($this->config),$this->config);
    }
}