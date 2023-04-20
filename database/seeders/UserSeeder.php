<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Jhonny Pirela',
            'phone' => '4147497092',
            'email' => 'jhosagid77@gmail.com',
            'profile' => 'ADMIN',
            'status' => 'ACTIVE',
            'password' => bcrypt('jhosagid')
        ]);
        User::create([
            'name' => 'Vendedor Generico',
            'phone' => '3549873214',
            'email' => 'vendedor@gmail.com',
            'profile' => 'EMPLOYEE',
            'status' => 'ACTIVE',
            'password' => bcrypt('123')
        ]);

        // crear role Administrador
        $admin    = Role::create(['name' => 'Admin']);
        $employee    = Role::create(['name' => 'Employee']);

        // crear permisos componente categories
        Permission::create(['name' => 'Category_Create']);
        Permission::create(['name' => 'Category_Search']);
        Permission::create(['name' => 'Category_Update']);
        Permission::create(['name' => 'Category_Destroy']);

        // crear permisos componente products Product_Update
        Permission::create(['name' => 'Product_Create']);
        Permission::create(['name' => 'Product_Search']);
        Permission::create(['name' => 'Product_Update']);
        Permission::create(['name' => 'Product_Destroy']);

        // asignar permisos al rol Admin sobre categories
        $admin->givePermissionTo([
            'Category_Create', 
            'Category_Search', 
            'Category_Update', 
            'Category_Destroy'
        ]);

        // asignar permisos al rol Admin sobre Products
        $admin->givePermissionTo([
            'Product_Create', 
            'Product_Search', 
            'Product_Update', 
            'Product_Destroy'
        ]);

        // asignar role Admin al usuario Jhonny Pirela
        $uAdmin = User::find(1);
        $uAdmin->assignRole('Admin');

        // asignar permisos al rol Enmployee sobre categories
        $employee->givePermissionTo([
            'Category_Search',
        ]);

        // asignar permisos al rol Admin sobre Products
        $employee->givePermissionTo([
            'Product_Search',
        ]);

        // asignar role Employee al usuario Vendedor
        $uEmployee = User::find(2);
        $uEmployee->assignRole('Employee');
    }
}
