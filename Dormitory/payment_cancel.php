<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php'; requireLogin(); redirectWith('/Dormitory/tenant/pay_bill.php','error','Payment was cancelled.');