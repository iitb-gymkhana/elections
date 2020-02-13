'use strict';

document.addEventListener('DOMContentLoaded', function () {
    // Modals
    var rootEl = document.documentElement;
    var $modals = getAll('.modal');
    var $modalButtons = getAll('.modal-button');
    var $modalCloses = getAll('.modal-background, .modal-close, .modal-card-head .delete, .modal-card-foot .button');

    if ($modalButtons.length > 0) {
        $modalButtons.forEach(function ($el) {
            $el.addEventListener('click', function () {
                var target = $el.dataset.target;
                var $target = document.getElementById(target);
                rootEl.classList.add('is-clipped');
                $target.classList.add('is-active');
            });
        });
    }

    if ($modalCloses.length > 0) {
        $modalCloses.forEach(function ($el) {
            $el.addEventListener('click', function () {
                closeModals();
            });
        });
    }

    document.addEventListener('keydown', function (event) {
        var e = event || window.event;
        if (e.keyCode === 27) {
            closeModals();
        }
    });

    function closeModals() {
        rootEl.classList.remove('is-clipped');
        $modals.forEach(function ($el) {
            $el.classList.remove('is-active');
        });
    }

    // Functions
    function getAll(selector) {
        return Array.prototype.slice.call(document.querySelectorAll(selector), 0);
    }
});

function logoutElectron(event) {
    if (window.ipc) {
        window.ipc.send('logout', []);
        if (event && event.preventDefault) event.preventDefault();
        if (event && event.stopPropagation) event.stopPropagation();
        return true;
    }

    return false;
}

function voterlistPost() {
    const elem = document.getElementById('vl-create-submit');
    elem.classList.add('is-loading');
    elem.classList.add('is-danger');
    elem.classList.remove('is-success');
    elem.classList.remove('is-outlined');
    elem.addEventListener('click', function(ev) {
        ev.preventDefault();
    });

    const iframe = document.getElementById('create-vl-result');
    iframe.style.display = 'block';
    iframe.addEventListener("load", function() {
        const elem = document.getElementById('vl-create-submit');
        elem.classList.remove('is-loading');
        elem.classList.remove('is-danger');
        elem.classList.add('is-success');

        elem.innerHTML = 'Continue'
        elem.addEventListener('click', function(ev) {
            ev.preventDefault();
            window.location = window.location.href;
        });
    });
}

// Check existing voter list
function checkEVL(event) {
    if (!voterLists) return;

    // Target name
    const name = event.target.value;

    // Hint element
    const elem = document.getElementById('evl-hint');
    const eid = document.getElementById('evl-eid');

    // Check all
    for (const vl of voterLists) {
        if (vl.name.toUpperCase() == name.toUpperCase()) {
            elem.classList.add('is-success');
            elem.innerHTML = 'Voters will be added to existing list';
            eid.value = vl.id;
            return;
        }
    }

    eid.value = '';
    elem.classList.remove('is-success');
    elem.innerHTML = 'Use an existing name to add more voters';
}

function printResult() {
    let resultSections = document.getElementsByClassName("result-section-pl");
    for (var i = 0; i < resultSections.length; i++) {
        if (document.getElementById('chk-explode').checked) {
            resultSections.item(i).classList.add('result-section');
        } else {
            resultSections.item(i).classList.remove('result-section');
        }
    }

    resultSections = document.getElementsByClassName("result-details-pl");
    for (var i = 0; i < resultSections.length; i++) {
        if (document.getElementById('chk-details').checked) {
            resultSections.item(i).classList.remove('result-details');
        } else {
            resultSections.item(i).classList.add('result-details');
        }
    }

    window.print();
}
