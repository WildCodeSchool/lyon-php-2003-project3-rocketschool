const imgPath = document.getElementById('faq_image');
var imgName = "";

document.querySelector('#faq_image').addEventListener('change', () => {
    imgName = imgPath.value.split("\\");
    document.querySelector('.custom-file-label').innerHTML = imgName[2];
});