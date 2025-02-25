'use strict';

window.addEventListener('load', function () {
    document.getElementById('save-params-button').addEventListener('click', function () {
        BX.adjust(BX('work-info'), {html: ''});
        const requestedPage = document.getElementById('requested-page').value.trim();
        const waitSpinner = BX.showWait('work-info-spinner');
        prepareWork(requestedPage, waitSpinner);
    });
});

function prepareWork(url, waitSpinner) {
    let techappealToaddressNewElement = null;
    let techappealToaddressValues = [];
    document.querySelectorAll('.techappeal_toaddress').forEach(function (element) {
        let elementValue = '';
        if (typeof element.value === 'string') {
            if (techappealToaddressNewElement === null) {
                techappealToaddressNewElement = element.cloneNode();
                techappealToaddressNewElement.value = '';
                element.parentElement.appendChild(techappealToaddressNewElement);
            }

            elementValue = element.value.trim();
            if (elementValue) {
                techappealToaddressValues.push(elementValue);
            } else {
                element.parentElement.removeChild(element);
            }
        }
    });

    let techappealSubjectNewElement = null;
    let techappealSubjectValues = [];
    document.querySelectorAll('.techappeal_subject').forEach(function (element) {
        let elementValue = '';
        if (typeof element.value === 'string') {
            if (techappealSubjectNewElement === null) {
                techappealSubjectNewElement = element.cloneNode();
                techappealSubjectNewElement.value = '';
                element.parentElement.appendChild(techappealSubjectNewElement);
            }

            elementValue = element.value.trim();
            if (elementValue) {
                techappealSubjectValues.push(elementValue);
            } else {
                element.parentElement.removeChild(element);
            }
        }
    });

    const params = {
        techappealToaddress: techappealToaddressValues,
        techappealSubject: techappealSubjectValues
    }

    saveParams(url, params, waitSpinner);
}

function saveParams(url, params, waitSpinner) {
    fetch(`${url}?action=saveparams`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(params)
    }).then(
        response => response.json()
    ).then(
        (data) => {
            if (data.result) {
                if (data.result === 'fail') {
                    showMessage(url, 'ERROR', 'DIGITMIND_MULTIOPTIONS_SAVEPARAMS_ERROR', {}, 'work-info');
                    BX.closeWait('work-info-spinner', waitSpinner);
                } else {
                    doWork(url, params, waitSpinner);
                }
            } else {
                BX.closeWait('work-info-spinner', waitSpinner);
            }
        }
    ).catch(
        (error) => {
            // console.error(error);
            BX.closeWait('work-info-spinner', waitSpinner);
        }
    );
}

function doWork(url, params, waitSpinner) {
    showMessage(url, 'OK', 'DIGITMIND_MULTIOPTIONS_DOWORK_SUCCESS', {}, 'work-info');
    BX.closeWait('work-info-spinner', waitSpinner);
}
