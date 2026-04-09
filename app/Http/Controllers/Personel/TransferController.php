<?php

namespace App\Http\Controllers\Personel;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Personel;
use App\Models\RiwayatTransferAntarPersonel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LogAktivitas;
use App\Notifications\TransferNotification;
use App\Models\Chat;

class TransferController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $personel = $user->personel;
        $satkerId = $user->satker_id;

        // Get list of other personels in same Satker AND (same fuel type OR fuel type is empty)
        $personels = Personel::where('satker_id', $satkerId)
            ->where('id', '!=', $personel->id)
            ->where(function ($q) use ($personel) {
                $q->where('jenis_bbm', $personel->jenis_bbm)
                  ->orWhereNull('jenis_bbm')
                  ->orWhere('jenis_bbm', '');
            })
            ->orderBy('nama')
            ->get();
        
        // Get list of vehicles in same Satker with same fuel type or matching bbm
        $availableKendaraans = Kendaraan::where('satker_id', $satkerId)
            ->where('jenis_bbm', $personel->jenis_bbm)
            ->orderBy('no_polisi')
            ->get();

        // Get transfer history (sent and received)
        $riwayat = RiwayatTransferAntarPersonel::with(['sender', 'receiver'])
            ->where(function ($query) use ($personel) {
                $query->where('sender_id', $personel->id)
                      ->orWhere('receiver_id', $personel->id);
            })
            ->latest()
            ->paginate(10);

        return view('personel.transfer.index', compact('personel', 'personels', 'riwayat', 'availableKendaraans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_tujuan' => 'required|in:personel,kendaraan',
            'receiver_id' => 'nullable|required_if:tipe_tujuan,personel|exists:personels,id',
            'target_kendaraan_id' => 'nullable|required_if:tipe_tujuan,kendaraan|exists:kendaraans,id',
            'jumlah' => 'required|numeric|min:1',
            'pin' => 'required|string',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $sender = $user->personel;
        $targetName = '';
        $targetType = $request->tipe_tujuan;

        // Find Target
        if ($targetType === 'personel') {
            $receiver = Personel::findOrFail($request->receiver_id);
            $targetName = $receiver->nama;

            if ($sender->satker_id !== $receiver->satker_id) {
                return back()->with('error', 'Penerima harus berada dalam satu Satker.');
            }

            if ($receiver->jenis_bbm && $sender->jenis_bbm !== $receiver->jenis_bbm) {
                return back()->with('error', 'Transfer hanya bisa dilakukan ke sesama jenis BBM (' . $sender->jenis_bbm . '). Penerima saat ini terdaftar dengan BBM ' . $receiver->jenis_bbm . '.');
            }
        } else {
            $receiverKendaraan = Kendaraan::findOrFail($request->target_kendaraan_id);
            $targetName = "Kendaraan " . $receiverKendaraan->no_polisi;

            if ($sender->satker_id !== $receiverKendaraan->satker_id) {
                return back()->with('error', 'Kendaraan harus berada dalam satu Satker.');
            }

            if ($sender->jenis_bbm !== $receiverKendaraan->jenis_bbm) {
                 return back()->with('error', 'Jenis BBM kendaraan (' . $receiverKendaraan->jenis_bbm . ') tidak cocok dengan BBM Anda (' . $sender->jenis_bbm . ').');
            }
        }

        if ($sender->pin !== $request->pin) {
            return back()->with('error', 'PIN salah.');
        }

        if ($sender->saldo < $request->jumlah) {
             return back()->with('error', 'Saldo tidak mencukupi.');
        }

        try {
            DB::transaction(function () use ($sender, $request, $targetType, $targetName) {
                // Deduct from sender
                $sender->decrement('saldo', $request->jumlah);

                if ($targetType === 'personel') {
                    $receiver = Personel::findOrFail($request->receiver_id);
                    // Add to receiver & set fuel type if empty
                    $receiver->increment('saldo', $request->jumlah);
                    if (!$receiver->jenis_bbm) {
                        $receiver->jenis_bbm = $sender->jenis_bbm;
                        $receiver->save();
                    }

                    // Record transaction
                    RiwayatTransferAntarPersonel::create([
                        'satker_id' => $sender->satker_id,
                        'sender_id' => $sender->id,
                        'receiver_id' => $receiver->id,
                        'jumlah' => $request->jumlah,
                        'jenis_bbm' => $sender->jenis_bbm ?: 'TANPA JENIS',
                        'keterangan' => $request->keterangan,
                    ]);

                    // Send Notification & Chat
                    if ($receiver->user) {
                        $receiver->user->notify(new TransferNotification($sender, $request->jumlah, $request->keterangan));
                        Chat::create([
                            'sender_id' => Auth::id(),
                            'receiver_id' => $receiver->user->id,
                            'message' => "Transfer saldo berhasil masuk sebesar " . number_format($request->jumlah, 0, ',', '.') . " Liter" . ($request->keterangan ? ". Keterangan: " . $request->keterangan : ""),
                            'is_read' => false,
                        ]);
                    }
                } else {
                    $receiverKendaraan = Kendaraan::findOrFail($request->target_kendaraan_id);
                    $receiverKendaraan->increment('saldo', $request->jumlah);

                    // Record transaction
                    RiwayatTransferAntarPersonel::create([
                        'satker_id' => $sender->satker_id,
                        'sender_id' => $sender->id,
                        'target_kendaraan_id' => $receiverKendaraan->id,
                        'jumlah' => $request->jumlah,
                        'jenis_bbm' => $sender->jenis_bbm ?: 'TANPA JENIS',
                        'keterangan' => $request->keterangan,
                    ]);
                }
                
                LogAktivitas::create([
                    'user_id' => Auth::id(),
                    'aktivitas' => "Transfer: " . number_format($request->jumlah, 0, ',', '.') . " Liter ke " . $targetName . ". Ket: " . ($request->keterangan ?? '-'),
                ]);
            });

            return back()->with('success', 'Transfer saldo berhasil.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memproses transfer: ' . $e->getMessage());
        }
    }
}
