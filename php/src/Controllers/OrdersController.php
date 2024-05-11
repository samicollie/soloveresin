<?php
namespace App\Controllers;

use Dompdf\Dompdf;
use DateTime;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Addresses;
use App\Controllers\Controller;
use App\Controllers\Traits\Validatortrait;
use App\Models\Users;
use App\Services\EmailService;

class OrdersController extends Controller {
    use Validatortrait;

    /**
     * display choosing addresses formular on get method and process the form submission on post method
     *
     * @return void
     */
    public function chooseAddresses(){
        $this->isAccessAllowed();
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $idUser = $_SESSION['id_user'];
            $addresseModel = new Addresses;
            $addresses = $addresseModel->getAddresses($idUser);
            $tokenCSRF = bin2hex(random_bytes(32));
            $_SESSION['order_token'] = $tokenCSRF;
            $this->render('cart/orderChooseAdresses', "Choix de l'adresse", ['addresses' => $addresses, 'tokenCSRF' => $tokenCSRF]);
        }else if($_SERVER['REQUEST_METHOD'] ==='POST'){
            $fields = $this->cleanFields($_POST);
            $delivery = $fields['delivery'] ?? '';
            $invoice = $fields['invoice'] ?? '';
            $tokenCSRF = $fields['token_csrf'] ?? '';
            if($tokenCSRF !== $_SESSION['order_token']){
                header("location: /cart");
                exit();
            }
            unset($_SESSION['order_token']);
            if(empty($delivery) || empty($invoice)){
                $errorMessage['blank'] = "Vous devez choisir une adresse de livraison et de facturation.";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            $adresseModel = new Addresses;
            $addresses['delivery'] = $adresseModel->getAddress($delivery);
            $addresses['invoice'] = $adresseModel->getAddress($invoice);
            $cartModel = new Cart;
            $idCart = $cartModel->getIdCartFromIdUser($_SESSION['id_user']);
            $cartTemp = $cartModel->getProductsIdFromCart($idCart);
            $productModel = New Products;
            foreach($cartTemp as $cartItem){
                $product = $productModel->getOneProduct($cartItem->id_product);
                $cart[] = [$product, $cartItem->quantity];
            }
            $_SESSION['order_products'] = $cart;
            $_SESSION['order_addresses'] = $addresses;
            $this->sendJSONResponse(['success' => true]);
        }else{
            header("location: /cart");
        }
    }

