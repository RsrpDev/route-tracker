<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $payments;
    protected $includeDetails;

    public function __construct($payments, $includeDetails = true)
    {
        $this->payments = $payments;
        $this->includeDetails = $includeDetails;
    }

    public function collection()
    {
        return $this->payments;
    }

    public function headings(): array
    {
        $headings = [
            'ID Pago',
            'ID Suscripción',
            'Estudiante',
            'Colegio',
            'Ruta',
            'Proveedor',
            'Período Inicio',
            'Período Fin',
            'Monto Total',
            'Comisión Plataforma',
            'Monto Proveedor',
            'Método Pago',
            'Estado',
            'Fecha Pago',
            'Fecha Creación'
        ];

        if ($this->includeDetails) {
            $headings = array_merge($headings, [
                'Teléfono Estudiante',
                'Email Estudiante',
                'Dirección Estudiante',
                'Nombre Proveedor',
                'Teléfono Proveedor',
                'Email Proveedor'
            ]);
        }

        return $headings;
    }

    public function map($payment): array
    {
        $studentName = 'N/A';
        $studentPhone = 'N/A';
        $studentEmail = 'N/A';
        $studentAddress = 'N/A';
        $schoolName = 'N/A';
        $routeName = 'N/A';
        $providerName = 'N/A';
        $providerPhone = 'N/A';
        $providerEmail = 'N/A';

        if ($payment->subscription && $payment->subscription->transportContract) {
            $contract = $payment->subscription->transportContract;

            if ($contract->student) {
                $studentName = $contract->student->first_name . ' ' . $contract->student->last_name;
                $studentPhone = $contract->student->phone ?? 'N/A';
                $studentEmail = $contract->student->email ?? 'N/A';
                $studentAddress = $contract->student->address ?? 'N/A';
            }

            if ($contract->pickupRoute) {
                $routeName = $contract->pickupRoute->route_name;

                if ($contract->pickupRoute->provider) {
                    $providerName = $contract->pickupRoute->provider->provider_name;
                    $providerPhone = $contract->pickupRoute->provider->phone ?? 'N/A';
                    $providerEmail = $contract->pickupRoute->provider->email ?? 'N/A';
                }
            }

            if ($contract->student && $contract->student->school) {
                $schoolName = $contract->student->school->school_name;
            }
        }

        $row = [
            $payment->payment_id,
            $payment->subscription_id ?? 'N/A',
            $studentName,
            $schoolName,
            $routeName,
            $providerName,
            $payment->period_start ? $payment->period_start->format('d/m/Y') : 'N/A',
            $payment->period_end ? $payment->period_end->format('d/m/Y') : 'N/A',
            '$' . number_format($payment->amount_total, 2),
            '$' . number_format($payment->platform_fee, 2),
            '$' . number_format($payment->provider_amount, 2),
            ucfirst(str_replace('_', ' ', $payment->payment_method)),
            ucfirst($payment->payment_status),
            $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'N/A',
            $payment->created_at->format('d/m/Y H:i')
        ];

        if ($this->includeDetails) {
            $row = array_merge($row, [
                $studentPhone,
                $studentEmail,
                $studentAddress,
                $providerName,
                $providerPhone,
                $providerEmail
            ]);
        }

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para el encabezado
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '366092']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        $widths = [
            'A' => 10, // ID Pago
            'B' => 15, // ID Suscripción
            'C' => 25, // Estudiante
            'D' => 20, // Colegio
            'E' => 20, // Ruta
            'F' => 20, // Proveedor
            'G' => 15, // Período Inicio
            'H' => 15, // Período Fin
            'I' => 15, // Monto Total
            'J' => 20, // Comisión Plataforma
            'K' => 20, // Monto Proveedor
            'L' => 20, // Método Pago
            'M' => 15, // Estado
            'N' => 20, // Fecha Pago
            'O' => 20, // Fecha Creación
        ];

        if ($this->includeDetails) {
            $widths = array_merge($widths, [
                'P' => 20, // Teléfono Estudiante
                'Q' => 30, // Email Estudiante
                'R' => 30, // Dirección Estudiante
                'S' => 20, // Nombre Proveedor
                'T' => 20, // Teléfono Proveedor
                'U' => 30, // Email Proveedor
            ]);
        }

        return $widths;
    }

    public function title(): string
    {
        return 'Reporte de Pagos';
    }
}
