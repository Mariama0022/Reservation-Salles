<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Salle;

class EloquentSalleRepository implements SalleRepositoryInterface
{
    public function lister(): array
    {
        return Salle::query()->get()->all();
    }

    public function trouver(int $id): ?Salle
    {
        return Salle::query()->find($id);
    }

    public function enregistrer(Salle $salle): Salle
    {
        $salle->save();

        return $salle;
    }
}
