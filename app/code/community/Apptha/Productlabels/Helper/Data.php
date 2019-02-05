<?php
/**
 * Apptha
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.apptha.com/LICENSE.txt
 *
 * ==============================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * ==============================================================
 * This package designed for Magento COMMUNITY edition
 * Apptha does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Apptha does not provide extension support in case of
 * incorrect edition usage.
 * ==============================================================
 *
 * @category    Apptha
 * @package     Apptha_ProductLabels
 * @version     1.0.0
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2014 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 *
 */

class Apptha_Productlabels_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Upload an image based on the $fileKey
     *
     * @param string $fileKey
     * @param string|null $filename - set a custom filename
     * @return null|string - returns saved filename
     */
    public function uploadImage($fileKey, $filename = null)
    {
        try {

            $uploader = new Varien_File_Uploader($fileKey);
            $extendPath = 'apptha'.'/'.'productlabels'.'/';
            $mediapath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA).DS.$extendPath;
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'))
            ->setAllowRenameFiles(false)
            ->setFilesDispersion(false);
            $file = $uploader->save($mediapath,time().'.'.$uploader->getFileExtension());
            $basePath = $mediapath.DS.$file['file'];
            $imageObj = new Varien_Image($basePath);
            $imageObj->constrainOnly(TRUE);
            $imageObj->keepAspectRatio(FALSE);
            $imageObj->keepFrame(FALSE);
            $imageObj->keepTransparency(TRUE);
            $imageObj->resize(50,50);
            $imageObj->save($basePath);
            return $extendPath.$file['file'];
        } catch (Exception $e) {
            if ($e->getCode() != Varien_File_Uploader::TMP_NAME_EMPTY) {
                throw $e;
            }
        }

        return null;
    }
    /**
     * Function to get the domain key
     *
     * Return domain key
     * @return string
     */

    public function domainKey($tkey)
    {
        $message = "EM-PLMP0EFIL9XEV8YZAL7KCIUQ6NI5OREH4TSEB3TSRIF2SI1ROTAIDALG-JW";
        $stringLength = strlen($tkey);
        for($i = 0; $i < $stringLength; $i++) {
            $keyArray[] = $tkey[$i];
        }
        $encMessage = "";
        $kPos = 0;
        $charsStr = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
        $strLen = strlen($charsStr);
        for($i = 0; $i < $strLen; $i++) {
            $charsArray[] = $charsStr[$i];
        }
        $lenMessage = strlen($message);
        $count = count($keyArray);
        for($i = 0; $i < $lenMessage; $i++) {
            $char   = substr($message, $i, 1);
            $offset = $this->getOffset($keyArray[$kPos], $char);
            $encMessage .= $charsArray[$offset];
            $kPos++;

            if ($kPos >= $count) {
                $kPos = 0;
            }
        }
        return $encMessage;
    }
    /**
     * Function to get the offset for license key
     *
     * Return offset key
     * @return string
     */

    public function getOffset($start, $end)
    {
        $charsStr = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
        $strLen = strlen($charsStr);
        for ($i = 0; $i < $strLen; $i++) {
            $charsArray[] = $charsStr[$i];
        }
        for ($i = count($charsArray) - 1; $i >= 0; $i--) {
            $lookupObj[ord($charsArray[$i])] = $i;
        }
        $sNum   = $lookupObj[ord($start)];
        $eNum   = $lookupObj[ord($end)];
        $offset = $eNum - $sNum;
        if ($offset < 0) {
            $offset = count($charsArray) + ($offset);
        }
        return $offset;
    }
    /**
     * Function to generate license key
     *
     * Return license key
     * @return string
     */

    public function genenrateOscdomain()
    {
        $subfolder = $matches = '';
        $strDomainName = Mage::app()->getFrontController()->getRequest()->getHttpHost();
        preg_match("/^(http:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
        preg_match("/^(https:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
        preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $subfolder[2], $matches);
        if (isset($matches['domain']))
        {
            $customerurl = $matches['domain'];
        } else {
            $customerurl = "";
        }
        $customerurl = str_replace("www.", "", $customerurl);
        $customerurl = str_replace(".", "D", $customerurl);
        $customerurl = strtoupper($customerurl);
        if (isset($matches['domain']))
        {
            $response = $this->domainKey($customerurl);
        } else {
            $response = "";
        }
        return $response;
    }
}