<?php

namespace App\Filament\Widgets;

use App\Models\Transactions;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class YCategoriesChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Pengeluaran Berdaskan Category';
    // protected static string $color = 'warning';

    protected function getData(): array
    {
        // Ambil filter tanggal dari page filter
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            now()->startOfMonth();

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        // Query data berdasarkan kategori
        $data = Transactions::Expenses()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('category') // Pastikan relasi category ada
            ->get()
            ->groupBy('category.name')
            ->map(function ($transactions) {
                return $transactions->sum('amount');
            });

        return [
            'datasets' => [
                [
                    'label' => 'Pengeluaran per Kategori',
                    'data' => $data->values(),
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'], // Tambahkan warna
                ],
            ],
            'labels' => $data->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
