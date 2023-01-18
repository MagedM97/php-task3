<?php
function addProductToCart($product)
{
    if (session_status() === PHP_SESSION_NONE)
        session_start();

    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $found = false;
    for ($i = 0; $i < count($cart); $i++) {
        if ($cart[$i]['product']['id'] === $product['id']) {
            if($product[0]=='plus')
            $cart[$i]['quantity'] += 1;
            else if ($product[0]=='minus'&&$cart[$i]['quantity']>1)
            $cart[$i]['quantity'] -= 1;
            else if ($product[0]=='remove')
            array_splice($cart, $i, 1);
            else if($cart[$i]['product']['id'] === $product['id']&&!$product[0]=='minus'&&!$product[0]=='minus')
            $cart[$i]['quantity'] += 1;
            $found = true;
        }
    }
    if (!$found) {
        array_push($cart, ['product' => $product, 'quantity' => 1]);
    }
    $_SESSION['cart'] = $cart;
}


function getCart()
{
    if (!headers_sent()&& session_status() === PHP_SESSION_NONE)
        session_start();

    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    
    return $cart;

}
function get_shipping()
{
    $cart=getCart();
    return count($cart) * 10;  
}



function sub_total()
{
    $sum = 0;
    $cart = getCart();
    foreach ($cart as $line) {
        $sum += ($line['product']['price']-($line['product']['price']*$line['product']['discount']))*$line['quantity'];
        
    }
    return $sum;
}
function total()
{
    return sub_total() + get_shipping();
}

function line_total($line){
    return ($line['product']['price'] - $line['product']['price'] * $line['product']['discount']) * $line['quantity'];
}