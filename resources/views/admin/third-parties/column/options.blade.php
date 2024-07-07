@if (hasPermission('third_party_update') || hasPermission('third_party_delete'))
    <div class="action-card">
        <ul class="d-flex justify-content-cneter">
            @if (hasPermission('third_party_update'))
                <li><a href="{{ route('admin.third-party.edit', $third_party->id) }}" class="dropdown-item">
                        <i class="las la-edit"></i>
                    </a>
                </li>
            @endif
            @if (hasPermission('third_party_delete'))
                <li><a href="javascript:void(0);" onclick="delete_row('third-party/delete/', {{ $third_party->id }})"
                        id="delete-btn" class="dropdown-item">
                        <i class="las la-trash-alt"></i>
                </li>
            @endif
        </ul>
    </div>
@endif
