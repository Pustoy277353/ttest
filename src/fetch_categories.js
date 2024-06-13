document.addEventListener('DOMContentLoaded', function () {
    fetch('src/fetch_categories.php')
        .then(response => response.json())
        .then(data => {
            const container = document.querySelector('.container');
            container.innerHTML = '';

            data.forEach(category => {
                const categoryElement = document.createElement('div');
                categoryElement.classList.add('container-el');
                const imageSrc = `data:image/jpeg;base64,${category.image}`;
                categoryElement.innerHTML = `
                    <a href="src/houses.html?category_id=${category.id}">
                        <img src="${imageSrc}" alt="${category.name}">
                        <p>${category.name}</p>
                    </a>
                `;
                container.appendChild(categoryElement);
            });
        });
});
