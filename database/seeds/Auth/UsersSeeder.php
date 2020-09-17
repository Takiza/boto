<?php

use Database\traits\TruncateTable;
use Database\traits\DisableForeignKeys;

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        $this->truncate('users');

        $users = [
            [
                'platform_id' => 1,
                'phone' => '+1234567890',
                'first_name' => 'Admin',
                'last_name' => 'Adminovich',
                'email' => 'admin@bodo.com',
                'status' => 'Status 1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('users')->insert($users);

        factory(\App\User::class, 50)->create();

        $this->enableForeignKeys();
    }
}
