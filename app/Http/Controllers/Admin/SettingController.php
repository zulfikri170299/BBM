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
        $request->validate([
            'mobile_hero_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'login_background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192'
        ], [
            'mobile_hero_image.image' => 'File harus berupa gambar.',
            'mobile_hero_image.mimes' => 'Format gambar harus JPEG, PNG, JPG, WEBP, atau SVG.',
            'mobile_hero_image.max' => 'Ukuran gambar maksimal adalah 4MB.',
            'login_background_image.image' => 'File latar belakang harus berupa gambar.',
            'login_background_image.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau WEBP.',
            'login_background_image.max' => 'Ukuran gambar latar belakang maksimal adalah 8MB.'
        ]);

        // Handle upload mobile hero image (mendukung file langsung maupun hasil circular cropper base64)
        if ($request->filled('mobile_hero_image_base64') && str_starts_with($request->mobile_hero_image_base64, 'data:image')) {
            $base64Image = $request->mobile_hero_image_base64;
            $image_parts = explode(";base64,", $base64Image);
            if (isset($image_parts[1])) {
                $image_base64 = base64_decode($image_parts[1]);
                $filename = 'hero_' . time() . '_' . uniqid() . '.png';
                $destination = public_path('uploads/hero');
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }

                $oldImage = Setting::where('key', 'mobile_hero_image')->value('value');
                if ($oldImage && file_exists(public_path($oldImage))) {
                    @unlink(public_path($oldImage));
                }

                file_put_contents($destination . '/' . $filename, $image_base64);
                Setting::updateOrCreate(['key' => 'mobile_hero_image'], ['value' => 'uploads/hero/' . $filename]);
            }
        } elseif ($request->hasFile('mobile_hero_image')) {
            $file = $request->file('mobile_hero_image');
            $filename = 'hero_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/hero');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            
            // Hapus gambar lama jika ada
            $oldImage = Setting::where('key', 'mobile_hero_image')->value('value');
            if ($oldImage && file_exists(public_path($oldImage))) {
                @unlink(public_path($oldImage));
            }

            $file->move($destination, $filename);
            Setting::updateOrCreate(['key' => 'mobile_hero_image'], ['value' => 'uploads/hero/' . $filename]);
        } elseif ($request->has('remove_mobile_hero_image') && $request->remove_mobile_hero_image == '1') {
            $oldImage = Setting::where('key', 'mobile_hero_image')->value('value');
            if ($oldImage && file_exists(public_path($oldImage))) {
                @unlink(public_path($oldImage));
            }
            Setting::updateOrCreate(['key' => 'mobile_hero_image'], ['value' => '']);
        }

        // Handle upload login background image (gambar gedung latar belakang desktop login - mendukung base64 dari crop 16:9 maupun file langsung)
        if ($request->filled('login_background_image_base64') && str_starts_with($request->login_background_image_base64, 'data:image')) {
            $base64Image = $request->login_background_image_base64;
            $image_parts = explode(";base64,", $base64Image);
            if (isset($image_parts[1])) {
                $image_base64 = base64_decode($image_parts[1]);
                $filename = 'bg_' . time() . '_' . uniqid() . '.jpg';
                $destination = public_path('uploads/backgrounds');
                if (!file_exists($destination)) {
                    mkdir($destination, 0755, true);
                }

                $oldBg = Setting::where('key', 'login_background_image')->value('value');
                if ($oldBg && file_exists(public_path($oldBg))) {
                    @unlink(public_path($oldBg));
                }

                file_put_contents($destination . '/' . $filename, $image_base64);
                Setting::updateOrCreate(['key' => 'login_background_image'], ['value' => 'uploads/backgrounds/' . $filename]);
            }
        } elseif ($request->hasFile('login_background_image')) {
            $file = $request->file('login_background_image');
            $filename = 'bg_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/backgrounds');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            
            // Hapus gambar latar lama jika ada
            $oldBg = Setting::where('key', 'login_background_image')->value('value');
            if ($oldBg && file_exists(public_path($oldBg))) {
                @unlink(public_path($oldBg));
            }

            $file->move($destination, $filename);
            Setting::updateOrCreate(['key' => 'login_background_image'], ['value' => 'uploads/backgrounds/' . $filename]);
        } elseif ($request->has('remove_login_background_image') && $request->remove_login_background_image == '1') {
            $oldBg = Setting::where('key', 'login_background_image')->value('value');
            if ($oldBg && file_exists(public_path($oldBg))) {
                @unlink(public_path($oldBg));
            }
            Setting::updateOrCreate(['key' => 'login_background_image'], ['value' => '']);
        }

        $data = $request->except([
            '_token', 
            'mobile_hero_image', 
            'mobile_hero_image_base64', 
            'remove_mobile_hero_image',
            'login_background_image',
            'login_background_image_base64',
            'remove_login_background_image'
        ]);

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
            'is_satker_enabled'
        ];
        // Pastikan modul personel selalu nonaktif
        Setting::updateOrCreate(['key' => 'personel_access_control'], ['value' => '0']);
        Setting::updateOrCreate(['key' => 'is_personel_enabled'], ['value' => '0']);
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


}
