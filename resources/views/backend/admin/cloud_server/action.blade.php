<ul class="d-flex gap-30 justify-content-end align-items-center">
	@can('cloud_server.edit')
		<li>
			<a href="{{ route('cloud-server.edit', @$server->id) }}"
			   data-bs-toggle="tooltip"
			   title="{{ __('edit') }}"><i class="las la-edit"></i></a>
		</li>
	@endcan
	@if(hasPermission('cloud_server.destroy'))
		<li>
			<a href="javascript:void(0)"
			   onclick="delete_row('{{ route('cloud-server.destroy', @$server->id) }}')"
			   data-bs-toggle="tooltip"
			   title="{{ __('delete') }}"><i class="las la-trash-alt"></i></a>
		</li>
	@endif
</ul>



