<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RendisTemplateExport;
use App\Imports\RendisBbmImport;
use App\Exceptions\ImportConflictException;
use Illuminate\Support\Facades\Storage;

class RendisImportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.rendis.import');
    }

    public function downloadTemplate()
    {
        return Excel::download(new RendisTemplateExport, 'Template_Import_Rendis_BBM.xlsx');
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls',
            'pin' => 'required|string',
        ], [
            'file_excel.required' => 'File Excel harus diunggah.',
            'file_excel.mimes' => 'Format file harus .xlsx atau .xls',
            'pin.required' => 'PIN Top Up wajib diisi.',
        ]);

        if (!\Hash::check($request->pin, auth()->user()->topup_password)) {
            return back()->with('error', 'PIN Top Up salah.');
        }

        try {
            Excel::import(new RendisBbmImport, $request->file('file_excel'));
            return redirect()->route('admin.rendis.index')->with('success', 'Rendis BBM berhasil di-import dan dibuat.');
        } catch (ImportConflictException $e) {
            $path = $request->file('file_excel')->store('temp_imports');
            return back()->with('import_conflict', [
                'message' => $e->getMessage(),
                'file_path' => $path,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function resolveImportConflict(Request $request)
    {
        $request->validate([
            'file_path' => 'required|string',
            'action_type' => 'required|in:replace,update',
        ]);

        $filePath = $request->file_path;
        
        if (!Storage::exists($filePath)) {
            return redirect()->route('admin.rendis.import')->with('error', 'File temporary tidak ditemukan. Silakan upload ulang.');
        }

        try {
            Excel::import(new RendisBbmImport($request->action_type), $filePath);
            Storage::delete($filePath);
            
            $msg = $request->action_type === 'replace' ? 'ditimpa (replace)' : 'diperbarui (update)';
            return redirect()->route('admin.rendis.index')->with('success', "Rendis BBM berhasil {$msg}.");
        } catch (\Exception $e) {
            return redirect()->route('admin.rendis.import')->with('error', 'Gagal memproses konflik import: ' . $e->getMessage());
        }
    }
}
