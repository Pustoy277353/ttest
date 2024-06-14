document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const category_id = urlParams.get('category_id');

    fetch(`server/src/fetch_houses.php?category_id=${category_id}`)
        .then(response => response.json())
        .then(data => {
            const container = document.querySelector('.container');
            container.innerHTML = '';

            console.log(data);

            data.forEach(house => {
                const houseElement = document.createElement('div');
                houseElement.classList.add('container-el');
                houseElement.style.textAlign = 'start';

                const descriptionWithBreaks = house.description.replace(/\r?\n/g, '<br>');

                const imageSrc = `data:image/jpeg;base64,${house.image}`;
                houseElement.innerHTML = `
                    <img src="${imageSrc}" alt="${house.title}" class="house-image" data-id="${house.id}">
                    <p>${house.title}</p>
                    <p>Цена: ${house.price}</p>
                    <p>${descriptionWithBreaks}</p>
                `;
                
                container.appendChild(houseElement);
            });
        });
});
