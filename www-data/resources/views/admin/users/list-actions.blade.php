<div class="btn-group-vertical btn-group-sm">
    @allowed(App\Services\PermissionService::O_USER, App\Services\PermissionService::A_CHANGE)
        <a href="{{route('admin.' . $route . '.view', $object)}}" class="btn"><i class="bx bx-edit"></i></a>
    @endallowed
    @allowed(App\Services\PermissionService::O_USER, App\Services\PermissionService::A_DELETE)
        <a href="{{route('admin.' . $route . '.delete', $object)}}" onclick="return confirm('You a sure?');" class="btn"><i class="bx bx-trash"></i></a>
    @endallowed
</div>


