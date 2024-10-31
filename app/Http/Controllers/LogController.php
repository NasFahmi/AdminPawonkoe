<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index()
    {
        $searchTerm = request('search');
        if ($searchTerm) {
            $data = Activity::where('event', 'like', '%' . $searchTerm . '%')
                ->orWhere('description', 'like', '%' . $searchTerm . '%')
                ->latest()->paginate(10);
        } else {
            $data = Activity::latest()->paginate(10);
        }
        $datetime = [];
        $actor = [];

        foreach ($data as $item) {
            $subject = User::find($item->causer_id);
            // Pastikan $subject tidak null
            if ($subject) {
                $actor[] = $subject->nama;
            } else {
                $actor[] = 'Unknown'; // Atau nilai default lainnya jika pengguna tidak ditemukan
            }

            // Konversi waktu dan tambahkan ke array $datetime
            $convertedTime = \Carbon\Carbon::parse($item->created_at, 'Europe/Lisbon')
                ->setTimezone('Asia/Jakarta')
                ->locale('id')
                ->isoFormat('D MMMM YYYY HH:mm:ss');

            $datetime[] = $convertedTime;
        }
        // dd($datetime);
        return view('pages.activities.index', compact('data', 'actor','datetime'));
    }
}
