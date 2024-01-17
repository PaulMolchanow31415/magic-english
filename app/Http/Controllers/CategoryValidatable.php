<?php

namespace App\Http\Controllers;

trait CategoryValidatable {

    public function validateRequest(string $forTableName): void {
        request()->validate([
            'id'   => ['int', 'nullable'],
            'name' => ['string', 'unique:'.$forTableName, 'min:3', 'max:255'],
        ]);
    }

}