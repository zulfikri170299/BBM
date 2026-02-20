<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\LogAktivitas;



class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'whatsapp_fetch_token']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        // Handle unchecked checkboxes (they don't send data)
        $checkboxes = [
            'satker_can_create_kendaraan', 
            'satker_can_edit_kendaraan',
            'satker_can_import_kendaraan',
            'is_satker_enabled',
            'is_personel_enabled'
        ];
        foreach ($checkboxes as $key) {
            if (!$request->has($key)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => '0'] // Assuming 0 for false/disabled
                );
            }
        }

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Memperbarui Pengaturan Aplikasi"
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function fetchGroups(Request $request)
    {
        $token = $request->token;
        if (!$token) {
            return response()->json(['status' => false, 'reason' => 'Token required'], 400);
        }

        $waService = new \App\Services\WhatsAppService($token);
        
        // 1. Try to get existing list
        $result = $waService->getGroups();

        // 2. If no data found, try to sync once
        if ($result['status'] && empty($result['data']['data'] ?? [])) {
            $waService->syncGroups();
            // Try fetch again after short delay or just tell user to wait
            $result = $waService->getGroups();
        }

        return response()->json($result);
    }

    public function syncGroups(Request $request)
    {
        $token = $request->token;
        $waService = new \App\Services\WhatsAppService($token);
        $result = $waService->syncGroups();
        return response()->json($result);
    }
}
