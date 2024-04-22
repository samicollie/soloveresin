<?php 

namespace App\Models;

use DateTime;

class LegalNotices extends Model{

    public function __construct(){

    } 

    /**
     * get the terms of sale
     *
     * @return object
     */
    public function getTermsOfSale():object
    {
        $sql= "SELECT * FROM Terms_Of_Sale WHERE terms_id = 1";
        return $this->request($sql)->fetchObject();
    }

    /**
     * update the terms of sale
     *
     * @param integer $termsId
     * @param string $sailorId
     * @param string $productsAndPrices
     * @param string $commandProcess
     * @param string $payement
     * @param string $delivery
     * @param string $rightOfCancellation
     * @param string $disputes
     * @param string $modification
     * @return boolean
     */
    public function updateTermsOfSale(int $termsId, string $sailorId, string $productsAndPrices, string $commandProcess, 
    string $payement, string $delivery, string $rightOfCancellation, string $disputes, string $modification): bool
    {
        date_default_timezone_set('Europe/Paris');
        $date = new DateTime();
        $lastAction = $date->format('Y-m-d H:i:s');
        $sql = "UPDATE Terms_Of_Sale SET
            sailor_id = ?,
            products_and_prices = ?,
            command_process = ?,
            payement = ?,
            delivery = ?,
            right_of_cancellation = ?,
            disputes = ?,
            modification = ?,
            updated_at = ?
            WHERE terms_id = ?";
            if($this->request($sql, [$sailorId, $productsAndPrices, $commandProcess, $payement, $delivery, $rightOfCancellation, $disputes, $modification, $lastAction, $termsId])){
                return true;
            }else{
                return false;
            }
    }

    /**
     * get the privacy policy
     *
     * @return object
     */
    public function getPrivacyPolicy(): object
    {
        $sql = "SELECT * FROM Privacy_Policy WHERE policy_id = 1";
        return $this->request($sql)->fetchObject();
    }

    /**
     * update the privacy policy
     *
     * @param integer $policyId
     * @param string $collection
     * @param string $usage
     * @param string $conservation
     * @param string $security
     * @param string $cookie
     * @param string $usersRights
     * @param string $disputes
     * @param string $contact
     * @return boolean
     */
    public function updatePrivacyPolicy(int $policyId, string $collection, string $usage, string $conservation,
    string $security, string $cookie, string $usersRights, string $disputes, string $contact): bool
    {
        date_default_timezone_set('Europe/Paris');
        $date = new DateTime();
        $lastAction = $date->format('Y-m-d H:i:s');
        $sql = "UPDATE Privacy_Policy SET
            collection = ?,
            usage_data = ?,
            conservation = ?,
            security_data = ?,
            cookie = ?,
            users_rights = ?,
            disputes = ?,
            contact = ?,
            updated_at = ?
            WHERE policy_id = ?";
            if($this->request($sql, [$collection, $usage, $conservation, $security, $cookie, $usersRights, $disputes, $contact, $lastAction, $policyId])){
                return true;
            }else{
                return false;
            }
    }

    /**
     * get the usage of cookies
     *
     * @return object
     */
    public function getUsageCookie(): object
    {
        $sql = "SELECT * FROM Usage_Cookie WHERE usage_id = 1";
        return $this->request($sql)->fetchObject();
    }

    public function updateUsageCookie(int $usageId, string $usageContent): bool
    {
        date_default_timezone_set('Europe/Paris');
        $date = new DateTime();
        $lastAction = $date->format('Y-m-d H:i:s');
        $sql = "UPDATE Usage_Cookie SET content = ? WHERE usage_id = ?";
        if($this->request($sql, [$usageContent, $usageId])){
            return true;
        }else{
            return false;
        }
    }
}