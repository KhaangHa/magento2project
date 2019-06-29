<?php
/**
 * Created by PhpStorm.
 * User: magenest
 * Date: 18/08/2017
 * Time: 19:08
 */

namespace Magenest\Affiliate\Helper;

class ZendHttpHelper
{
    /**
     * @param string $url
     * @param array  $requestPost
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function sendRequest($url, $requestPost = null, $method = null)
    {
        $httpHeaders = new \Zend\Http\Headers();
        //        $httpHeaders->addHeaders([
        //            'Content-Type' => 'application/x-www-form-urlencoded',
        //        ]);
        $request = new \Zend\Http\Request();
        $request->setHeaders($httpHeaders);
        $request->setUri($url);
        $request->setMethod($method);

        if (!!$requestPost) {
            $request->getPost()->fromArray($requestPost);
        } else {
            $request->setMethod(\Zend\Http\Request::METHOD_GET);
        }

        $client = new \Zend\Http\Client();
        $options = [
            'adapter' => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => [
                CURLOPT_FOLLOWLOCATION => true
            ],
            'maxredirects' => 0,
            'timeout' => 30
        ];
        $client->setOptions($options);
        try {
            $response = $client->send($request);
            $rawResponseBody = $response->getBody();
            $responseBody = urldecode($rawResponseBody);
            $arrayReturn = [];
            parse_str($responseBody, $arrayReturn);

            return $arrayReturn;
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __($e->getMessage())
            );
        }
    }
}
