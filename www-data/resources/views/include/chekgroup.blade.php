@foreach ($items as $id => $t)
    <div class="w-full max-w-xs col-span-1 sm:col-span-1 flex" section-row="{{ $id }}" style="display: flex;align-items: center">

    <label class="col-sm-2 col-form-label font-bold">{{ isset($translateKey) ? __($translateKey.'.'.$id) : $id  }}</label>

        @foreach([\App\Services\PermissionService::A_VIEW,\App\Services\PermissionService::A_CREATE,\App\Services\PermissionService::A_CHANGE,\App\Services\PermissionService::A_DELETE] as  $role)
            @if(in_array($role, array_keys($t)))
<?php
    $title=$role;
    $enabled = $t[$title];
?>
{{--        @foreach ($t as $title => $enabled)--}}
        <div class="text-left flex items-center" style="width: 100px">

            <label for="{{ $id }}-{{ $title }}" class="pl-2">
                <input type="checkbox" {!!  $disabled?'disabled="disabled"':'' !!} data-type="bool" class="checkbox checkbox-sm checkbox-primary {{ $class ?? '' }}" id="{{ $id }}-{{ $title }}" {!! $enabled ? 'checked="checked"':'' !!} value="1" section-row-value="{{ $title }}">
                <span class="chb-fix"></span>{{ isset($translateKey) ? __($translateKey.'.'.$title) : $title }}</label>
        </div>
                @else
                <div class="text-left flex items-center" style="width: 100px">

                    </div>
                @endif

@endforeach
        <div class="divider"></div>
</div>
    <hr>
@endforeach
