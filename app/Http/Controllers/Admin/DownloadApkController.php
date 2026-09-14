<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadApkController extends Controller
{
    protected function apkPath(): string
    {
        return public_path('apk/posal.apk');
    }

    public function index(): View
    {
        $path = $this->apkPath();
        $apk = [
            'name' => 'posal.apk',
            'size' => file_exists($path) ? round(filesize($path) / 1048576, 2) : 0,
        ];

        return view('admin.download.index', compact('apk'));
    }

    public function download(): BinaryFileResponse
    {
        $path = $this->apkPath();

        abort_unless(file_exists($path), 404, 'File APK tidak ditemukan.');

        return response()->download($path, 'posal.apk');
    }
}
