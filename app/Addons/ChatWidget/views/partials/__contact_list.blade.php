<table class="table" id="permissions-table">
    <thead>
        <tr>
            <th width="50px">#</th>
            <th>{{ __('name') }}</th>
            <th>{{ __('phone') }}</th>
            <th>{{ __('avatar') }}</th>
            <th>{{ __('message') }}</th>
            <th>{{ __('status') }}</th>
            <th class="text-center">{{ __('action') }}</th>
        </tr>
    </thead>
    <tbody id="sortable-body">
        @foreach ($row->contacts as $key => $contact)
            <tr data-id="{{ $contact->id }}">
                <td width="50px">
                    <button class="btn "><i class="la la-bars text-default default-color" aria-hidden="true"></i></button>
                </td>
                <td> {{ $contact->name }}</td>
                <td>{{ $contact->phone }}</td>
                <td>
                    <img src="{{ getFileLink('40x40', $contact->images) }}"
                        alt="{{ $contact->name }}">
                </td>
                <td>{{ $contact->welcome_message }}</td>     
                <td>
                    <div class="setting-check">
                        <input type="checkbox" class="__js_update_status"
                            {{ $contact->status == 1 ? 'checked' : '' }}
                            data-id="{{ $contact->id }}"
                            data-url="{{route('client.chatwidget.contact.status-update',$contact->id)}}"
                            value="0"
                            id="customSwitch2-{{ $contact->id }}">
                        <label
                            for="customSwitch2-{{ $contact->id }}"></label>
                    </div>
                </td>
                <td>
                    <ul class="d-flex gap-30 justify-content-center">
                        <li>
                            <a href="javascript:void(0)" class="__js_edit"
                            data-url="{{ route('client.chatwidget.contact.edit', $contact->id) }}">
                                <i class="las la-edit"></i>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"
                            class="__js_delete" data-url="{{ route('client.chatwidget.contact.destroy', $contact->id) }}"
                                data-toggle="tooltip"
                                data-original-title="{{ __('delete') }}">
                                <i class="las la-trash-alt"></i>
                            </a>
                        </li>
                    </ul>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>