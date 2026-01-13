<?php

namespace App\Application\Lots\Services;

use App\Domain\Lots\Models\Lot;
use Illuminate\Support\Facades\DB;

class LotService
{
    public function create(array $data): Lot
    {
        return DB::transaction(function () use ($data) {
            // Reserve next serial
            $sequenceId = DB::table('lot_sequences')->insertGetId([
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Convert to 8-digit lot number
            $data['lot_number'] = str_pad((string)$sequenceId, 8, '0', STR_PAD_LEFT);

            return Lot::create($data);
        });
    }

    public function update(Lot $lot, array $data): Lot
    {
        // Immutable lot number
        unset($data['lot_number']);

        $lot->update($data);
        return $lot->refresh();
    }

    public function archive(Lot $lot): void
    {
        $lot->update(['status' => 'ARCHIVED']);
    }
}
