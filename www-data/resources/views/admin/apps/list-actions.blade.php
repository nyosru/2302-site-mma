<div class="btn-group-vertical btn-group-sm">
    @allowed(App\Services\PermissionService::O_APPLICATION, App\Services\PermissionService::A_CHANGE)
        <a href="{{route('admin.' . $route . '.view', $object)}}" class="btn"><i class="bx bx-edit"></i></a>
    @endallowed
</div>


