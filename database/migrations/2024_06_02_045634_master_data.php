<?php

use App\Models\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('accounts')) {
            Account::create([
                'userName' => 'admin',
                'password' => '$2y$10$FM2KT1iDiNA4uPwokd9fQ.A0T9JSB7DKwoovI1kq8LFBYBlFHs.rm',
                'role' => 3,
            ]);
        }
    }

    public function down()
    {
        Account::where('userName', 'admin')->delete();
    }
};
