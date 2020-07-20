const imgDiv = document.getElementById('faq_image');
var imgName = '';

document.querySelector('#faq_image').addEventListener('change', () => {
    imgPath = imgDiv.value.split('\\');
    imgName = imgPath[2];
    document.querySelector('.custom-file-label').innerHTML = imgName;
});
