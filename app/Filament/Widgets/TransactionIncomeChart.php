<?php

namespace App\Filament\Widgets;

use Flowframe\Trend\Trend;
use App\Models\Transactions;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class TransactionIncomeChart extends ChartWidget
{
    use InteractsWithPageFilters;
    protected static ?string $heading = 'Pemasukan';
    protected static string $color = 'success';
    protected function getData(): array
    {
        $startDate = isset($this->filters['startDate']) && $this->filters['startDate']
            ? Carbon::parse($this->filters['startDate'])
            : now(); // Gunakan hari ini sebagai default

        $endDate = isset($this->filters['endDate']) && $this->filters['endDate']
            ? Carbon::parse($this->filters['endDate'])
            : now(); // Gunakan hari ini sebagai default

        // Pastikan $startDate dan $endDate bukan null
        if (is_null($startDate) || is_null($endDate)) {
            throw new \Exception("Invalid date range: startDate or endDate is null");
        }

        $data = Trend::query(Transactions::Income())
            ->between(
                start: $startDate,
                end: $endDate,
            )
            ->dateColumn('date_transtion')
            ->perDay()
            ->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }


    protected function getType(): string
    {
        return 'line';
    }
}
