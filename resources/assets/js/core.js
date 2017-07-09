"use strict";

export class http {
    static get(uri) {
        return http._getFetch(uri);
    }

    static post(uri, body, hasUpload = false) {
        if (hasUpload) {
            return http._getUploadFetch(uri, body, 'POST', hasUpload);
        }

        body._method = 'POST';

        return http._getFetch(uri, body, 'POST');
    }

    static put(uri, body, hasUpload = false) {
        if (hasUpload) {
            return http._getUploadFetch(uri, body, 'POST', hasUpload);
        }

        body._method = 'PUT';

        return http._getFetch(uri, body, 'POST');
    }

    static destroy(uri) {
        return http._getFetch(uri, {
            _method: 'DELETE'
        }, 'POST');
    }

    static uploadFile(formElement, uri) {
        let input = document.querySelector(formElement);
        let data = new FormData();
        data.append('uploaded_file', input.files[0]);
        data.append('_token', window.Laravel.csrfToken);
        return fetch(uri, {
            method: 'POST',
            credentials: 'same-origin',
            body: data,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
            }
        }).then(response => {
            if (response.ok) {
                return response.json();
            }

            if (response.status == 401) {
                logout();
                return false;
            }

            return response.json().then((err) => {
                let errors = flatten(Object.values(err));

                throw new Error(JSON.stringify(errors));
            });
        });
    }

    static _getUploadFetch(uri, body = null, method = 'GET', hasUpload = false) {
        let options = {
            method: method,
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
            },
        };

        if (body) {
            if (! hasUpload) {
                body._token = window.Laravel.csrfToken;
                body = JSON.stringify(body);
                options.headers['Content-Type'] = 'application/json';
            }

            options.body = body;
        }

        return fetch(uri, options)
            .then(response => {
                if (response.ok) {
                    return response.json();
                }

                if (response.status == 401) {
                    logout();
                    return false;
                }

                return response.json().then((err) => {
                    let errors = flatten(Object.values(err));

                    throw new Error(JSON.stringify(errors));
                });
            })
    }

    static _getFetch(uri, body = null, method = 'GET') {
        let options = {
            method: method,
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
            },
        };

        if (body) {
            body._token = window.Laravel.csrfToken;
            options.body = JSON.stringify(body);
        }

        return fetch(uri, options)
            .then(response => {
                if (response.ok) {
                    return response.json();
                }

                if (response.status == 401) {
                    logout();
                    return false;
                }

                return response.json().then((err) => {
                    let errors = flatten(Object.values(err));

                    throw new Error(JSON.stringify(errors));
                });
            })
    }
}

function logout() {
    localStorage.removeItem('foeiwafwfuwe');
    localStorage.removeItem('fewuia32rfwe');
    http.post('/logout', {}).then(() => window.location = '/login');
}

export function showAlert(root, errors, level = 'info') {
    root.errors = errors;
    root.showAlert = true;
    root.level = level;

    setTimeout(() => {
        root.showAlert = false;
    }, 5000)
}

function flatten(arr) {
    return arr.reduce((acc, val) => acc.concat(Array.isArray(val) ? flatten(val) : val), []);
}

export function prepareTable() {
    setTimeout(() => {
        $('table').dataTable({
            "lengthMenu": [ [-1, 10, 25, 50, 100, 200], ["All", 10, 25, 50, 100, 200] ],
            dom: '<".pull-right"f>l<".pull-right"B>rtip',
            buttons: [
                'colvis',
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });
    }, 100);
}

export function formatDate(value) {
    value = new Date(value);

    let monthNames = [
        "January", "February", "March",
        "April", "May", "June", "July",
        "August", "September", "October",
        "November", "December"
    ];

    let monthIndex = value.getMonth();

    return value.getDate() + ' ' + monthNames[monthIndex] + ' ' + value.getFullYear();
}

export function confirmPopup(selector, success, cancel = () => {}, destroy = false) {
    if (destroy) {
        $(selector).popover('destroy');
        return;
    }
    let template = `
    <div class="confirmation-buttons text-center">
        <div class="btn-group" style="width: 90px;">
            <button class="btn btn-xs btn-success confirm-accept pull-left">
                <i class="glyphicon glyphicon-ok"></i> Yes
            </button>
            <button class="btn btn-xs btn-danger confirm-dismiss pull-right">
                <i class="glyphicon glyphicon-remove"></i> No
            </button>
        </div>
    </div>
    `;

    setTimeout(() => {
        $(selector).popover({
            content: template,
            html: true,
            placement: 'bottom',
            title: 'Delete?',
            trigger: 'focus'
        })
            .on('shown.bs.popover', function () {
                let trigger = this;
                $('.confirm-accept').off().on('click', function () {
                    success(trigger);
                });

                $('.confirm-dismiss').off().on('click', () => {
                    cancel(trigger);
                });
            });
    }, 1000);
}

export function hasPermission(permission) {
    let user = localStorage.getItem('fewuia32rfwe');
    if (! user) { return false; }

    user = JSON.parse(user);
    let permissions = JSON.parse(user.permissions);

    if (permissions.length > 0 && permissions[0] == '*') {
        return true;
    }

    return permissions.indexOf(permission) !== -1;
}

export function mapToFormData(main, addons, isPUT = false) {
    let data = new FormData();

    data.append('_token', window.Laravel.csrfToken);

    for (let key in main) {
        data.append(key, main[key]);
    }

    addons.forEach((value) => {
        let key = Object.keys(value)[0];
        let input = document.querySelector(value[key]);

        if (input.files[0]) {
            data.append(key, input.files[0]);
        }
    });

    data.append('_method', 'POST');

    if (isPUT) {
        data.set('_method', 'PUT');
    }

    return data;
}
