<?php

/**
 * Created By: Henry Ejemuta
 * Project: laravel-monnify
 * Class Name: Monnify.php
 * Date Created: 7/13/20
 * Time Created: 8:44 PM
 */

namespace App\Facades;

use App\Classes\Banks;
use App\Classes\CustomerReservedAccount;
use App\Classes\Disbursements;
use App\Classes\SubAccounts;
use App\Classes\Transactions;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string computeRequestValidationHashTest(string $stringifiedData)
 * @method static string computeRequestValidationHash(string $stringifiedData)
 * @method static Banks Banks()
 * @method static CustomerReservedAccount ReservedAccounts()
 * @method static Disbursements Disbursements()
 * @method static SubAccounts SubAccounts()
 * @method static Transactions Transactions()
 *
 * Class Monnify
 * @package HenryEjemuta\LaravelMonnify\Facades
 */
class Monnify extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'monnify';
    }
}