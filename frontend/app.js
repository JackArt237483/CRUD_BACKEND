document.addEventListener('DOMContentLoaded', function() {
    const productForm = document.getElementById('productForm');
    const productList = document.getElementById('productList');

    // Загрузка данных
    function loadProducts() {
        fetch('http://localhost/sites/site.loc/backend/read.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                productList.innerHTML = '';
                if (Array.isArray(data)) {
                    data.forEach(product => {
                        const card = document.createElement('div');
                        card.className = 'product-card';
                        card.innerHTML = `
                            <h3>${product.name}</h3>
                            <p>Количество: ${product.quantity}</p>
                            <p>Год создания: ${product.year}</p>
                            <p>Описание: ${product.description}</p>
                            <div class="actions">
                                <button onclick="editProduct(${product.id})">Изменить</button>
                                <button onclick="deleteProduct(${product.id})">Удалить</button>
                            </div>
                        `;
                        productList.appendChild(card);
                    });
                } else {
                    console.error('Ошибка при загрузке продуктов:', data.error);
                }
            })
            .catch(error => {
                console.error('Ошибка при загрузке продуктов:', error);
            });
    }

    // Добавление/Обновление продукта
    productForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(productForm);
        const data = {};
        formData.forEach((value, key) => {
            if (key === 'productName') {
                data['name'] = value;
            } else if (key === 'creationYear') {
                data['year'] = value;
            } else {
                data[key] = value;
            }
        });

        if (!data.productId) {
            delete data.productId;
        }

        const method = data.productId ? 'PUT' : 'POST';
        const url = data.productId
            ? `http://localhost/sites/site.loc/backend/update.php?id=${data.productId}`
            : 'http://localhost/sites/site.loc/backend/create.php';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(result => {
                console.log('Ответ сервера:', result);
                productForm.reset();
                loadProducts();
            })
            .catch(error => {
                console.error('Ошибка при добавлении/обновлении продукта:', error);
            });
    });

    // Удаление продукта
    window.deleteProduct = function(id) {
        fetch(`http://localhost/sites/site.loc/backend/delete.php?id=${id}`, {
            method: 'DELETE'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(result => {
                if (result.deleted) {
                    loadProducts();
                } else {
                    console.error('Ошибка при удалении продукта:', result.error);
                }
            })
            .catch(error => {
                console.error('Ошибка при удалении продукта:', error);
            });
    };

    // Редактирование продукта
    window.editProduct = function(id) {
        fetch(`http://localhost/sites/site.loc/backend/read.php?id=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('productId').value = data.id;
                document.getElementById('productName').value = data.name;
                document.getElementById('quantity').value = data.quantity;
                document.getElementById('creationYear').value = data.year;
                document.getElementById('description').value = data.description;
            })
            .catch(error => {
                console.error('Ошибка при редактировании продукта:', error);
            });
    };

    // Загрузка продуктов при загрузке страницы
    loadProducts();
});
