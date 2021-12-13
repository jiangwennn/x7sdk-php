<?php

namespace X7\Module\Basic\Request;

use Exception;
use RuntimeException;
use X7\Exception\BusinessException;
use X7\Exception\ParameterException;
use X7\Exception\ServerRequestException;
use X7\Exception\SignatureException;
use X7\Module\Basic\Model\GamePayEncrypData;
use X7\Request\BasicRequestReceiverInterface;
use X7\Request\Server\RequestParameterRetrieverInterface;
use X7\Utils\ParamTool;
use X7\Utils\Signature;
use X7\Utils\Traits\ToArray;

class GamePayCallbackRequest implements BasicRequestReceiverInterface
{
    use ToArray;

    public $encryp_data;

    public $extends_info_data;

    public $game_area;

    public $game_level;

    public $game_orderid;

    public $game_role_id;
    
    public $game_role_name;
    
    public $sdk_version;
    
    public $subject;

    public $xiao7_goid;

    public $sign_data;

    protected $x7PublicKey;

    public function __construct($x7PublicKey)
    {
        if (!ParamTool::isValidPublicKey($x7PublicKey)) {
            throw new BusinessException("failed: public key invalid ");
        }
        $this->x7PublicKey = $x7PublicKey;
    }


    public function validate(RequestParameterRetrieverInterface $retriever)
    {
        $paramArr = array_keys($this->toArray());
        $absentFields = [];
        foreach ($paramArr as $key) {
            if (!$retriever->has($key)) {
                $absentFields[] = $key;
            } else {
                $this->{$key} = $retriever->get($key);
            }
        }
        if (!empty($absentFields)) {
            throw new ParameterException("failed:field missing ".implode(",", $absentFields));
        }

        $signArr = $this->toArray();
        unset($signArr['sign_data']);
        ksort($signArr);
        $sourceStr = ParamTool::buildQueryString($signArr, false);

        $isPass = openssl_verify($sourceStr, base64_decode($this->sign_data), Signature::formatRsaPublicKey($this->x7PublicKey), OPENSSL_ALGO_SHA1);
        if (!$isPass) {
            throw new SignatureException("sign_data_verify_failed");
        }

        return $this;
    }

    /**
     * @return GamePayEncrypData
     */
    public function getDecryptedData()
    {
        try {
            $rawEncrypData = base64_decode($this->encryp_data);
            openssl_public_decrypt($rawEncrypData, $decryptedStr, Signature::formatRsaPublicKey($this->x7PublicKey));
            parse_str($decryptedStr, $decryptedData);
            return GamePayEncrypData::make($decryptedData);
        } catch (Exception $e) {
            throw new ServerRequestException("encryp_data_decrypt_failed");
        }
    }




}