    /**
     * display order summary formular on get method and process the form submission on post method
     *
     * @return void
     */
    public function orderSummary(){
        $this->isAccessAllowed();
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            if(!isset($_SESSION['order_products']) || !isset($_SESSION['order_addresses'])){
                header("location: /cart");
                exit();
            }
            $cart = $_SESSION['order_products'];
            $addresses = $_SESSION['order_addresses'];
            $tokenCSRF = bin2hex(random_bytes(32));
            $_SESSION['order_token'] = $tokenCSRF;
            $this->render('cart/orderSummary', 'Récapitulatif de commande', ['cart' => $cart, 'addresses' => $addresses, 'tokenCSRF' => $tokenCSRF]);
        }else if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $fields = $this->cleanFields($_POST);
            $tokenCSRF = $fields['token_csrf'] ?? '';
            $price = $fields['price'];
            if($tokenCSRF !== $_SESSION['order_token']){
                header("location: /cart");
            }
            if(!$this->validatePrice($price)){
                header("location: /cart");
                exit();
            }
            $_SESSION['order_price'] = floatval($price);
            unset($_SESSION['order_token']);
            $this->sendJSONResponse(['success' => true]);
        }else{
            header("location: /cart");
        }
    }

    /**
     * display payment formular on get method and process the form submission on post method
     *
     * @return void
     */
    public function orderPayment(){
        $this->isAccessAllowed();
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $this->displayPaymentPage();
        }else if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->processPayment();
            $this->createOrder();
            if(!$_SESSION['order_id']){
                $errorMessage['request'] = "Une erreur s'est produite lors de la création de la commande.";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            $orderNumber = $_SESSION['order_number'];
            //get out all products from the user cart
            $this->updateProductsQuantity();
            $this->deleteAllProductsInUserCart();
            $this->sendConfirmationEmail();
            $this->clearSessionData();
            $this->sendJSONResponse(['success' => true, 'orderNumber' => $orderNumber]);
        }else{
            header("location: /cart");
        }
    }

    /**
     * display a confirmation page of the user order
     *
     * @return void
     */
    public function confirmationOrder(){
        $this->isAccessAllowed();
        $this->render('cart/confirmationSuccessOrder', 'Confirmation de commande');
    }

    /**
     * display the formular of payment
     *
     * @return void
     */
    private function displayPaymentPage(){
        if(!isset($_SESSION['order_products']) || !isset($_SESSION['order_addresses']) || !isset($_SESSION['order_price'])){
            header("location: /cart");
        }
        $tokenCSRF = bin2hex(random_bytes(32));
        $_SESSION['order_token'] = $tokenCSRF;
        $this->render('cart/orderPayment', 'Paiement', ['tokenCSRF' => $tokenCSRF]);
    }

    /**
     * make the process payement
     *
     * @return void
     */
    private function processPayment(){
        $fields = $this->cleanFields($_POST);
            $errorMessage = $this->validateFields($fields);
            $tokenCSRF = $fields['token_csrf'] ?? '';
            $cardNumber = $fields['card-number'] ?? '';
            $lastname = $fields['lastname'] ?? '';
            $expirationDate = $fields['expiration-date'] ?? '';
            $cvv = $fields['cvv'] ?? '';
            if($tokenCSRF !== $_SESSION['order_token']){
                header("location: /cart");
                exit();
            }
            if(empty($cardNumber) || empty($lastname) || empty($expirationDate) || empty($cvv)){
                $errorMessage['blank'] = "Tous les champs doivent être complétés.";
            }
            if(!empty($errorMessage)){
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            
    }

    /**
     * create an order which an user valid
     *
     * @return void
     */
    private function createOrder(){
        date_default_timezone_set('Europe/Paris');
        $date = (new DateTime())->format('Y-m-d H:i:s');
        $orderNumber='ORD-' . (new DateTime())->format('YmdHis') . '-' . $_SESSION['id_user'];
        $_SESSION['order_number'] = $orderNumber;
        $deliveryAddress = $_SESSION['order_addresses']['delivery'];
        $invoiceAddress = $_SESSION['order_addresses']['invoice'];
        $orderDeliveryAddress = $deliveryAddress->lastname . ' ' . $deliveryAddress->firstname . "\n" . 
        $deliveryAddress->street_number . ' ' . $deliveryAddress->street_name . "\n" . 
        $deliveryAddress->zipcode . ' ' . $deliveryAddress->city ;
        $orderInvoiceAddress = $invoiceAddress->lastname . ' ' . $invoiceAddress->firstname . "\n" . 
        $deliveryAddress->street_number . ' ' . $deliveryAddress->street_name . "\n" . 
        $invoiceAddress->zipcode . ' ' . $invoiceAddress->city ;
        $orderModel = new Orders;
        $idOrder = $orderModel->createOrder($orderNumber, $date, $orderDeliveryAddress, $orderInvoiceAddress,
        $_SESSION['order_price'], $_SESSION['id_user']);
        if($idOrder){
            $_SESSION['order_id'] = $idOrder;
            foreach($_SESSION['order_products'] as $product){
                $orderModel->addProductInOrder($product[0]->id_product, $product[1], $idOrder);
            }
        }
    }

    /**
     * clear the cart of an user after an order
     *
     * @return void
     */
    private function deleteAllProductsInUserCart(){
        $cartModel = new Cart;
        $idCart = $cartModel->getIdCartFromIdUser($_SESSION['id_user']);
        $cartModel->deleteAllProductsInCart($idCart);
    }

    /**
     * send a mail of confirmation after an user order
     *
     * @return void
     */
    private function sendConfirmationEmail(){
        ob_start();
        require_once ROOT . '/src/Views/cart/invoiceModel.php';
        $invoiceHtml = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($invoiceHtml);
        $dompdf->render();
        $invoicePdf = $dompdf->output();
        EmailService::confirmationOrderEmail($_SESSION['id_user'], $orderNumber, $invoicePdf);
    }

    /**
     * clear all data save in the session from an order
     *
     * @return void
     */
    private function clearSessionData(){
        unset($_SESSION['order_token']);
        unset($_SESSION['order_products']);
        unset($_SESSION['order_addresses']);
        unset($_SESSION['cart']);
        unset($_SESSION['order_id']);
        unset($_SESSION['order_number']);
    }

    /**
     * update quantity product from order
     *
     * @return void
     */
    private function updateProductsQuantity(){
        $idUser = $_SESSION['id_user'];
        $cartModel = new Cart;
        $idCart = $cartModel->getIdCartFromIdUser($idUser);
        $cart = $cartModel->getProductsIdFromCart($idCart);
        $productModel = new Products;
        foreach($cart as $item){
            $productModel->updateProductQuantity($item->id_product, $item->quantity);
        }
    }
}