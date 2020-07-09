// FAQ INDEX POSITION SCRIPT
const $ = require('jquery');

function movePosition(event) {
    event.preventDefault();
    const move = event.target;
    const link = move.getAttribute('data-href');
    fetch(link);
    return false;
}

$('.question-btn-up').click(function clickUp() {
    $(this).parents('.question-tr').insertBefore($(this).parents('.question-tr').prev());
});

$('.question-btn-down').click(function clickDown() {
    $(this).parents('.question-tr').insertAfter($(this).parents('.question-tr').next());
});

$(document).ready(() => {
    const goUp = document.querySelectorAll('.question-btn-down');
    goUp.forEach((child) => {
        child.addEventListener('click', movePosition);
    });
    const goDown = document.querySelectorAll('.question-btn-up');
    goDown.forEach((child) => {
        child.addEventListener('click', movePosition);
    });
});
