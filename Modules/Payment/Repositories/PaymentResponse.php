<?php

/**
 * @Author : VuND
 */

namespace Modules\Payment\Repositories;

trait PaymentResponse
{
    private $responseData = array(
        'ErrorCode' => 1,
        'ErrorDescription' => 'Chưa thiết lập response.',
        'Data' => array()
    );

    /**
     * Set response data
     *
     * @param int $errorCode
     * @param string $message
     * @param array $data
     */
    protected function responseJson($errorCode, $message = null, $data = null)
    {
//        $this->responseData['Data']             = $errorCode == CODE_SUCCESS ? $data : null;
        $this->responseData['Data']             = $data;
        $this->responseData['ErrorCode']        = $errorCode;
        $this->responseData['ErrorDescription'] = $message ?: __('Xử lý thành công.');

//        if ($errorCode != CODE_SUCCESS && $data != null) {
//            $this->responseData['ErrorData'] = $data;
//        }

        return $this->responseData;
    }
}
