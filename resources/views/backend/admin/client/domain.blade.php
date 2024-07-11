@if(@$client->domains)
<td>
    <a href="https://{{ @$client->domains->sub_domain }}.delix.cloud" target="_blank">{{ 'https://'.@$client->domains->sub_domain.'.delix.cloud'}}</a>
</td>
@endif
