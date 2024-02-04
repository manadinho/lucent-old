<?php

namespace App\Traits;

use App\Models\Team;

trait TeamTrait 
{
    /**
     * Generate a unique name by appending a number to the input name if it already exists in the database.
     *
     * @param string $name The original name to check for uniqueness.
     * @return string The unique name.
     */
    private function getUniqueName(string $name): string
    {
        // TO CHECK IF UPDATE REQUEST AND USER IS NOT UPDATIONG NAME
        if (request()->id) {
            if(Team::find(request()->id)->name ===  $name) {
                return $name;
            }
        }

        $originalName = $name;

        $counter = 1;

        while (Team::where(['name' => $name, 'user_id' => $this->user->id])->exists()) {
            $name = $originalName . '-' . $counter;

            $counter++;
        }

        return $name;
    }
}
