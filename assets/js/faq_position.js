// FAQ INDEX POSITION SCRIPT
const $ = require('jquery');

function movePosition(event) {
    event.preventDefault();
    const move = event.target;
    const link = move.getAttribute('data-href');
    fetch(link);
    return false;
}

$('.faq-btn-up').click(function clickUp() {
    $(this).parents('.faq-tr').insertBefore($(this).parents('.faq-tr').prev());
});

$('.faq-btn-down').click(function clickDown() {
    $(this).parents('.faq-tr').insertAfter($(this).parents('.faq-tr').next());
});

$(document).ready(() => {
    const goUp = document.querySelectorAll('.faq-btn-down');
    goUp.forEach((child) => {
        child.addEventListener('click', movePosition);
    });
    const goDown = document.querySelectorAll('.faq-btn-up');
    goDown.forEach((child) => {
        child.addEventListener('click', movePosition);
    });
});
