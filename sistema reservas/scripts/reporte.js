        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleFilters');
            const filtersPanel = document.getElementById('filtersPanel');
            const resetBtn = document.getElementById('resetFilters');
            
            // Toggle del panel de filtros
            toggleBtn.addEventListener('click', function() {
                filtersPanel.classList.toggle('show');
            });
            
            // Resetear filtros
            resetBtn.addEventListener('click', function() {
                const form = this.closest('form');
                form.reset();
            });
            
            // Cerrar el panel al hacer clic fuera
            document.addEventListener('click', function(event) {
                if (!filtersPanel.contains(event.target) && event.target !== toggleBtn) {
                    filtersPanel.classList.remove('show');
                }
            });
        });
        
