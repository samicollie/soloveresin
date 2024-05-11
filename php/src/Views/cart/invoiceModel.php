<?php 
$cart = $_SESSION['order_products'];
$addresses = $_SESSION['order_addresses'];
$orderNumber = $_SESSION['order_number'];
$idOrder = $_SESSION['order_id'];
date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR');
$date = new DateTime();
$pathPicture = 'https://github.com/samicollie/soloveresin/blob/main/php/public/assets/images/';
$currentDate = $date->format('d/m/Y ');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
    <style>
        .invoice-header{
            display: flex;
            justify-content: space-between;
        }
        table{
            margin-top: 1rem;
            width: 100%;
            border-collapse: collapse;
        }

        td, th{
            border: 1px solid black;
            padding : 0.5rem;
            text-align: center;
        }
        tfoot{
            font-weight: bold;
        }
        .business-address{
            text-align: left;
        }

        .client-address{
            text-align: right;
        }

        .invoice-number{
            text-align: center;
        }

        section{
            margin-top: 3rem;
        }
    </style>
</head>
<body>
        <div class="business-address">
            <p><strong>So Love Resin</strong></p>
            <p> N° SIREN : 648857121</p>
            <p>Le bois piou</p>
            <p>03230 La Chapelle-aux-chasses</p>
            <p>Email : soloveresin@gmail.com</p>
        </div>


    <div class="client-address">
        <h4>Adresse de facturation : </h4>
        <p> <?= ucfirst($addresses['invoice']->firstname) . ' ' . ucfirst($addresses['invoice']->lastname)  ?></p>
        <p><?= $addresses['invoice']->street_number . ' ' . ucfirst($addresses['invoice']->street_name) ?></p>
        <p><?= $addresses['invoice']->zipcode . ' ' . ucfirst($addresses['invoice']->city) ?></p>
    </div>
    <h2 class="invoice-number">Facture N°<?= $idOrder ?></h2>

    <p>Détails depuis la commande n° <?= $orderNumber ?> : </p>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Qté</th>
                <th>Prix Unitaire</th>
                <th>Prix</th>
            </tr>
        </thead>
        <?php $price = 5.30; ?>
        <tbody>
        <?php foreach($cart as $product): ?>
            <tr>
                <td>
                    <?= $product[0]->product_name ?>
                </td>
                <td>
                    <?= $product[1] ?>
                </td>
                <td>
                    <?= $product[0]->product_price . "€" ?>
                </td>
                <td>
                    <?php echo number_format($product[0]->product_price *$product[1], 2, ',', '') . "€"; ?>
                </td>
            </tr>
        <?php $price += $product[0]->product_price * $product[1]; ?>
        <?php endforeach ?>
        
            <tr>
                <td colspan="3">Frais de livraison</td>
                <td> 5,30€</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"> MONTANT TOTAL</td>
                <td><?= number_format($price, 2, ',', '') ?></td>
            </tr>
        </tfoot>
    </table>
    <section>
        <p>Facture émise le <?= $currentDate  ?> à La Chapelle-aux-Chasses. </p>
        <p>Facture payée par carte bancaire le <?= $currentDate ?>.</p>
        
    </section>

</body>
</html>