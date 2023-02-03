<?php

use App\Models\AdminUser;

/**
 * Class AdminUserTableSeeder
 */
class AdminUserTableSeeder extends Seeder
{
    public function run()
    {
        if (AdminUser::where('username', 'admin')->count() == 0) {
            $adminUser = new AdminUser(['username' => 'admin', 'password' => 'diol', 'active' => true]);
            $adminUser->super = true;
            $adminUser->save();
        }
    }
}
