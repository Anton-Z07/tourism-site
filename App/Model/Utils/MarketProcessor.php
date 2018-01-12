<?php

namespace App\Model\Utils;

use Luracast\Restler\RestException,
    Google_Client,
    Google_Service_AndroidPublisher,
    Google_Auth_AssertionCredentials,
    Google_Exception,
    Exception;

/**
 * https://developers.google.com/android-publisher/getting_started
 * https://console.developers.google.com/project/
 * https://play.google.com/apps/publish
 **/
class MarketProcessor {
    
    public static function getProductsFromTokens($market, $config, $list)
    {
        if($market === 'android') {
            return static::processAndroid($config, $list);
        } else if($market === 'ios') {
            return static::processIOs($config, $list);
        }
        
        throw new RestException(400, "Market place unsupported!");
    }
    
    private static function processAndroid($config, $list)
    {
        if(!file_exists($config['keyFile']) || !is_readable($config['keyFile'])) {
            throw new RestException(500, "Auth key file not found!");
        }
        
        $client = new Google_Client();
        
        if (isset($_SESSION['service_token'])) {
            $client->setAccessToken($_SESSION['g_token']);
        }
        
        $cred = new Google_Auth_AssertionCredentials(
            $config['serviceAccount'],
            ['https://www.googleapis.com/auth/androidpublisher'],
            file_get_contents($config['keyFile'])
        );
        
        $client->setAssertionCredentials($cred);
        
        if($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion($cred);
        }
        
        $_SESSION['g_token'] = $client->getAccessToken();

        //$client->setDeveloperKey($options['appKey']);
        $client->setDefer(true);
        $service = new Google_Service_AndroidPublisher($client);
        $result = [];
        
        foreach($list as $item) {
            $hash = substr($item['productId'], strpos($item['productId'], $item['packageName'].'.') +  strlen($item['packageName'].'.'));
            
            if(strlen($hash) !== 8) {
                continue;
            }
            
            $request = $service->purchases_products->get($item['packageName'], $item['productId'], $item['purchaseToken'], []);
            
            try {
                $products = $client->execute($request);
            } catch (Google_Exception $e) {
                throw new RestException(400, $e->getCode().': '.$e->getMessage());
            } catch (Exception $e) {
                throw new RestException(400, "");
            }
            $result[$hash] = $products;
        }
        //$packageName = $config['appCode'];
        //$productId = $config['appCode'].'.6';
        //$token = 'ekofgmjccpgfjplilodcefcd.AO-J1OwLMqg_c37LPiNnxCB_oorS0LMTN79tfTu6GeGHpaZNLj1SYnIifD4Y9pMsT8dTxhwjTVvtwghb20e2btKgqcg_6yDwfxKcxNOY4l3Q15VEG7MdjlOzeJCgrB9LPFhbbWA757fpC8JHH5douGt_0WvVed-Rq1qsU66cwpAnf0CnrAhwVGI';
        return $result;
    }
    
    private static function processIOs($config, $list)
    {
        $result = [];
        
        try {
            $products = static::verifyIOsReceipt($list[0]['receipt'], $config['sandbox']);
        } catch (\Exception $e) {
            throw new RestException(400, $e->getCode().': '.$e->getMessage());
        }
        
        foreach($products['products'] as $item) {
            $hash = substr($item['product_id'], strpos($item['product_id'], $products['bundle_id'].'.') + strlen($products['bundle_id'].'.'));
            
            if(strlen($hash) !== 8) {
                continue;
            }
            
            $result[$hash] = $products;
        }
        return $result;
    }

    private static function verifyIOsReceipt($base64EncodedReceipt, $isInSandbox = false)
    {        
        if ($isInSandbox) {
            $verification_uri = 'https://sandbox.itunes.apple.com/verifyReceipt';
        } else {
            $verification_uri = 'https://buy.itunes.apple.com/verifyReceipt';
        }
        
        $postData = json_encode(array('receipt-data' => $base64EncodedReceipt));
 
        $ch = curl_init($verification_uri);
        
        curl_setopt_array($ch, array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_POST  => true,
            CURLOPT_POSTFIELDS => $postData
        ));
        
        $response = curl_exec($ch);
        $errno    = curl_errno($ch);
        $errmsg   = curl_error($ch);
        curl_close($ch); 
       
        if ($errno != 0) {
            throw new Exception($errmsg, $errno);
        } 
        
        $data = json_decode($response); 
        
        if (!is_object($data)) {
            throw new Exception('Invalid verification response');
        } 
       
        if (!isset($data->status)) {        	
            throw new Exception('Invalid verification response');
        }       
       
        if ($data->status != 0) {
            if ($data->status == 21007 && !$isInSandbox) {
                return static::verifyIOsReceipt($base64EncodedReceipt, true);        		
            } else {
                throw new Exception('Invalid receipt');	
            }       	
        }
        
        if(!isset($data->receipt->in_app) || !is_array($data->receipt->in_app) || count($data->receipt->in_app) < 1) {
            throw new Exception('Invalid receipt');	
        }
        $result = [
            'products' => [],
            'app_item_id'       =>  $data->receipt->app_item_id,
            'bundle_id'         =>  $data->receipt->bundle_id,
        ];
        
        foreach($data->receipt->in_app as $inapp) {
            array_push($result['products'], [
                'quantity'          =>  $inapp->quantity,
                'product_id'        =>  $inapp->product_id,
                'transaction_id'    =>  $inapp->transaction_id,
                'purchase_date'     =>  $inapp->purchase_date,
                'purchase_date_ms'  =>  $inapp->purchase_date_ms,
            ]);
        }
        return $result;
    }
}
