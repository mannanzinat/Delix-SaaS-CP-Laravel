<?php

namespace App\Imports;

use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelEvent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class ParcelsImport implements  ToCollection, WithHeadingRow, WithChunkReading, SkipsEmptyRows, SkipsOnError, WithValidation
{
    use Importable, SkipsErrors;

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
        $user = jwtUser() ?? Sentinel::getUser();

        foreach ($rows as $row){
            if ($user->user_type == 'merchant'):
                $merchant = $user->merchant;
            elseif ( $user->user_type == 'merchant_staff'):
                $merchant = $user->staffMerchant;
            else:
                $merchant = Merchant::find($row['merchant']);
            endif;
            $fragile_charge = number_format(0, 2);
            $fragile        = 0;
            $packaging_charge = number_format(0, 2);
            $packaging        = 'no';
            if(isset($row['fragile']) && $row['fragile'] == 1){
                $fragile        = 1;
                $fragile_charge = settingHelper('fragile_charge');

                if(isset($row['packaging']) && $row['packaging'] != 'no'){
                    $packaging        = $row['packaging'];
                    $packaging_charge = settingHelper('package_and_charges')->where('id',$row['packaging'])->first()->charge;
                }
            }
            $available_parcel_types = array();
            if ($user->user_type == 'merchant' || $user->user_type == 'merchant_staff'):
                if (settingHelper('preferences')->where('title','same_day')->first()->merchant):
                    array_push($available_parcel_types, 'same_day');
                endif;
                if (settingHelper('preferences')->where('title','next_day')->first()->merchant):
                    array_push($available_parcel_types, 'next_day');
                endif;
                if (settingHelper('preferences')->where('title','sub_city')->first()->merchant):
                    array_push($available_parcel_types, 'sub_city');
                endif;
                if (settingHelper('preferences')->where('title','sub_urban_area')->first()->merchant):
                    array_push($available_parcel_types, 'sub_urban_area');
                endif;
            else:
                if (settingHelper('preferences')->where('title','same_day')->first()->staff):
                    array_push($available_parcel_types, 'same_day');
                endif;
                if (settingHelper('preferences')->where('title','next_day')->first()->staff):
                    array_push($available_parcel_types, 'next_day');
                endif;
                if (settingHelper('preferences')->where('title','sub_city')->first()->staff):
                    array_push($available_parcel_types, 'sub_city');
                endif;
                if (settingHelper('preferences')->where('title','sub_urban_area')->first()->staff):
                    array_push($available_parcel_types, 'sub_urban_area');
                endif;
            endif;
            $parcel_type = $row['parcel_type'] ?? 'next_day';
            if (!in_array($parcel_type, $available_parcel_types)):
                continue;
            endif;
            if($parcel_type == "same_day" || $parcel_type == "next_day" || $parcel_type == "frozen"):
                $location            = 'dhaka';
            elseif($parcel_type == "sub_city"):
                $location            = 'sub_city';
            elseif($parcel_type == "sub_urban_area"):
                $location            = 'sub_urban_area';
            elseif($parcel_type == "third_party_booking"):
                $location            = 'third_party_booking';
            endif;
            $weight = $row['weight'] ?? 1;
            $charge                = data_get($merchant->charges, $weight.'.'.$parcel_type);
            $cod_charge            = data_get($merchant->cod_charges, $location);
            $vat                   = $merchant->vat ?? 0.00;
            $total_delivery_charge = $charge + $packaging_charge + $fragile_charge + ($row['price'] / 100 * $cod_charge);
            $total_delivery_charge += $total_delivery_charge / 100 * $vat;
            $payable               = $row['price'] - $total_delivery_charge;
            if($parcel_type == 'frozen'){
                $pickup_date   = date('Y-m-d');
                $pickup_time   = date('h:i:s');
                $delivery_date = date("Y-m-d", strtotime('+2 hours', strtotime($pickup_date)));
                $delivery_time = date("h:i:s", strtotime('+2 hours', strtotime($pickup_time)));
            }elseif($parcel_type == 'same_day'){
                if(date('H') >= settingHelper('pickup_accept_start') && date('H') <= settingHelper('pickup_accept_end')){
                    $pickup_date   = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
                    $delivery_date = date("Y-m-d", strtotime('+1 days', strtotime(date('Y-m-d'))));
                }else{
                    $pickup_date   = date('Y-m-d');
                    $delivery_date = date("Y-m-d");
                }
            }elseif($parcel_type == 'sub_urban_area'){
                if(date('H') >= settingHelper('pickup_accept_start') && date('H') <= settingHelper('pickup_accept_end')){
                    $days = settingHelper('outside_dhaka_days') + 1;
                    $pickup_date   = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
                    $delivery_date = date("Y-m-d", strtotime('+'.$days.' days', strtotime(date('Y-m-d'))));
                }else{
                    $days = settingHelper('outside_dhaka_days');
                    $pickup_date   = date('Y-m-d');
                    $delivery_date = date("Y-m-d", strtotime('+'.$days.' days', strtotime(date('Y-m-d'))));
                }
            }else{
                if(date('H') > settingHelper('pickup_accept_start') && date('H') <= settingHelper('pickup_accept_end')){
                    $pickup_date   = date('Y-m-d');
                    $delivery_date = date("Y-m-d", strtotime('+2 days', strtotime(date('Y-m-d'))));
                }else{
                    $pickup_date   = date('Y-m-d');
                    $delivery_date = date("Y-m-d", strtotime('+1 days', strtotime(date('Y-m-d'))));
                }
            }

            $pickup_shop_phone_number = $row['pickup_shop_phone_number'] ?? '';
            $customer_phone_number = $row['customer_phone_number'] ?? '';
            if ($pickup_shop_phone_number != ''):
                $pickup_number = preg_replace('/^(\+880|880|0)/', '', $pickup_shop_phone_number);
                $pickup_number = preg_replace('/-/', '', $pickup_number);
                $row['pickup_shop_phone_number'] = '0'.$pickup_number;
            endif;
            if ($customer_phone_number):
                $pickup_number = preg_replace('/^(\+880|880|0)/', '', $customer_phone_number);
                $pickup_number = preg_replace('/-/', '', $pickup_number);
                $row['customer_phone_number'] = '0'.$pickup_number;
            endif;
            $parcel = Parcel::create([
                'parcel_no'           => make_unique_parcel_id(),
                'merchant_id'         => $merchant->id,
                'price'               => $row['price'],
                'selling_price'       => $row['selling_price'],
                'customer_name'       => $row['customer_name'],
                'customer_invoice_no' => $row['customer_invoice_no'],
                'customer_phone_number'  => $row['customer_phone_number'],
                'customer_address'    => $row['customer_address'],
                'note'                => $row['note'] ?? '',

                // Charge
                'packaging'            => $packaging,
                'packaging_charge'     => $packaging_charge,
                'fragile'              => $fragile,
                'fragile_charge'       => $fragile_charge,

                'weight'               => $weight,
                'parcel_type'          => $parcel_type,
                'charge'               => $charge,
                'cod_charge'           => $cod_charge,
                'vat'                  => $vat,
                'total_delivery_charge' => floor($total_delivery_charge),
                'payable'               => ceil($payable),
                'location'              => $location,
                // End charge

                // pickup shop details
                'pickup_shop_phone_number'  => $row['pickup_shop_phone_number'] ?? $merchant->shops->where('default', true)->first()->shop_phone_number,
                'pickup_address'            => $row['pickup_address'] ?? $merchant->shops->where('default', true)->first()->address,
                'pickup_branch_id'             => $row['pickup_branch'] ?? ($merchant->shops->pickup_branch_id !='' ?$merchant->shops->pickup_branch_id : null),
                'shop_id'                   => $merchant->shops->where('default', true)->first()->id,
                'pickup_date'               => $pickup_date,
                'date'                      => date('Y-m-d'),
                'pickup_time'               => $pickup_time ?? '',
                'delivery_date'             => $delivery_date ?? '',
                'delivery_time'             => $delivery_time ?? '',
                'user_id'                   => $user->id
            ]);
           ParcelEvent::create([
            'parcel_id'             => $parcel->id,
            'user_id'               => $user->id,
            'title'                 => 'parcel_create_event',
            ]);
        }
        DB::commit();
    } catch (\Exception $e) {

        dd($e->getMessage());
        // Handle the exception here
        Log::error($e->getMessage());
        // Rollback the transaction
        DB::rollBack();
        // Optionally, you can rethrow the exception if needed
        // throw $e;
    }
    }

    public function rules(): array
    {
        $user = jwtUser() ?? Sentinel::getUser();

        if ($user->user_type == 'merchant' || $user->user_type == 'merchant_staff'):
            return [
                '*.price'           => 'required|numeric',
                '*.selling_price'   => 'required|numeric',
                '*.parcel_type'     => 'string|nullable',
                '*.customer_invoice_no' => 'required',
                '*.customer_phone_number' => 'required',
                '*.customer_address'    => 'required|string',
            ];
        else:
            return [
                '*.merchant'        => 'required',
                '*.price'           => 'required|numeric',
                '*.selling_price'   => 'required|numeric',
                '*.parcel_type'     => 'string|nullable',
                '*.customer_invoice_no' => 'required',
                '*.customer_phone_number' => 'required',
                '*.customer_address'    => 'required|string',
            ];
        endif;

    }

    public function chunkSize(): int
    {
        return 500;
    }
}
