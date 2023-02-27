<div class="btn-group-vertical btn-group-sm">
    <a href="{{route('admin.' . $route . '.view', $object)}}" class="btn"><i class="bx bx-edit"></i></a>
    <a href="{{route('admin.' . $route . '.restore', $object)}}" class="btn"><i class="bx bx-reset"></i></a>
    <a href="{{route('admin.' . $route . '.delete', $object)}}" onclick="return confirm('You a sure?');" class="btn"><i class="bx bx-trash"></i></a>
</div>


