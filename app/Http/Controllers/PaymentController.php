<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::paginate(15);
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => 'required|exists:subscriptions,subscription_id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'amount_total' => 'required|numeric|min:0',
            'platform_fee' => 'required|numeric|min:0',
            'provider_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:credit_card,debit_card,bank_transfer,cash',
            'payment_status' => 'required|in:pending,processing,completed,failed,cancelled,refunded'
        ]);

        Payment::create($validated);

        return redirect()->route('admin.payments.index')->with('success', 'Pago creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        return view('admin.payments.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'subscription_id' => 'required|exists:subscriptions,subscription_id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'amount_total' => 'required|numeric|min:0',
            'platform_fee' => 'required|numeric|min:0',
            'provider_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:credit_card,debit_card,bank_transfer,cash',
            'payment_status' => 'required|in:pending,processing,completed,failed,cancelled,refunded'
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')->with('success', 'Pago actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Pago eliminado exitosamente');
    }

    /**
     * Process payment
     */
    public function process(Payment $payment)
    {
        $payment->update(['payment_status' => 'completed', 'paid_at' => now()]);

        return redirect()->route('admin.payments.show', $payment)->with('success', 'Pago procesado exitosamente');
    }

    /**
     * Cancel payment
     */
    public function cancel(Payment $payment)
    {
        $payment->update(['payment_status' => 'cancelled']);

        return redirect()->route('admin.payments.show', $payment)->with('success', 'Pago cancelado exitosamente');
    }

    /**
     * Refund payment
     */
    public function refund(Payment $payment)
    {
        $payment->update(['payment_status' => 'refunded']);

        return redirect()->route('admin.payments.show', $payment)->with('success', 'Pago reembolsado exitosamente');
    }

    /**
     * Upload documents
     */
    public function uploadDocuments(Request $request, Payment $payment)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'document_file' => 'required|file|max:10240'
        ]);

        $file = $request->file('document_file');
        $path = $file->store('payment-documents', 'public');

        // Aquí podrías guardar la información del documento en la base de datos

        return redirect()->route('admin.payments.show', $payment)->with('success', 'Documento subido exitosamente');
    }

    /**
     * Bulk update payments
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,payment_id',
            'payment_status' => 'required|in:pending,processing,completed,failed,cancelled,refunded'
        ]);

        Payment::whereIn('payment_id', $request->payment_ids)
            ->update(['payment_status' => $request->payment_status]);

        return redirect()->route('admin.payments.index')->with('success', 'Pagos actualizados exitosamente');
    }

    /**
     * Export payments with advanced filtering and multiple formats
     */
    public function export(Request $request)
    {
        try {
            \Log::info('Iniciando exportación de pagos', [
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->account_type ?? 'no_auth',
                'request_params' => $request->all()
            ]);

            // Verificar autenticación
            if (!auth()->check()) {
                \Log::warning('Intento de exportación sin autenticación');
                return redirect()->route('login')->with('error', 'Debes iniciar sesión para exportar datos');
            }

            // Verificar rol de admin
            if (auth()->user()->account_type !== 'admin') {
                \Log::warning('Intento de exportación sin rol de admin', [
                    'user_id' => auth()->id(),
                    'user_role' => auth()->user()->account_type
                ]);
                return redirect()->back()->with('error', 'No tienes permisos para exportar datos');
            }

            // Obtener parámetros de filtrado
        $format = $request->get('format', 'csv'); // csv, excel, pdf
        $status = $request->get('status');
        $method = $request->get('method');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $includeDetails = $request->get('include_details', true);

        // Construir query con filtros
        $query = Payment::with([
            'subscription.transportContract.student',
            'subscription.transportContract.pickupRoute.provider'
        ]);

        // Aplicar filtros
        if ($status && $status !== '') {
            $query->where('payment_status', $status);
        }

        if ($method && $method !== '') {
            $query->where('payment_method', $method);
        }

        if ($dateFrom && $dateFrom !== '') {
            $query->where('created_at', '>=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo && $dateTo !== '') {
            $query->where('created_at', '<=', $dateTo . ' 23:59:59');
        }

        $payments = $query->orderBy('created_at', 'desc')->get();

        // Generar nombre de archivo con timestamp
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "payments_export_{$timestamp}";

        // Generar estadísticas
        $stats = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount_total'),
            'total_platform_fee' => $payments->sum('platform_fee'),
            'total_provider_amount' => $payments->sum('provider_amount'),
            'status_counts' => $payments->groupBy('payment_status')->map->count(),
            'method_counts' => $payments->groupBy('payment_method')->map->count()
        ];

        switch ($format) {
            case 'excel':
                return $this->exportToExcel($payments, $filename, $stats, $includeDetails);
            case 'pdf':
                return $this->exportToPDF($payments, $filename, $stats, $includeDetails);
            default:
                return $this->exportToCSV($payments, $filename, $stats, $includeDetails);
        }
        } catch (\Exception $e) {
            \Log::error('Error en exportación de pagos: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->account_type ?? 'no_auth',
                'request_params' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            // Si es una petición AJAX o API, devolver JSON
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'Error al exportar pagos',
                    'message' => $e->getMessage(),
                    'status' => 500
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al exportar pagos: ' . $e->getMessage());
        }
    }

    /**
     * Export to CSV format
     */
    private function exportToCSV($payments, $filename, $stats, $includeDetails)
    {
        \Log::info('Iniciando exportación CSV', [
            'payments_count' => $payments->count(),
            'filename' => $filename,
            'include_details' => $includeDetails
        ]);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        $callback = function() use ($payments, $stats, $includeDetails) {
            $file = fopen('php://output', 'w');

            // Agregar BOM para UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // Estadísticas generales
            fputcsv($file, ['REPORTE DE PAGOS - ESTADÍSTICAS GENERALES']);
            fputcsv($file, ['Fecha de Exportación', now()->format('d/m/Y H:i:s')]);
            fputcsv($file, ['Total de Pagos', $stats['total_payments']]);
            fputcsv($file, ['Monto Total', '$' . number_format($stats['total_amount'], 2)]);
            fputcsv($file, ['Total Comisiones Plataforma', '$' . number_format($stats['total_platform_fee'], 2)]);
            fputcsv($file, ['Total Monto Proveedores', '$' . number_format($stats['total_provider_amount'], 2)]);
            fputcsv($file, []); // Línea vacía

            // Distribución por estado
            fputcsv($file, ['DISTRIBUCIÓN POR ESTADO']);
            foreach ($stats['status_counts'] as $status => $count) {
                fputcsv($file, [ucfirst($status), $count]);
            }
            fputcsv($file, []); // Línea vacía

            // Distribución por método
            fputcsv($file, ['DISTRIBUCIÓN POR MÉTODO DE PAGO']);
            foreach ($stats['method_counts'] as $method => $count) {
                fputcsv($file, [ucfirst(str_replace('_', ' ', $method)), $count]);
            }
            fputcsv($file, []); // Línea vacía

            // Headers de datos
            $headers = [
                'ID Pago', 'ID Suscripción', 'Estudiante', 'Colegio', 'Ruta', 'Proveedor',
                'Período Inicio', 'Período Fin', 'Monto Total', 'Comisión Plataforma',
                'Monto Proveedor', 'Método Pago', 'Estado', 'Fecha Pago', 'Fecha Creación'
            ];

            if ($includeDetails) {
                $headers = array_merge($headers, [
                    'Teléfono Estudiante', 'Email Estudiante', 'Dirección Estudiante',
                    'Nombre Proveedor', 'Teléfono Proveedor', 'Email Proveedor'
                ]);
            }

            fputcsv($file, $headers);

            // Datos
            foreach ($payments as $payment) {
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

                if ($includeDetails) {
                    $row = array_merge($row, [
                        $studentPhone,
                        $studentEmail,
                        $studentAddress,
                        $providerName,
                        $providerPhone,
                        $providerEmail
                    ]);
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        try {
            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            \Log::error('Error en stream CSV: ' . $e->getMessage());
            // Fallback: generar CSV simple
            return $this->generateSimpleCSV($payments, $filename, $stats, $includeDetails);
        }
    }

    /**
     * Generate simple CSV as fallback
     */
    private function generateSimpleCSV($payments, $filename, $stats, $includeDetails)
    {
        $csvContent = "ID Pago,ID Suscripción,Estudiante,Colegio,Ruta,Proveedor,Período Inicio,Período Fin,Monto Total,Comisión Plataforma,Monto Proveedor,Método Pago,Estado,Fecha Pago,Fecha Creación\n";

        foreach ($payments as $payment) {
            $studentName = 'N/A';
            $schoolName = 'N/A';
            $routeName = 'N/A';
            $providerName = 'N/A';

            if ($payment->subscription && $payment->subscription->transportContract) {
                $contract = $payment->subscription->transportContract;

                if ($contract->student) {
                    $studentName = $contract->student->first_name . ' ' . $contract->student->last_name;
                }

                if ($contract->pickupRoute) {
                    $routeName = $contract->pickupRoute->route_name;

                    if ($contract->pickupRoute->provider) {
                        $providerName = $contract->pickupRoute->provider->provider_name;
                    }
                }

                if ($contract->student && $contract->student->school) {
                    $schoolName = $contract->student->school->school_name;
                }
            }

            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $payment->payment_id,
                $payment->subscription_id ?? 'N/A',
                '"' . $studentName . '"',
                '"' . $schoolName . '"',
                '"' . $routeName . '"',
                '"' . $providerName . '"',
                $payment->period_start ? $payment->period_start->format('d/m/Y') : 'N/A',
                $payment->period_end ? $payment->period_end->format('d/m/Y') : 'N/A',
                '$' . number_format($payment->amount_total, 2),
                '$' . number_format($payment->platform_fee, 2),
                '$' . number_format($payment->provider_amount, 2),
                ucfirst(str_replace('_', ' ', $payment->payment_method)),
                ucfirst($payment->payment_status),
                $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'N/A',
                $payment->created_at->format('d/m/Y H:i')
            );
        }

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Export to Excel format using Laravel Excel
     */
    private function exportToExcel($payments, $filename, $stats, $includeDetails)
    {
        try {
            return Excel::download(new \App\Exports\PaymentsExport($payments, $includeDetails), "{$filename}.xlsx");
        } catch (\Exception $e) {
            \Log::error('Error al exportar a Excel: ' . $e->getMessage());
            // Fallback a CSV si Excel falla
            return $this->exportToCSV($payments, $filename, $stats, $includeDetails);
        }
    }

    /**
     * Export to PDF format
     */
    private function exportToPDF($payments, $filename, $stats, $includeDetails)
    {
        // Para PDF necesitaríamos instalar un paquete como barryvdh/laravel-dompdf
        // Por ahora redirigimos a CSV
        return $this->exportToCSV($payments, $filename, $stats, $includeDetails);
    }
}

