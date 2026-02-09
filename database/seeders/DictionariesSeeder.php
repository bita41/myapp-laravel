<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DictionariesSeeder extends Seeder
{
    public function run(): void
    {
        $rows = $this->getRows();

        if (empty($rows)) {
            return;
        }

        if (DB::table('dictionaries')->exists()) {
            return;
        }

        DB::table('dictionaries')->insert($rows);
    }

    /**
     * @return array<int, array{parameter: string, romanian: string, english: string, record_update_date: string}>
     */
    private function getRows(): array
    {
        $date = '2024-10-04 11:16:16';

        return [
            ['parameter' => 'a-simple-save', 'romanian' => 'Salvează', 'english' => 'Save', 'record_update_date' => $date],
            ['parameter' => 'a-cancel', 'romanian' => 'Anulare', 'english' => 'Cancel', 'record_update_date' => $date],
            ['parameter' => 'a-dictionary-parameter', 'romanian' => 'Parametru', 'english' => 'Parameter', 'record_update_date' => $date],
            ['parameter' => 'a-dictionary-romanian', 'romanian' => 'Română', 'english' => 'Romanian', 'record_update_date' => $date],
            ['parameter' => 'a-add-dictionary', 'romanian' => 'Adaugă Cuvânt', 'english' => 'Add Word', 'record_update_date' => $date],
            ['parameter' => 'a-filter', 'romanian' => 'Filtru', 'english' => 'Filter', 'record_update_date' => $date],
            ['parameter' => 'a-required-fields', 'romanian' => 'Completați câmpurile obligatorii!', 'english' => 'Fill in the required fields!', 'record_update_date' => $date],
            ['parameter' => 'a-please-wait', 'romanian' => 'Te rog așteaptă...', 'english' => 'Please wait...', 'record_update_date' => $date],
            ['parameter' => 'a-information-updated', 'romanian' => 'Informația a fost actualizată!', 'english' => 'The information has been updated!', 'record_update_date' => $date],
            ['parameter' => 'a-save-succesfully', 'romanian' => 'Informația a fost salvată cu succes!', 'english' => 'The information has been successfully saved!', 'record_update_date' => $date],
            ['parameter' => 'a-dictionary-duplicate-parameter', 'romanian' => 'Parametrul există deja in dicționar!', 'english' => 'The parameter already exists in the dictionary!', 'record_update_date' => $date],
            ['parameter' => 'a-dictionary', 'romanian' => 'Dicționar', 'english' => 'Dictionary', 'record_update_date' => $date],
            ['parameter' => 'a-settings', 'romanian' => 'Setări', 'english' => 'Settings', 'record_update_date' => $date],
            ['parameter' => 'a-simple-edit', 'romanian' => 'Editează', 'english' => 'Edit', 'record_update_date' => $date],
            ['parameter' => 'a-edit-dictionary', 'romanian' => 'Editează Cuvânt', 'english' => 'Edit Word', 'record_update_date' => $date],
            ['parameter' => 'a-edit-succesfully', 'romanian' => 'Informația a fost actualizată cu succes!', 'english' => 'Information has been successfully updated!', 'record_update_date' => $date],
            ['parameter' => 'a-users', 'romanian' => 'Utilizatori', 'english' => 'Users', 'record_update_date' => $date],
            ['parameter' => 'a-add-user', 'romanian' => 'Adaugă Utilizator', 'english' => 'Add User', 'record_update_date' => $date],
            ['parameter' => 'a-user-id', 'romanian' => 'ID', 'english' => 'ID', 'record_update_date' => $date],
            ['parameter' => 'a-user-name', 'romanian' => 'Nume', 'english' => 'Name', 'record_update_date' => $date],
            ['parameter' => 'a-user-email', 'romanian' => 'Email', 'english' => 'Email', 'record_update_date' => $date],
            ['parameter' => 'a-user-phone', 'romanian' => 'Telefon', 'english' => 'Phone', 'record_update_date' => $date],
            ['parameter' => 'a-user-date', 'romanian' => 'Data', 'english' => 'Date', 'record_update_date' => $date],
            ['parameter' => 'a-user-role', 'romanian' => 'Rol', 'english' => 'Role', 'record_update_date' => $date],
            ['parameter' => 'a-user-status', 'romanian' => 'Status', 'english' => 'Status', 'record_update_date' => $date],
            ['parameter' => 'a-filter-name', 'romanian' => 'Filtru nume', 'english' => 'Filter name', 'record_update_date' => $date],
            ['parameter' => 'a-filter-email', 'romanian' => 'Filtru email', 'english' => 'Filter email', 'record_update_date' => $date],
            ['parameter' => 'a-filter-phone', 'romanian' => 'Filtru telefon', 'english' => 'Filter phone', 'record_update_date' => $date],
            ['parameter' => 'a-user-password', 'romanian' => 'Parola', 'english' => 'Password', 'record_update_date' => $date],
            ['parameter' => 'a-user-status-active', 'romanian' => 'Activ', 'english' => 'Active', 'record_update_date' => $date],
            ['parameter' => 'a-user-status-inactive', 'romanian' => 'Inactiv', 'english' => 'Inactive', 'record_update_date' => $date],
            ['parameter' => 'a-admin-name', 'romanian' => 'Admin Nume Aplicație', 'english' => 'Admin Application Name', 'record_update_date' => $date],
            ['parameter' => 'a-your-email', 'romanian' => 'Adresa Dvs. de email!', 'english' => 'Your Email', 'record_update_date' => $date],
            ['parameter' => 'a-your-password', 'romanian' => 'Parola Dvs.', 'english' => 'Your Password', 'record_update_date' => $date],
            ['parameter' => 'a-login', 'romanian' => 'Autentificare', 'english' => 'Login', 'record_update_date' => $date],
            ['parameter' => 'a-login-remember', 'romanian' => 'Ține-mă minte pentru 30 zile!', 'english' => 'Remember me for 30 days!', 'record_update_date' => $date],
            ['parameter' => 'a-email', 'romanian' => 'Adresa de Email', 'english' => 'Email Address', 'record_update_date' => $date],
            ['parameter' => 'a-password', 'romanian' => 'Parola', 'english' => 'Password', 'record_update_date' => $date],
            ['parameter' => 'a-logout', 'romanian' => 'Delogare', 'english' => 'Logout', 'record_update_date' => $date],
            ['parameter' => 'a-print', 'romanian' => 'Printează', 'english' => 'Print', 'record_update_date' => $date],
            ['parameter' => 'a-fullscreen', 'romanian' => 'Ecran Complet', 'english' => 'Full Screen', 'record_update_date' => $date],
            ['parameter' => 'a-user-profile', 'romanian' => 'Profil Utilizator', 'english' => 'User Profile', 'record_update_date' => $date],
            ['parameter' => 'a-edit-user', 'romanian' => 'Editeaza User', 'english' => 'Edit User', 'record_update_date' => $date],
            ['parameter' => 'a-roles', 'romanian' => 'Roluri', 'english' => 'Roles', 'record_update_date' => $date],
            ['parameter' => 'a-add-role', 'romanian' => 'Adauga Rol', 'english' => 'Add Role', 'record_update_date' => $date],
            ['parameter' => 'a-login-success', 'romanian' => 'Autentificare realizată cu succes!', 'english' => 'Login successfully!', 'record_update_date' => $date],
            ['parameter' => 'a-role-id', 'romanian' => 'ID', 'english' => 'ID', 'record_update_date' => $date],
            ['parameter' => 'a-filter-description', 'romanian' => 'Filtru descriere', 'english' => 'Filter description', 'record_update_date' => $date],
            ['parameter' => 'a-role-name', 'romanian' => 'Nume', 'english' => 'Name', 'record_update_date' => $date],
            ['parameter' => 'a-role-description', 'romanian' => 'Descriere', 'english' => 'Description', 'record_update_date' => $date],
            ['parameter' => 'a-role-date', 'romanian' => 'Data', 'english' => 'Date', 'record_update_date' => $date],
            ['parameter' => 'a-role-select', 'romanian' => 'Alege Permisiunile', 'english' => 'Choose Permissions', 'record_update_date' => $date],
            ['parameter' => 'a-role-permission-read', 'romanian' => 'Citire', 'english' => 'Read', 'record_update_date' => $date],
            ['parameter' => 'a-role-permission-edit', 'romanian' => 'Editare', 'english' => 'Edit', 'record_update_date' => $date],
            ['parameter' => 'a-role-permission-add', 'romanian' => 'Adăugare', 'english' => 'Add', 'record_update_date' => $date],
            ['parameter' => 'a-role-permission-delete', 'romanian' => 'Ștergere', 'english' => 'Delete', 'record_update_date' => $date],
            ['parameter' => 'a-all', 'romanian' => 'ALL', 'english' => 'ALL', 'record_update_date' => $date],
            ['parameter' => 'a-personal', 'romanian' => 'Personal', 'english' => 'Personal', 'record_update_date' => $date],
            ['parameter' => 'a-delete', 'romanian' => 'Șterge', 'english' => 'Delete', 'record_update_date' => $date],
            ['parameter' => 'a-confirm-delete', 'romanian' => 'Confirmare ștergere', 'english' => 'Confirm delete', 'record_update_date' => $date],
            ['parameter' => 'a-message-delete', 'romanian' => 'Ești sigur că vrei sa ștergi această înregistrare?', 'english' => 'Are you sure you want to delete this recording?', 'record_update_date' => $date],
            ['parameter' => 'a-none', 'romanian' => '[None]', 'english' => '[None]', 'record_update_date' => $date],
            ['parameter' => 'a-user-language', 'romanian' => 'Limba', 'english' => 'Language', 'record_update_date' => $date],
            ['parameter' => 'a-user-image', 'romanian' => 'Imaginea de profil', 'english' => 'Profile Image', 'record_update_date' => $date],
            ['parameter' => 'a-choose-file', 'romanian' => 'Alege Fișierul ...', 'english' => 'Choose File', 'record_update_date' => $date],
            ['parameter' => 'a-module_settings', 'romanian' => 'Setări', 'english' => 'Settings', 'record_update_date' => $date],
            ['parameter' => 'a-module_settings_tab_1', 'romanian' => '-> Dicționar', 'english' => '-> Dictionary', 'record_update_date' => $date],
            ['parameter' => 'a-module_settings_tab_2', 'romanian' => '-> Utilizatori', 'english' => '-> Users', 'record_update_date' => $date],
            ['parameter' => 'a-module_settings_tab_3', 'romanian' => '-> Roluri', 'english' => '-> Roles', 'record_update_date' => $date],
            ['parameter' => 'a-edit-role', 'romanian' => 'Editează Rol', 'english' => 'Edit Role', 'record_update_date' => $date],
            ['parameter' => 'a-dictionary-english', 'romanian' => 'Engleză', 'english' => 'English', 'record_update_date' => $date],
            ['parameter' => 'a-edit-setting', 'romanian' => 'Editeaza setare', 'english' => 'Edit Settings', 'record_update_date' => $date],
            ['parameter' => 'a-language-ro', 'romanian' => 'Română', 'english' => 'Romanian', 'record_update_date' => $date],
            ['parameter' => 'a-language-en', 'romanian' => 'Engleză', 'english' => 'English', 'record_update_date' => $date],
            ['parameter' => 'a-company_name', 'romanian' => 'Denumire companie', 'english' => 'Company Name', 'record_update_date' => $date],
            ['parameter' => 'a-settings-name', 'romanian' => 'Denumire setare', 'english' => 'Settings Name', 'record_update_date' => $date],
            ['parameter' => 'a-settings-value', 'romanian' => 'Valoare setare', 'english' => 'Settings Value', 'record_update_date' => $date],
            ['parameter' => 'a-created-date', 'romanian' => 'Data', 'english' => 'Date', 'record_update_date' => $date],
            ['parameter' => 'a-company_tax_code', 'romanian' => 'Cod fiscal', 'english' => 'Tax Code', 'record_update_date' => $date],
            ['parameter' => 'a-company_trade_register', 'romanian' => 'Numar registrul comertului', 'english' => 'Trade Register', 'record_update_date' => $date],
            ['parameter' => 'a-company_social_center', 'romanian' => 'Sediu social', 'english' => 'Social Headquarters', 'record_update_date' => $date],
            ['parameter' => 'a-company_comercial_center', 'romanian' => 'Sediu comercial', 'english' => 'Commercial Headquarters', 'record_update_date' => $date],
            ['parameter' => 'a-company_iban', 'romanian' => 'Cont IBAN', 'english' => 'IBAN account', 'record_update_date' => $date],
            ['parameter' => 'a-company_bank', 'romanian' => 'Banca', 'english' => 'Bank', 'record_update_date' => $date],
            ['parameter' => 'a-company_phone', 'romanian' => 'Număr de telefon', 'english' => 'Phone Number', 'record_update_date' => $date],
            ['parameter' => 'a-company_email', 'romanian' => 'Adresa de email', 'english' => 'Email Address', 'record_update_date' => $date],
            ['parameter' => 'a-company_web', 'romanian' => 'Adresa web', 'english' => 'Web Address', 'record_update_date' => $date],
            ['parameter' => 'a-home', 'romanian' => 'Dashboard', 'english' => 'Dashboard', 'record_update_date' => $date],
            ['parameter' => 'a-module_customers', 'romanian' => 'Clienti', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-customers', 'romanian' => 'Clienti', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-add-customer', 'romanian' => 'Adauga Client', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-customer-name', 'romanian' => 'Nume si Prenume', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-customer-email', 'romanian' => 'Email', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-customer-phone', 'romanian' => 'Telefon', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-customer-date', 'romanian' => 'Data', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-projects', 'romanian' => 'Proiecte', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-module_projects', 'romanian' => 'Proiecte', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-project-type', 'romanian' => 'Tip', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-project-name', 'romanian' => 'Nume Proiect', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-project-currency', 'romanian' => 'Moneda', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-project-status', 'romanian' => 'Status', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-project-date', 'romanian' => 'Data', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-add-project', 'romanian' => 'Adauga Proiect', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-tasks', 'romanian' => 'Tasks', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-task-name', 'romanian' => 'Nume Task', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-task-project-name', 'romanian' => 'Nume Proiect', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-task-unit-price', 'romanian' => 'Pret / ora', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-add-task', 'romanian' => 'Adauga Task', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-task-status', 'romanian' => 'Status', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-task-total-price', 'romanian' => 'Total', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-insert-date', 'romanian' => 'Data crearii', 'english' => '', 'record_update_date' => $date],
            ['parameter' => 'a-total', 'romanian' => 'Total', 'english' => '', 'record_update_date' => $date],
        ];
    }
}
