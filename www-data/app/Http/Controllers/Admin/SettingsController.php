<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Front\Controller;
use App\Models\Setting;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class SettingsController extends Controller
{
    public function view()
    {
        return view('settings');
    }

    public function store(Request $request)
    {
        if (!PermissionService::allowed(
            $this->getVisitor()->id,
            PermissionService::O_SETTING, PermissionService::A_CHANGE
        )
        ) {
            throw new AccessDeniedHttpException();
        }
        try {
            foreach (Setting::all() as $key => $item) {

                $old_value = $item->value;
                $new_value = null;

                if ($request->has($item->key)) {
                    $new_value = $request->input($item->key);
                }

                if ($item->key == 'timezone'){
                    $new_value = (int)$new_value;
                    if (abs($new_value) >20){
                        return redirect()->back()->with('message', 'An error occurred');
                    }
                }

                if (null === $new_value){
                    $new_value = '';
                }
                $item->value = $new_value;
                $item->save();

                if ($old_value !== $new_value) {
                    auth()->user()->createLog($item, 'Edit');
                }
            }
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            return redirect()->back()->with('message', 'An error occurred'. $e->getMessage());
        }

        return redirect()->back()->with('message', 'Successfully saved!');
    }
}
