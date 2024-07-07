<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\BranchSeeder;
use Database\Seeders\ChargeSeeder;
use Database\Seeders\NoticeSeeder;
use Database\Seeders\ParcelSeeder;
use Database\Seeders\DistrictZilla;
use Database\Seeders\AccountsSeeder;
use Database\Seeders\CodChargeSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\DeliveryMenSeeder;
use Database\Seeders\ParcelEventSeeder;
use Database\Seeders\SmsTemplateSeeder;
use Database\Seeders\WithdrawSmsSeeder;
use Database\Seeders\SettingsTableSeeder;
use Database\Seeders\MerchantsTableSeeder;
use Database\Seeders\PaymentMethodsSeeder;
use Database\Seeders\PreferenceTableSeeder;
use Database\Seeders\MerchantAccountsSeeder;
use Database\Seeders\CustomerParcelSmsSeeder;
use Database\Seeders\DeliveryManAccountsSeeder;
use Database\Seeders\PackagingAndChargesSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call(UserSeeder::class);
    }
}
