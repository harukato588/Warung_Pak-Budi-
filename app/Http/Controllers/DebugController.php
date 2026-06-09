<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebugController extends Controller
{
    // ⚠️ VULN #10: Sensitive Data Exposure
    // Endpoint ini terbuka untuk SEMUA orang (tidak ada auth!)
    // Membocorkan: semua user + password hash, env config, versi PHP/Laravel
    public function index()
    {
        $users = DB::table('users')->get(); // semua user + password hash!

        $info = [
            'app_name'     => config('app.name'),
            'app_env'      => config('app.env'),
            'app_debug'    => config('app.debug'),
            'app_key'      => config('app.key'),         // ⚠️ APP_KEY bocor!
            'db_host'      => config('database.connections.mysql.host'),
            'db_database'  => config('database.connections.mysql.database'),
            'db_username'  => config('database.connections.mysql.username'),
            'db_password'  => config('database.connections.mysql.password'), // ⚠️ password DB bocor!
            'php_version'  => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_info'  => $_SERVER,                  // ⚠️ seluruh $_SERVER bocor!
        ];

        return response()->json([
            'status'  => 'debug_mode_active',
            'server'  => $info,
            'users'   => $users, // ⚠️ semua user + hash password!
            'session' => session()->all(),
        ]);
    }

    // ⚠️ VULN #10: Endpoint ini dump semua orders tanpa auth
    public function orders()
    {
        $orders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name', 'users.email')
            ->get();

        return response()->json($orders);
    }
}
