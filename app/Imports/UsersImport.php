<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithLimit;

use Exception;

class UsersImport implements ToCollection, WithHeadingRow
{
    protected $users = [];

    public function collection(Collection $rows)
    {

        if(count($rows)> 100 ) 
        {
            throw new Exception('Maximum 100 row are allowed', 422);
        }
        foreach ($rows as $row) {
            if (
                isset($row['first_name']) &&
                isset($row['last_name']) &&
                isset($row['date_of_birth']) &&
                isset($row['mobile_number']) &&
                isset($row['father_full_name']) &&
                isset($row['select_state']) &&
                isset($row['select_city']) &&
                isset($row['address'])
            ) {
                $user = new User([
                    'first_name' => $row['first_name'],
                    'middle_name' => $row['middle_name'],
                    'last_name' => $row['last_name'],
                    'date_of_birth' => $row['date_of_birth'],
                    'mobile_number' => $row['mobile_number'],
                    'father_full_name' => $row['father_full_name'],
                    'state' => $row['select_state'],
                    'city' => $row['select_city'],
                    'address' => $row['address'],
                    'profile_photo' => 'default.jpg',
                ]);

                $this->users[] = $user;
            }
        }
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function columnFormats(): array
    {
        return [
            'date_of_birth' => 'dd/mm/yyyy',
        ];
    }

    public function limit(): int
    {
        return 1;
    }
}
