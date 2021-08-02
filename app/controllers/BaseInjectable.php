<?php
/**
 * Created by PhpStorm.
 * User: Theophilus
 * Date: 8/12/2018
 * Time: 12:46 AM
 */

namespace App\Controllers;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

abstract class BaseInjectable extends \Phalcon\Di\Injectable {
    const ERROR_NOT_FOUND           = 1;
    const ERROR_INVALID_REQUEST     = 2;
    private $messages               = [];
    private $_apiKey, $_apiId;
    public $_phpMailer;

    public function __construct() {
        $this->_phpMailer   = new PHPMailer(true);
    }

    public function getApiKey(){
        return $this->_apiKey;
    }

    public function getApiId(){
        return $this->_apiId;
    }

    public function setApiKey($keyString){
        $this->_apiKey  = $keyString;
    }

    public function setApiId($keyIdString){
        $this->_apiId   = $keyIdString;
    }

    public function displayJsonRow($content):string {
        return json_encode($content);
    }

    public function getExternalBaseUri($uri=""){
        return empty($uri) || is_null($uri) ? "https://artdeals.ng/" : $uri;
    }

    /**
     * @param string $type
     * @return array|string
     */
    public function getMessages($type = 'string'){
        return $type == 'array' ? $this->messages : implode(',', $this->messages);
    }

    //This method should be used for associative array
    /**
     * @param type $array
     * @return type array;
     */
    public function _buildRequestQuery($array = array()){
        if($this->request->isPost()){
            $_POST   = $this->request->getPost() + $array;
            return $getPost     = $this->request->getPost();
        }
        else{
            $_GET   = $this->request->getQuery() + $array;
            return $getQuery    = $this->request->getQuery();
        }
    }

    //Get Array Conditions to replace post or get Query
    //Note that the index 0 returned is array and 1 is strings
    //Use like this $getWhatever = $this->_setArrayParameters($array);
    /**
     * @param array $array
     * @return array
     */
    protected function _setArrayParameters(array $array){
        $strings = '';
        $results = array();
        foreach($array as $key => $value){
            $results[$key] = $value;
            $strings .= $key.' = :'.$key.': AND ';
        }
        return array(
            $results, substr($strings,0,-4)
        );
    }

    //Remove empty getPost() | getQuery() request

    /**
     * @param string $rkey
     * @param string $useKey
     */
    protected function _removeEmptyQueryParams($rkey="", $useKey=""){
        if($this->request->isPost()){
            foreach($this->request->getPost() as $key => $value){
                if(empty($value) || is_null($value)){
                    unset($_POST[$key]);
                }
            }
            if(!empty($rkey) || !is_null($rkey)){
                $lid    = $this->request->getPost($rkey);
                unset($_POST[$rkey]);
                $_POST[$useKey]    = $lid;
            }
        }
        else{
            foreach($this->request->getQuery() as $key => $value){
                if(empty($value) || is_null($value)){
                    unset($_GET[$key]);
                }
            }
            if(!empty($rkey) || !is_null($rkey)){
                $lid    = $this->request->getQuery($rkey);
                unset($_GET[$rkey]);
                $_GET[$useKey]  = $lid;
            }
        }
    }

    /**
     * @param string $type
     * @return array
     */
    //This method create a binding value based
    //Empty post remooved from the getPost() returned
    protected function _bindQueryParameters($type='post'){
        $results = array();
        switch ($type) {
            case 'post':
                foreach($this->request->getPost() as $key => $value){
                    $results[$key] = $value;
                }
                return $results;
                break;

            case 'get':
                foreach($this->request->getQuery() as $key => $value){
                    if($key !== '_url'){
                        $results[$key] = $value;
                    }
                }
                return $results;
                break;
        }
    }

    /**
     * @return array
     */
    protected function _requestTypeQuery():array {
        return $this->request->isGet() ? $this->request->getQuery() : $this->request->getPost();
    }

    /**
     * @param string $type
     * @return false|string
     */
    //This method creates queries of values for binding
    protected function _queryBindConditions($type='post') {
        $strings = '';
        switch ($type) {
            case 'post':
                foreach ($this->request->getPost() as $key => $value) {
                    $strings .= $key . ' = :' . $key . ': AND ';
                }
                return substr($strings, 0, -4);
                break;

            case 'get':
                foreach ($this->request->getQuery() as $key => $value) {
                    if ($key !== '_url') {
                        $strings .= $key . ' = :' . $key . ': AND ';
                    }
                }
                return substr($strings, 0, -4);
                break;
        }
    }

    /**
     * to use subclass must follow definition pattern
     * @param $getVideo
     * @return string
     */
    protected function _getYtImgRow($getVideo){
        $imageUrlRow    = "";
        $ytApiRow       = is_array($getVideo) ? @$getVideo['youtube_api'] : @$getVideo->youtube_api;
        $ytApiArray     = json_decode($ytApiRow, true);
        if(!is_null($ytApiArray)) {
            $ytSnippet = array_key_exists("items", $ytApiArray) ? $ytApiArray['items'][0]['snippet'] : $ytApiArray['videos'][0]['snippet'];
            //var_dump($ytSnippet["thumbnails"]); exit;
            if (array_key_exists('maxres', $ytSnippet["thumbnails"])) {
                $imageUrlRow = $ytSnippet["thumbnails"]["maxres"]["url"];
            }
            elseif (array_key_exists('standard', $ytSnippet["thumbnails"])) {
                $imageUrlRow = $ytSnippet["thumbnails"]["standard"]["url"];
            }
            elseif (array_key_exists('high', $ytSnippet["thumbnails"])) {
                $imageUrlRow = $ytSnippet["thumbnails"]["high"]["url"];
            }
            elseif (array_key_exists('medium', $ytSnippet["thumbnails"])) {
                $imageUrlRow = $ytSnippet["thumbnails"]["medium"]["url"];
            }
        }
        return $imageUrlRow;
    }

    /**
     * @param $getVideo
     * @param $imageSize
     * @return string
     */
    protected function _getYtSizeTypeRow($getVideo, $imageSize){
        $imageUrlRow    = "";
        $ytApiRow       = is_array($getVideo) ? @$getVideo['youtube_api'] : @$getVideo->youtube_api;
        $ytApiArray     = json_decode($ytApiRow, true);
        if(!is_null($ytApiArray)) {
            $ytSnippet = array_key_exists("items", $ytApiArray) ? $ytApiArray['items'][0]['snippet'] : $ytApiArray['videos'][0]['snippet'];
            //var_dump($ytSnippet["thumbnails"]); exit;
            if (array_key_exists($imageSize, $ytSnippet["thumbnails"])) {
                $imageUrlRow = $ytSnippet["thumbnails"][$imageSize]["url"];
            }
        }
        return $imageUrlRow;
    }

    /**
     * @param $getVideo
     * @param $key
     * @return string
     */
    protected function _getYtIdeTagRow($getVideo, $key){
        $keyStringRow   = "";
        $ytApiRow       = is_array($getVideo) ? @$getVideo['youtube_api'] : @$getVideo->youtube_api;
        $ytApiArray     = json_decode($ytApiRow, true);
        if(!is_null($ytApiArray)) {
            $keyStringRow   = array_key_exists("items", $ytApiArray) ? $ytApiArray['items'][0][$key] : $ytApiArray['videos'][0][$key];
        }
        return $keyStringRow;
    }

    /**
     * @param $currentPage
     * @param $nextPage
     * @return bool
     */
    protected function _checkPagingGotToEnd($currentPage, $nextPage) {
        return $currentPage == $nextPage ? true : false;
    }
}