<?php
declare(strict_types=1);

define('PAYMONGO_SECRET_KEY', getenv('PAYMONGO_SECRET_KEY') ?: '');
define('PAYMONGO_PUBLIC_KEY', getenv('PAYMONGO_PUBLIC_KEY') ?: '');

function paymongoSecretKey(): string
{
	return (string) PAYMONGO_SECRET_KEY;
}
