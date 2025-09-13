@extends('layouts.app')

@section('title', 'Logs de Rutas - Conductor')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Logs de Rutas</h1>
                    <p class="text-gray-600">Registro de actividades y seguimiento de rutas</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('driver.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Filtros -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Filtros</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha</label>
                        <input type="date" id="dateFilter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de Actividad</label>
                        <select id="activityFilter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            <option value="start">Inicio</option>
                            <option value="pickup">Recogida</option>
                            <option value="dropoff">Entrega</option>
                            <option value="end">Fin</option>
                            <option value="incident">Incidente</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select id="statusFilter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            <option value="on_time">A tiempo</option>
                            <option value="early">Temprano</option>
                            <option value="late">Tarde</option>
                            <option value="delayed">Retrasado</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button onclick="applyFilters()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                            </svg>
                            Filtrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Rutas Completadas</p>
                            <p class="text-2xl font-bold text-gray-900" id="completedRoutes">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">A Tiempo</p>
                            <p class="text-2xl font-bold text-gray-900" id="onTimeRoutes">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Con Retraso</p>
                            <p class="text-2xl font-bold text-gray-900" id="delayedRoutes">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Incidentes</p>
                            <p class="text-2xl font-bold text-gray-900" id="incidents">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Logs -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Registro de Actividades</h3>
            </div>
            <div class="p-6">
                <div id="logsContainer">
                    <div class="text-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Cargando logs...</h3>
                        <p class="mt-1 text-sm text-gray-500">Obteniendo información de rutas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cargar logs al inicializar la página
document.addEventListener('DOMContentLoaded', function() {
    loadTodayLogs();
});

function loadTodayLogs() {
    fetch('{{ route("driver.logs.today") }}')
        .then(response => response.json())
        .then(data => {
            displayLogs(data.logs);
            updateStatistics(data.logs);
        })
        .catch(error => {
            console.error('Error loading logs:', error);
            document.getElementById('logsContainer').innerHTML = `
                <div class="text-center py-6">
                    <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Error al cargar logs</h3>
                    <p class="mt-1 text-sm text-gray-500">No se pudieron obtener los logs de rutas.</p>
                </div>
            `;
        });
}

function displayLogs(logs) {
    if (logs.length === 0) {
        document.getElementById('logsContainer').innerHTML = `
            <div class="text-center py-6">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay logs disponibles</h3>
                <p class="mt-1 text-sm text-gray-500">No se encontraron registros de actividades para hoy.</p>
            </div>
        `;
        return;
    }

    let html = '<div class="space-y-4">';

    logs.forEach(log => {
        const statusColor = getStatusColor(log.status);
        const activityIcon = getActivityIcon(log.activity_type);
        const time = new Date(log.actual_time).toLocaleTimeString('es-CO', {
            hour: '2-digit',
            minute: '2-digit'
        });

        html += `
            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 ${activityIcon.bg} rounded-full flex items-center justify-center">
                                ${activityIcon.svg}
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">${log.activity_description}</h4>
                            <p class="text-sm text-gray-500">${log.route.route_name}</p>
                            <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    ${time}
                                </span>
                                ${log.students_picked_up > 0 ? `<span>Recogidos: ${log.students_picked_up}</span>` : ''}
                                ${log.students_dropped_off > 0 ? `<span>Entregados: ${log.students_dropped_off}</span>` : ''}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusColor}">
                            ${getStatusText(log.status)}
                        </span>
                        ${log.delay_minutes > 0 ? `<span class="text-xs text-gray-500">+${log.delay_minutes}min</span>` : ''}
                    </div>
                </div>
                ${log.observations ? `
                    <div class="mt-3 text-sm text-gray-600 bg-gray-50 p-3 rounded-md">
                        <strong>Observaciones:</strong> ${log.observations}
                    </div>
                ` : ''}
            </div>
        `;
    });

    html += '</div>';
    document.getElementById('logsContainer').innerHTML = html;
}

function updateStatistics(logs) {
    const completed = logs.filter(log => log.activity_type === 'end').length;
    const onTime = logs.filter(log => log.status === 'on_time').length;
    const delayed = logs.filter(log => log.status === 'late' || log.status === 'delayed').length;
    const incidents = logs.filter(log => log.activity_type === 'incident').length;

    document.getElementById('completedRoutes').textContent = completed;
    document.getElementById('onTimeRoutes').textContent = onTime;
    document.getElementById('delayedRoutes').textContent = delayed;
    document.getElementById('incidents').textContent = incidents;
}

function getStatusColor(status) {
    const colors = {
        'on_time': 'bg-green-100 text-green-800',
        'early': 'bg-blue-100 text-blue-800',
        'late': 'bg-yellow-100 text-yellow-800',
        'delayed': 'bg-red-100 text-red-800',
        'cancelled': 'bg-gray-100 text-gray-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

function getStatusText(status) {
    const texts = {
        'on_time': 'A tiempo',
        'early': 'Temprano',
        'late': 'Tarde',
        'delayed': 'Retrasado',
        'cancelled': 'Cancelado'
    };
    return texts[status] || 'Desconocido';
}

function getActivityIcon(activityType) {
    const icons = {
        'start': {
            bg: 'bg-green-100',
            svg: '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m-6-8h8a2 2 0 012 2v8a2 2 0 01-2 2H8a2 2 0 01-2-2V6a2 2 0 012-2z"></path></svg>'
        },
        'pickup': {
            bg: 'bg-blue-100',
            svg: '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>'
        },
        'dropoff': {
            bg: 'bg-purple-100',
            svg: '<svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>'
        },
        'end': {
            bg: 'bg-red-100',
            svg: '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>'
        },
        'incident': {
            bg: 'bg-yellow-100',
            svg: '<svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>'
        }
    };
    return icons[activityType] || icons['start'];
}

function applyFilters() {
    // Implementar filtros en futuras versiones
    console.log('Aplicando filtros...');
}
</script>
@endsection




