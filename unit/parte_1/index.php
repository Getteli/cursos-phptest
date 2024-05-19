<?php

include 'autoloader.php';

$discountCalculator = new DiscountCalculator();
echo $discountCalculator->apply(101) . "\n";