/**
 * Dashboard JavaScript - Funcionalidades comunes para todos los dashboards
 */

class Dashboard {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupCharts();
        this.setupRealTimeUpdates();
    }

    setupEventListeners() {
        // Event listeners para acciones rápidas
        document.querySelectorAll('[data-dashboard-action]').forEach(element => {
            element.addEventListener('click', (e) => {
                const action = e.target.dataset.dashboardAction;
                this.handleAction(action, e);
            });
        });

        // Event listeners para actualizaciones en tiempo real
        document.querySelectorAll('[data-auto-refresh]').forEach(element => {
            const interval = element.dataset.autoRefresh || 30000; // 30 segundos por defecto
            setInterval(() => {
                this.refreshElement(element);
            }, parseInt(interval));
        });
    }

    setupCharts() {
        // Configuración para gráficos (usando Chart.js si está disponible)
        if (typeof Chart !== 'undefined') {
            this.initializeCharts();
        }
    }

    setupRealTimeUpdates() {
        // Configurar actualizaciones en tiempo real
        if (this.shouldEnableRealTime()) {
            this.startRealTimeUpdates();
        }
    }

    handleAction(action, event) {
        switch (action) {
            case 'refresh':
                this.refreshDashboard();
                break;
            case 'export':
                this.exportData(event);
                break;
            case 'print':
                this.printDashboard();
                break;
            default:
                console.log('Acción no reconocida:', action);
        }
    }

    refreshDashboard() {
        // Mostrar indicador de carga
        this.showLoading();

        // Recargar la página
        window.location.reload();
    }

    refreshElement(element) {
        const url = element.dataset.refreshUrl;
        if (!url) return;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            this.updateElementContent(element, data);
        })
        .catch(error => {
            console.error('Error al actualizar elemento:', error);
        });
    }

    updateElementContent(element, data) {
        // Actualizar el contenido del elemento con los nuevos datos
        if (data.html) {
            element.innerHTML = data.html;
        } else if (data.content) {
            element.textContent = data.content;
        }
    }

    exportData(event) {
        const type = event.target.dataset.exportType || 'pdf';
        const section = event.target.dataset.exportSection || 'all';

        // Implementar lógica de exportación
        console.log(`Exportando ${section} como ${type}`);

        // Ejemplo: exportar como PDF
        if (type === 'pdf') {
            this.exportAsPDF(section);
        } else if (type === 'excel') {
            this.exportAsExcel(section);
        }
    }

    exportAsPDF(section) {
        // Implementar exportación a PDF
        console.log('Exportando como PDF:', section);
    }

    exportAsExcel(section) {
        // Implementar exportación a Excel
        console.log('Exportando como Excel:', section);
    }

    printDashboard() {
        window.print();
    }

    showLoading() {
        // Mostrar indicador de carga
        const loading = document.createElement('div');
        loading.id = 'dashboard-loading';
        loading.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        loading.innerHTML = `
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-2 text-gray-600">Actualizando dashboard...</p>
            </div>
        `;
        document.body.appendChild(loading);
    }

    hideLoading() {
        const loading = document.getElementById('dashboard-loading');
        if (loading) {
            loading.remove();
        }
    }

    shouldEnableRealTime() {
        // Verificar si se deben habilitar actualizaciones en tiempo real
        return document.body.dataset.realTime === 'true';
    }

    startRealTimeUpdates() {
        // Configurar actualizaciones en tiempo real
        setInterval(() => {
            this.updateRealTimeData();
        }, 60000); // Actualizar cada minuto
    }

    updateRealTimeData() {
        // Actualizar datos en tiempo real
        const dashboardType = document.body.dataset.dashboardType;
        if (!dashboardType) return;

        fetch(`/api/v1/${dashboardType}/dashboard`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            this.updateRealTimeStats(data);
        })
        .catch(error => {
            console.error('Error al actualizar datos en tiempo real:', error);
        });
    }

    updateRealTimeStats(data) {
        // Actualizar estadísticas en tiempo real
        if (data.data && data.data.stats) {
            Object.keys(data.data.stats).forEach(key => {
                const element = document.querySelector(`[data-stat="${key}"]`);
                if (element) {
                    element.textContent = this.formatStatValue(data.data.stats[key]);
                }
            });
        }
    }

    formatStatValue(value) {
        // Formatear valores de estadísticas
        if (typeof value === 'number') {
            if (value >= 1000000) {
                return (value / 1000000).toFixed(1) + 'M';
            } else if (value >= 1000) {
                return (value / 1000).toFixed(1) + 'K';
            }
            return value.toLocaleString();
        }
        return value;
    }

    initializeCharts() {
        // Inicializar gráficos si están disponibles
        const chartElements = document.querySelectorAll('[data-chart]');
        chartElements.forEach(element => {
            const chartType = element.dataset.chart;
            const chartData = JSON.parse(element.dataset.chartData || '{}');
            this.createChart(element, chartType, chartData);
        });
    }

    createChart(element, type, data) {
        const ctx = element.getContext('2d');

        const config = {
            type: type,
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        };

        new Chart(ctx, config);
    }

    // Métodos de utilidad
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            type === 'warning' ? 'bg-yellow-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP'
        }).format(amount);
    }

    formatDate(date) {
        return new Intl.DateTimeFormat('es-CO', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(new Date(date));
    }
}

// Inicializar dashboard cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.dashboard = new Dashboard();
});

// Exportar para uso en otros módulos
export default Dashboard;
