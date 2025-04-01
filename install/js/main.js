'use strict';

function scrollToElement(elementId) {
    let infoElement = document.getElementById(elementId);
    window.scrollTo(infoElement.offsetLeft, infoElement.offsetTop);
}
