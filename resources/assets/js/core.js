"use strict";

export class http {
    static get(uri) {
        return http._getFetch(uri);
    }

    static post(uri, body) {
        body._method = 'POST';

        return http._getFetch(uri, body, 'POST');
    }

    static put(uri, body) {
        body._method = 'PUT';

        return http._getFetch(uri, body, 'POST');
    }

    static destroy(uri) {
        return http._getFetch(uri, {
            _method: 'DELETE'
        }, 'POST');
    }

    static _getFetch(uri, body = null, method = 'GET') {
        let options = {
            method: method,
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
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

                return response.json().then((err) => {
                    let errors = flatten(Object.values(err));

                    throw new Error(JSON.stringify(errors));
                });
            })
    }
}

export function showAlert(root, errors, level = 'info') {
    root.errors = errors;
    root.showAlert = true;
    root.level = level;

    setTimeout(() => {
        root.showAlert = false;
    }, 3000)
}

function flatten(arr) {
    return arr.reduce((acc, val) => acc.concat(
        Array.isArray(val) ? flatten(val) : val
        ),
        []
    )
}

export function prepareTable() {
    setTimeout(() => {
        $('table').dataTable();
    }, 500);
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
