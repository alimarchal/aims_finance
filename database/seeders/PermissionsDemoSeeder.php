<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions with the 'sanctum' guard
        $permissions = [
            'Full system control',
            'User management',
            'Role and permission management',
            'Patient registration',
            'Appointment scheduling',
            'Insurance verification',
            'Check-in/check-out',
            'Billing and invoicing',
            'EHR access',
            'Diagnosis and treatment planning',
            'Prescription and medication management',
            'Ordering and reviewing tests and imaging',
            'Patient monitoring',
            'Medication administration',
            'Recording vital signs',
            'Laboratory test processing',
            'Medication dispensing',
            'Medical imaging management',
            'Insurance claims management',
            'Viewing personal health records',
            'High-level analytics',
            'System maintenance',
            'Data security and access control',
            'Technical issue troubleshooting',
            'User support',
            'HMS software configuration',
        ];

        foreach ($permissions as $permissionName) {
            Permission::create(['name' => $permissionName, 'guard_name' => 'sanctum']);
        }

        // Create roles and assign existing permissions
        $rolePermissions = [
            'Administrator' => ['Full system control', 'User management', 'Role and permission management'],
            'Front Desk/Receptionist' => ['Patient registration', 'Issue Emergency Chit', 'Appointment scheduling', 'Insurance verification', 'Check-in/check-out', 'Billing and invoicing'],
            'Doctor/Physician' => ['EHR access', 'Diagnosis and treatment planning', 'Prescription and medication management', 'Ordering and reviewing tests and imaging'],
            'Nurse' => ['Patient monitoring', 'Medication administration', 'Recording vital signs'],
            'Laboratory Technician' => ['Laboratory test processing'],
            'Pharmacist' => ['Medication dispensing'],
            'Radiologist/Imaging Technician' => ['Medical imaging management'],
            'Insurance Coordinator/Billing Specialist' => ['Insurance claims management'],
            'Patient/Portal User' => ['Viewing personal health records', 'High-level analytics'],
            'Manager/Executive' => ['High-level analytics', 'System maintenance', 'Data security and access control', 'Technical issue troubleshooting', 'User support', 'HMS software configuration'],
            'IT Support/System Administrator' => ['System maintenance', 'Data security and access control', 'Technical issue troubleshooting', 'User support', 'HMS software configuration'],
        ];

        foreach ($rolePermissions as $roleName => $permissionNames) {
            $role = Role::create(['name' => $roleName, 'guard_name' => 'sanctum']);
            $role->syncPermissions($permissionNames);
        }

        // Create demo users and assign roles
        $users = [
            ['name' => 'Example User', 'email' => 'user@aims.com', 'password' => Hash::make('123456'), 'role' => 'Front Desk/Receptionist'],
            ['name' => 'Super-Admin User', 'email' => 'kh.marchal@gmail.com', 'password' => Hash::make('123456'), 'role' => 'Administrator'],
        ];

        foreach ($users as $userData) {
            $user = \App\Models\User::factory()->create($userData);
            $role = Role::where('name', $userData['role'])->first();
            $user->assignRole($role);
        }
    }
}
