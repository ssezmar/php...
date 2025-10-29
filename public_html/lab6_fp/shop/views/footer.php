    </div>

    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container text-center">
            <p>&copy; 2025 Магазин. Все права защищены.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function calculateTotal() {
            const productSelect = document.getElementById('product_id');
            const quantityInput = document.getElementById('quantity');
            const totalInput = document.getElementById('total_amount');
            
            if (productSelect && quantityInput && totalInput) {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                const quantity = quantityInput.value;
                
                if (price && quantity) {
                    totalInput.value = (parseFloat(price) * parseInt(quantity)).toFixed(2);
                }
            }
        }

        // Инициализация расчета общей суммы при загрузке
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotal();
        });
    </script>
</body>
</html>