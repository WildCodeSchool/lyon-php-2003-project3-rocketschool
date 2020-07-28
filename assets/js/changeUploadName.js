let imgPath = '';
let imgName = '';

if (document.getElementById('faq_image')) {
    const imgDiv = document.getElementById('faq_image');

    document.querySelector('#faq_image').addEventListener('change', () => {
        imgPath = imgDiv.value.split('\\');
        [imgName] = imgPath;
        imgName = imgPath['2'];
        document.querySelector('.custom-file-label').innerHTML = imgName;
    });
}

if (document.getElementById('pdf_path')) {
    const imgDiv = document.getElementById('pdf_path');

    document.querySelector('#pdf_path').addEventListener('change', () => {
        imgPath = imgDiv.value.split('\\');
        [imgName] = imgPath;
        imgName = imgPath['2'];
        document.querySelector('.custom-file-label').innerHTML = imgName;
    });
}
