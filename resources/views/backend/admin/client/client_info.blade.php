<div>
    <span class="bold">{{ $client->company_name }}</span><br>
    <span>{{ isDemoMode() ? '****@****.***' : @$client->user->email }}</span><br>
    <span>{{ isDemoMode() ? '+***********' : countryCode(@$client->user->phone_country_id).@$client->user->phone }}</span><br>

</div>