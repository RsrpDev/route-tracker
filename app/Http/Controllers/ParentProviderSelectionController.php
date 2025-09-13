<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Route;
use App\Models\StudentTransportContract;
use App\Models\Subscription;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentProviderSelectionController extends Controller
{
    /**
     * Mostrar opciones de conductores disponibles para un estudiante específico
     */
    public function index(Request $request)
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            abort(403, 'No tienes un perfil de padre asociado.');
        }

        // Obtener estudiantes del padre
        $students = Student::where('parent_id', $parent->parent_id)->get();

        if ($students->isEmpty()) {
            return redirect()->route('parent.dashboard')
                ->with('warning', 'No tienes estudiantes registrados.');
        }

        // Si no se especifica estudiante, mostrar lista de estudiantes
        if (!$request->has('student_id')) {
            return view('parent.provider-selection.select-student', compact('students'));
        }

        // Obtener el estudiante específico
        $student = $students->find($request->student_id);
        if (!$student) {
            abort(404, 'Estudiante no encontrado.');
        }

        // Obtener conductores que tienen rutas para la escuela del estudiante
        $drivers = \App\Models\Driver::whereHas('routeAssignments.route', function($query) use ($student) {
                $query->where('school_id', $student->school_id)
                      ->where('active_flag', true);
            })
            ->with(['routeAssignments.route' => function($query) use ($student) {
                $query->where('school_id', $student->school_id)
                      ->where('active_flag', true);
            }, 'provider'])
            ->where('driver_status', 'active')
            ->get();

        // Aplicar filtros
        if ($request->filled('max_price')) {
            $maxPrice = $request->max_price;
            $drivers = $drivers->filter(function($driver) use ($maxPrice) {
                return $driver->routeAssignments->pluck('route')->min('monthly_price') <= $maxPrice;
            });
        }

        // Obtener tipos de proveedores para filtros (basado en los conductores encontrados)
        $providerTypes = $drivers->pluck('provider.provider_type')
            ->unique()
            ->map(function($type) {
                return [
                    'value' => $type,
                    'label' => ucfirst(str_replace('_', ' ', $type))
                ];
            });

        // Obtener escuelas para filtros
        $schools = Student::where('parent_id', $parent->parent_id)
            ->with('school')
            ->get()
            ->pluck('school')
            ->filter()
            ->unique('school_id');

        return view('parent.provider-selection.index', compact(
            'drivers',
            'student',
            'students',
            'providerTypes',
            'schools'
        ));
    }

    /**
     * Mostrar detalles de un proveedor específico
     */
    public function show(Provider $provider)
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            abort(403, 'No tienes un perfil de padre asociado.');
        }

        // Obtener estudiantes del padre
        $students = Student::where('parent_id', $parent->parent_id)->get();

        // Obtener rutas activas del proveedor
        $routes = $provider->routes()->where('active_flag', true)->get();

        // Obtener planes de pago disponibles (simulados por ahora)
        $paymentPlans = $this->getAvailablePaymentPlans($provider);

        // Obtener estadísticas del proveedor
        $stats = [
            'total_routes' => $routes->count(),
            'total_students' => StudentTransportContract::where('provider_id', $provider->provider_id)
                ->where('contract_status', 'active')
                ->count(),
            'average_rating' => 4.5, // Simulado por ahora
            'years_experience' => $provider->created_at->diffInYears(now()),
        ];

        return view('parent.provider-selection.show', compact(
            'provider',
            'routes',
            'students',
            'paymentPlans',
            'stats'
        ));
    }

    /**
     * Mostrar formulario para crear contrato con un proveedor
     */
    public function createContract(Request $request, Provider $provider)
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            abort(403, 'No tienes un perfil de padre asociado.');
        }

        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'route_id' => 'required|exists:routes,route_id',
            'payment_plan_type' => 'required|in:monthly,quarterly,annual,postpaid',
        ]);

        $student = Student::findOrFail($request->student_id);
        $route = Route::findOrFail($request->route_id);

        // Verificar que el estudiante pertenece al padre
        if ($student->parent_id !== $parent->parent_id) {
            abort(403, 'No tienes permisos para este estudiante.');
        }

        // Verificar que la ruta pertenece al proveedor
        if ($route->provider_id !== $provider->provider_id) {
            abort(403, 'La ruta no pertenece a este proveedor.');
        }

        // Verificar que el estudiante no tenga ya un contrato activo
        $existingContract = StudentTransportContract::where('student_id', $student->student_id)
            ->where('contract_status', 'active')
            ->first();

        if ($existingContract) {
            return redirect()->back()
                ->with('error', 'El estudiante ya tiene un contrato de transporte activo.');
        }

        // Obtener información del plan de pago
        $paymentPlan = $this->getPaymentPlanDetails($request->payment_plan_type, $route->monthly_price);

        return view('parent.provider-selection.create-contract', compact(
            'provider',
            'student',
            'route',
            'paymentPlan'
        ));
    }

    /**
     * Procesar la creación del contrato
     */
    public function storeContract(Request $request, Provider $provider)
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            abort(403, 'No tienes un perfil de padre asociado.');
        }

        $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'route_id' => 'required|exists:routes,route_id',
            'payment_plan_type' => 'required|in:monthly,quarterly,annual,postpaid',
            'special_instructions' => 'nullable|string|max:500',
            'agree_terms' => 'required|accepted',
        ]);

        $student = Student::findOrFail($request->student_id);
        $route = Route::findOrFail($request->route_id);

        // Verificar permisos
        if ($student->parent_id !== $parent->parent_id) {
            abort(403, 'No tienes permisos para este estudiante.');
        }

        if ($route->provider_id !== $provider->provider_id) {
            abort(403, 'La ruta no pertenece a este proveedor.');
        }

        // Verificar que no exista contrato activo
        $existingContract = StudentTransportContract::where('student_id', $student->student_id)
            ->where('contract_status', 'active')
            ->first();

        if ($existingContract) {
            return redirect()->back()
                ->with('error', 'El estudiante ya tiene un contrato de transporte activo.');
        }

        // Crear el contrato de transporte
        $contract = StudentTransportContract::create([
            'student_id' => $student->student_id,
            'provider_id' => $provider->provider_id,
            'pickup_route_id' => $route->route_id,
            'dropoff_route_id' => $route->route_id, // Misma ruta para ida y vuelta
            'contract_start_date' => now(),
            'contract_end_date' => now()->addYear(), // Contrato por 1 año
            'contract_status' => 'pending', // Pendiente hasta confirmación del proveedor
            'monthly_fee' => $route->monthly_price,
            'special_instructions' => $request->special_instructions,
        ]);

        // Crear la suscripción con el plan de pago seleccionado
        $paymentPlan = $this->getPaymentPlanDetails($request->payment_plan_type, $route->monthly_price);

        $subscription = Subscription::create([
            'enrollment_id' => $contract->contract_id,
            'billing_cycle' => $paymentPlan['billing_cycle'],
            'price_snapshot' => $paymentPlan['price'],
            'platform_fee_rate' => 5.00,
            'next_billing_date' => $paymentPlan['next_billing_date'],
            'subscription_status' => 'pending',
            'payment_plan_type' => $request->payment_plan_type,
            'payment_plan_name' => $paymentPlan['name'],
            'payment_plan_description' => $paymentPlan['description'],
            'discount_rate' => $paymentPlan['discount_rate'],
            'auto_renewal' => true,
            'plan_start_date' => now(),
            'plan_end_date' => now()->addYear(),
            'payment_method' => 'pse', // Por defecto PSE
            'is_active' => false, // Inactivo hasta confirmación
        ]);

        return redirect()->route('parent.provider-selection.show', $provider)
            ->with('success', 'Contrato creado exitosamente. El proveedor revisará tu solicitud.');
    }

    /**
     * Obtener planes de pago disponibles
     */
    private function getAvailablePaymentPlans(Provider $provider)
    {
        $basePrice = $provider->routes->min('monthly_price') ?: 150000;

        return [
            'monthly' => [
                'name' => 'Plan Mensual',
                'description' => 'Pago mensual recurrente',
                'price' => $basePrice,
                'discount_rate' => 0,
                'billing_cycle' => 'monthly',
                'next_billing_date' => now()->addMonth(),
                'features' => ['Flexibilidad total', 'Sin compromiso a largo plazo']
            ],
            'quarterly' => [
                'name' => 'Plan Trimestral',
                'description' => 'Pago cada 3 meses con descuento',
                'price' => $basePrice * 3,
                'discount_rate' => 5,
                'billing_cycle' => 'quarterly',
                'next_billing_date' => now()->addMonths(3),
                'features' => ['5% de descuento', 'Menos gestiones de pago']
            ],
            'annual' => [
                'name' => 'Plan Anual',
                'description' => 'Pago anual con mayor descuento',
                'price' => $basePrice * 12,
                'discount_rate' => 15,
                'billing_cycle' => 'annual',
                'next_billing_date' => now()->addYear(),
                'features' => ['15% de descuento', 'Mejor precio', 'Sin preocupaciones por pagos']
            ],
            'postpaid' => [
                'name' => 'Plan Pospago',
                'description' => 'Pago posterior al uso (como plan de datos)',
                'price' => $basePrice,
                'discount_rate' => 0,
                'billing_cycle' => 'monthly',
                'next_billing_date' => now()->addMonth(),
                'features' => ['Pago después del servicio', 'Flexibilidad máxima']
            ]
        ];
    }

    /**
     * Obtener detalles de un plan de pago específico
     */
    private function getPaymentPlanDetails(string $planType, float $basePrice): array
    {
        $plans = $this->getAvailablePaymentPlans(new Provider());
        $plan = $plans[$planType];

        // Ajustar precio según la ruta específica
        $plan['price'] = $basePrice * ($planType === 'quarterly' ? 3 : ($planType === 'annual' ? 12 : 1));

        return $plan;
    }
}
