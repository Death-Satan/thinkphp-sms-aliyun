<?php
/**
 * @author    : Death-Satan
 * @date      : 2021/8/19
 * @createTime: 19:21
 * @company   : Death撒旦
 * @link      https://www.cnblogs.com/death-satan
 */

namespace SaTan\Think\Sms\driver;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddSmsSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddSmsTemplateRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\DeleteSmsSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\DeleteSmsTemplateRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\ModifySmsSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\ModifySmsTemplateRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsSignRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsTemplateRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendBatchSmsRequest;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Exception\TeaRetryError;
use AlibabaCloud\Tea\Exception\TeaUnableRetryError;
use Darabonba\OpenApi\Models\Config;


class AlunInterFaces implements \SaTan\Think\Sms\interfaces\SmsInterface
{
    protected Dysmsapi $dy_sms_api;

    public function __construct (Config $_config,$config)
    {
        $this->dy_sms_api = new Dysmsapi($_config);
    }

    /**
     * @param $params
     *
     * @return SendSmsRequest
     */
    protected function param($params):SendSmsRequest
    {
        return new SendSmsRequest($params);
    }

    /**
     * @inheritDoc
     * @throws TeaUnableRetryError|TeaRetryError|TeaError
     */
    public function sendSms (int $phone, string $sign_name, string $template_code, array $TemplateParam=[], array $extras = [])
    {
        $params = [
            'phoneNumbers'=>$phone,
            'signName'=>$sign_name,
            'templateCode'=>$template_code,
            'templateParam'=>collect($TemplateParam)->toJson(),
        ];
        if (!empty($extras['smsUpExtendCode'])) $params['smsUpExtendCode']=$extras['smsUpExtendCode'];
        if (!empty($extras['outId'])) $params['outId'] = $extras['outId'];

        $request = $this->param($params);
        return $this->dy_sms_api->sendSms($request);
    }
    /**
     * @inheritDoc
     */
    public function sendBatchSms (array $phones, array $sign_name, string $template_code, array $extras = [])
    {
        $params = [
            'phoneNumberJson'=>collect($phones)->toJson(),
            'signNameJson'=>collect($sign_name)->toJson(),
            'templateCode'=>$template_code,
        ];
        if (!empty($extras['templateParamJson']))$params['templateParamJson']=collect($extras['templateParamJson'])->toJson();

        if (!empty($extras['smsUpExtendCodeJson']))$params['smsUpExtendCodeJson']=collect($extras['smsUpExtendCodeJson'])->toJson();
        return $this->dy_sms_api->sendBatchSms(new SendBatchSmsRequest($params));
    }

    /**
     * @inheritDoc
     */
    public function addSmsSign (string $sign_name, string $sign_source, string $remark, array $extras = [])
    {
        $params = [
            'signName'=>$sign_name,
            'signSource'=>(int)$sign_source,
            'remark'=>$remark
        ];
        if (!empty($extras['signFileList'])) $params['signFileList'] = $extras['signFileList'];
        return $this->dy_sms_api->addSmsSign(new AddSmsSignRequest($params));
    }

    /**
     * @inheritDoc
     */
    public function deleteSmsSign (string $sign_name,array $extras=[])
    {
        return $this->dy_sms_api->deleteSmsSign(new DeleteSmsSignRequest(['signName'=>$sign_name]));
    }

    /**
     * @inheritDoc
     */
    public function modifySmsSign (string $sign_name, string $sign_source, string $remark, array $extras = [])
    {
        $params = [
            'signName'=>$sign_name,
            'signSource'=>(int)$sign_source,
            'remark'=>$remark
        ];
        if (!empty($extras['signFileList'])) $params['signFileList'] = $extras['signFileList'];
        return $this->dy_sms_api->modifySmsSign(new ModifySmsSignRequest($params));
    }

    /**
     * @inheritDoc
     */
    public function querySmsSign (string $sign_name = '', array $extras = [])
    {
        return $this->dy_sms_api->querySmsSign(new QuerySmsSignRequest(['signName'=>$sign_name]));
    }

    /**
     * @inheritDoc
     */
    public function addSmsTemplate (string $template_name, int $template_type, string $template_content, string $remark, array $extras = [])
    {
        $params = [
            'templateType'=>$template_type,
            'templateName'=>$template_name,
            'templateContent'=>$template_content,
            'remark'=>$remark
        ];
        return $this->dy_sms_api->addSmsTemplate(new AddSmsTemplateRequest($params));
    }

    /**
     * @inheritDoc
     */
    public function deleteSmsTemplate (string $template_code, array $extras = [])
    {
        return $this->dy_sms_api->deleteSmsTemplate(new DeleteSmsTemplateRequest([
            'templateCode'=>$template_code,
        ]));
    }

    /**
     * @inheritDoc
     */
    public function modifySmsTemplate (string $template_code, int $template_type, string $template_name, string $template_content, string $remark, array $extras = [])
    {
        return $this->dy_sms_api->modifySmsTemplate(new ModifySmsTemplateRequest([
            'templateType'=>$template_type,
            'templateName'=>$template_name,
            'templateCode'=>$template_code,
            'templateContent'=>$template_content,
            'remark'=>$remark,
        ]));
    }

    /**
     * @inheritDoc
     */
    public function querySmsTemplate (string $template, array $extras = [])
    {
        return $this->dy_sms_api->querySmsTemplate(new QuerySmsTemplateRequest([
            'templateCode'=>$template
        ]));
    }
}