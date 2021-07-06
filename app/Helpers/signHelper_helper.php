<?php

function signHelper($currency):string
{
    switch ($currency)
    {
        case 'NGN':
            return '₦';

        case 'GBP':
            return '£';

        case 'EUR':
            return '€';

        case 'USD':
            return '$';
    }
}