<?php declare(strict_types = 1);

use RedsysRest\Common\Currency;
use RedsysRest\Configurator;
use RedsysRest\Orders\Refund;
use RedsysRest\RequestBuilder;

$secret = getenv('REDSYS_SECRET') ?: 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
$terminal = getenv('REDSYS_TERMINAL');
$merchant = getenv('REDSYS_MERCHANT_CODE');;

if (!$terminal || !$merchant) {
    echo 'Missing environment variable either REDSYS_TERMINAL or REDSYS_MERCHANT_CODE' . PHP_EOL;
    exit(1);
}

$order = $argv[1] ?? false;
$amount = $argv[2] ?? false;

if (!$order || !$order) {
    echo 'An payment order and the amount you want to return is required.' . PHP_EOL;
    echo 'Usage example: php ' . $argv[0] . ' 000000000001 100' . PHP_EOL;
    exit(1);
}

require_once 'vendor/autoload.php';
$config = new Configurator(
    $secret,
    Currency::eur(),
    $merchant,
    $terminal,
    Configurator::ENV_TEST
);
$redsys = new Client(new Client, new RequestBuilder, $config);

$order = new Refund((string)$amount, (string)$order);

$redsys->execute($order);
