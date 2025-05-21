<style>
    .btn {
        padding: 0.4rem 0.8rem;
        border: none;
        border-radius: 4px;
        color: white;
        cursor: pointer;
        font-weight: 600;
        margin-right: 0.5rem;
    }
    .accept-btn {
        background-color: #28a745; /* Green */
    }
    .delete-btn, .disable-btn {
        background-color: #dc3545; /* Red */
    }
    .edit-btn {
        background-color: #007bff; /* Blue */
    }
    .status {
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-weight: 600;
        color: white;
        display: inline-block;
    }
    .status.accepted {
        background-color: #28a745; /* Green */
    }
    .status.pending {
        background-color: #007bff; /* Blue */
    }
</style>

<!-- Replace the action buttons and status display in your tenants table with the following -->

<td>
    <span class="status {{ strtolower($tenant->status) }}">{{ ucfirst($tenant->status) }}</span>
</td>
<td>
    @if($tenant->status === 'pending')
        <form method="POST" action="{{ route('admin.tenants.accept', $tenant->tenant_name) }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn accept-btn">Accept</button>
        </form>
        <form method="POST" action="{{ route('admin.tenants.delete', $tenant->tenant_name) }}" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this tenant?')">Delete</button>
        </form>
    @elseif($tenant->status === 'accepted')
        <form method="POST" action="{{ route('admin.tenants.revert', $tenant->tenant_name) }}" style="display:inline;">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn disable-btn">Disable</button>
        </form>
    @endif
    <a href="{{ route('admin.tenants.edit', $tenant->tenant_name) }}" class="btn edit-btn">Edit</a>
</td>
<td>
    @if($tenant->status === 'accepted')
        <a href="{{ url('/login', [], false) }}?tenant={{ $tenant->tenant_name }}" target="_blank" class="button-link">Tenant Login</a>
    @else
        <span style="color: grey;">Not available</span>
    @endif
</td>
