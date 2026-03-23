<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Division;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $karyawanRole = Role::where('nama_role', 'karyawan')->first();
        $divisions = Division::all();

        foreach (range(1, 12) as $i) {
            User::create([
                'name' => "Karyawan $i",
                'email' => "karyawan$i@erp.test",
                'password' => Hash::make('password'),
                'role_id' => $karyawanRole->id,
                'division_id' => $divisions->random()->id,
            ]);
        }
    }
}
