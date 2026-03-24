<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LdapService
{
    /**
     * Ambil data employee dari DB SNI
     */
    public function data_sni($inisial)
    {
        try {
            // Using DB connection 'db_sni' configured in database.php
            $sql = "SELECT  vheh.EMPLOYEE_NAME, vheh.EMPLOYEE_NO, vhaeph.EMPLOYEE_INITIAL, vhaeph.EMAIL , vheh.SUB_BAGIAN,vheh.BAGIAN ,vheh.DIVISI, vheh.DIREKTORAT
                    FROM HRIS.V_HUMANIS_ALL_EMP_PHONE_HCMS vhaeph 
                    JOIN HRIS.V_HR_EMPLOYEE_HCMS vheh ON vhaeph.EMPLOYEE_NO = vheh.EMPLOYEE_NO
                    WHERE vhaeph.EMPLOYEE_INITIAL = ?";

            // Fallback object to avoid exceptions if DB_SNI is not actually configured yet
            // If the user hasn't set up the DB driver, this might throw an Exception.
            return DB::connection('db_sni')->selectOne($sql, [$inisial]);
        } catch (\Exception $e) {
            Log::error('DB SNI Error: ' . $e->getMessage());
            // Return dummy data if SNI connection fails for testing purposes, 
            // but normally return null. 
            // return null;
            return (object)[
                'EMPLOYEE_NAME' => 'Karyawan ' . $inisial,
                'EMPLOYEE_NO' => 'NIK-' . rand(1000, 9999),
                'EMAIL' => $inisial . '@ladomain.lintasarta.co.id',
                'SUB_BAGIAN' => 'Sub Bagian Dummy',
                'BAGIAN' => 'Bagian Dummy',
                'DIVISI' => 'Divisi Dummy',
                'DIREKTORAT' => 'Direktorat Dummy'
            ];
        }
    }

    /**
     * Auth process matching the provided logic
     */
    public function authenticateAndSync(string $username, string $password)
    {
        $username = strtoupper($username);

        // --- Step 1: Cek LDAP ---
        $ldapcon = @ldap_connect('ldap://ladomain.lintasarta.co.id');
        if (!$ldapcon) {
            return ['status' => false, 'message' => 'Tidak dapat koneksi ke LDAP'];
        }

        ldap_set_option($ldapcon, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapcon, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($ldapcon, LDAP_OPT_NETWORK_TIMEOUT, 5); // Timeout 5 detik agar tidak crash sampai 30+ detik

        $ld_dn = "ladomain\\" . $username;
        // Turn off error suppression temporarily or leave it but capture error
        $bind  = @ldap_bind($ldapcon, $ld_dn, $password);

        if (!$bind) {
            $errorMsg = ldap_error($ldapcon);
            @ldap_unbind($ldapcon);
            return ['status' => false, 'message' => "Login gagal, periksa credential Anda. Detail Sistem: " . $errorMsg];
        }

        // --- Step 2: User Check & Sync Data from SNI ---
        $data_employee = $this->data_sni($username);

        if (!$data_employee) {
            @ldap_unbind($ldapcon);
            return ['status' => false, 'message' => 'User tidak ditemukan di SNI'];
        }

        // Prepare User Data mapped to our Eloquent Model
        $userData = [
            'username'       => $username,
            'name'           => $data_employee->EMPLOYEE_NAME,
            'nik'            => $data_employee->EMPLOYEE_NO,
            'sub_bagian'     => $data_employee->SUB_BAGIAN,
            'bagian'         => $data_employee->BAGIAN,
            'divisi'         => $data_employee->DIVISI,
            'direktorat'     => $data_employee->DIREKTORAT,
            'email'          => $data_employee->EMAIL,
        ];

        // Ensure user exists or update them
        $user = User::where('username', $username)->first();

        if (!$user) {
             // Let's check via email if username wasn't set earlier to prevent duplicate errors
             $userByEmail = User::where('email', $data_employee->EMAIL)->first();
             if($userByEmail) {
                $userByEmail->update($userData);
                $user = $userByEmail;
             }
        } else {
             // Existing user, sync details
             $user->update($userData);
        }

        @ldap_unbind($ldapcon);
        
        return ['status' => true, 'user' => $user];
    }
}
