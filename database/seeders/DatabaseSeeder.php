<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Pepe',
            'last_name' => 'Perez',
            'avatar' => 'pepote',
            'email' => 'pepe@mail.es',
            'password' => bcrypt('12345678')
        ]);


        User::factory(20)->create()->each(function ($user) {

            // Asignación aleatoria de roles usando random_int(0, 9)
            $role = random_int(0, 9);

            // Definir display_name y description basados en el rol asignado
            if ($role < 1) {
                // 10% admin
                $roleName = 'admin';
                $displayName = 'Administrador';
                $description = 'Administrador de la aplicación – Puede hacer y deshacer todo';
            } elseif ($role < 2) {
                // 10% creator
                $roleName = 'creator';
                $displayName = 'Creador';
                $description = 'Puede crear post y modificarlos mientras estén en modo draft.';
            } elseif ($role < 3) {
                // 10% editor
                $roleName = 'editor';
                $displayName = 'Editor';
                $description = 'Puede editar un post solo si está en modo pending.';
            } elseif ($role < 4) {
                // 10% validator
                $roleName = 'validator';
                $displayName = 'Verificador';
                $description = 'Puede cambiar el estado de un post de pending a published.';
            } elseif ($role < 5) {
                // 10% eraser
                $roleName = 'eraser';
                $displayName = 'Eliminador';
                $description = 'Puede borrar un post en cualquier momento.';
            } else {
                // 50% reader
                $roleName = 'reader';
                $displayName = 'Lector';
                $description = 'Solo puede acceder al listado de los posts y a cada uno de ellos de manera individual.';
            }

            // Crear o obtener el rol según la asignación
            $role = Role::firstOrCreate([
                'name' => $roleName,
            ], [
                'display_name' => $displayName,
                'description' => $description,
            ]);

            // Asignar el rol al usuario
            $user->role()->associate($role);  // Asociar el rol al usuario
            $user->save(); // Guardar al usuario con el rol asignado

            // Crear posts para cada usuario
            $user->posts()->saveMany(
                Post::factory(10)->make() // Generar 10 posts por usuario
            );
        });



        /*foreach ($users as $user) {
            $user->posts()->saveMany(
                Post::factory(10)->make()
            );
        }*/

        /*foreach ($users as $user) {
            Post::factory(10)->create([
                'user_id' => $user->id
            ]);
        }*/
    }
}
