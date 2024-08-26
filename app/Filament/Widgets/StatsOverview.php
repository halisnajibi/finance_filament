<?php

namespace App\Filament\Widgets;

use App\Models\Transactions;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        $pemasukan = Transactions::income()->whereBetween('date_transtion', [$startDate, $endDate])->sum('amount');
        $pengeluaran = Transactions::Expenses()->whereBetween('date_transtion', [$startDate, $endDate])->sum('amount');
        return [
            Stat::make('Pemasukan', 'Rp' . number_format($pemasukan))

                ->color('success'),
            Stat::make('Pengeluaran', 'Rp' . number_format($pengeluaran))

                ->color('danger'),
            Stat::make('Selisih', 'Rp' . number_format($pemasukan - $pengeluaran))

                ->color('warning'),
        ];
    }
}
