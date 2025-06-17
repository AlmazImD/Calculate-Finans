document.addEventListener('DOMContentLoaded', function() {
    // Инициализация фильтров на dashboard
    if (document.getElementById('filterForm')) {
        document.getElementById('filterForm').addEventListener('change', function() {
            this.submit();
        });
    }
    
    // Установка текущей даты в форме добавления транзакции
    if (document.getElementById('date')) {
        document.getElementById('date').valueAsDate = new Date();
    }
    
    // Динамическое обновление категорий при изменении типа
    if (document.getElementById('type') && document.getElementById('category')) {
        const typeSelect = document.getElementById('type');
        const categorySelect = document.getElementById('category');
        
        typeSelect.addEventListener('change', function() {
            updateCategories(this.value, categorySelect);
        });
        
        // Инициализация при загрузке
        if (typeSelect.value) {
            updateCategories(typeSelect.value, categorySelect);
        }
    }
});

function updateCategories(type, categorySelect) {
    // Категории могут быть загружены из data-атрибутов или через AJAX
    const categories = {
        income: ['Зарплата', 'Фриланс', 'Инвестиции', 'Подарки', 'Другое'],
        expense: ['Еда', 'Транспорт', 'Жилье', 'Развлечения', 'Одежда', 'Здоровье', 'Образование', 'Другое']
    };
    
    categorySelect.innerHTML = '<option value="">Выберите категорию</option>';
    
    if (type && categories[type]) {
        categories[type].forEach(category => {
            const option = document.createElement('option');
            option.value = category;
            option.textContent = category;
            categorySelect.appendChild(option);
        });
    }
}