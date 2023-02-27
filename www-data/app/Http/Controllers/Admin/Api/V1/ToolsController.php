<?php

namespace App\Http\Controllers\Admin\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Intervention\Image\ImageManagerStatic as Image;
use Throwable;

// TODO нужно добавить этот пакет

class ToolsController extends BaseController
{
    public function upload(Request $request)
    {
        try {
            $request->validate(
                [
                    'file' => 'required|mimes:doc,docx,pdf,txt,pdf,jpg,png,jpeg,gif,image/svg|max:16048',
                ]
            );

            $file = $request->file('file');

            $destinationPath = 'uploads';
            $fileName = md5(rand(0, 99999) . ' - ' . time());
            $ext = $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName . '.' . $ext);

            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])) {
                Image::make($destinationPath . '/' . $fileName . '.' . $ext)->resize(
                    370, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    }
                )->encode('jpg', 95)->save($destinationPath . '/' . $fileName . '-370.' . $ext, 95);

                Image::make($destinationPath . '/' . $fileName . '.' . $ext)->resize(
                    570, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    }
                )->encode('jpg', 95)->save($destinationPath . '/' . $fileName . '-570.' . $ext, 95);

                Image::make($destinationPath . '/' . $fileName . '.' . $ext)->resize(
                    1250, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    }
                )->encode('jpg', 95)->save($destinationPath . '/' . $fileName . '-1250.' . $ext, 95);
            }
        } catch (Throwable $e) {
            Log::error('EXCEPTION: ' . $e->getMessage());
            Response::json(
                array(
                    'message' => 'Failed to upload'
                ), \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST
            );
        }
        return response()->json(
            [
                'id' => rand(0, 9999999),
                'path' => $destinationPath . '/' . $fileName . '.' . $ext,
                'alt' => null,
                'title' => null,
                'link' => null,
            ]
        );
    }
}
