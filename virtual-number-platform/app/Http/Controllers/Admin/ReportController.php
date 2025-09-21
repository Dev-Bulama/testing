<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\PhoneNumber;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $from = Carbon::parse(request('from', now()->subMonth()->toDateString()));
        $to = Carbon::parse(request('to', now()->toDateString()));

        return view('admin.reports.index', [
            'from' => $from,
            'to' => $to,
            'numbersRented' => PhoneNumber::whereBetween('created_at', [$from, $to])->count(),
            'revenue' => Transaction::whereBetween('created_at', [$from, $to])
                ->where('type', Transaction::TYPE_DEBIT)
                ->sum('amount'),
            'smsCount' => Message::whereBetween('created_at', [$from, $to])->count(),
        ]);
    }
}
