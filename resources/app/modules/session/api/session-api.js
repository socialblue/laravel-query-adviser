const sessionApiPath = '/query-adviser/api/session';

export function clear() {
    return fetch(`${sessionApiPath}/clear`)
        .then((response) => {
            return response.json();
        });
}

export function sessions() {
    return fetch(`${sessionApiPath}/`).then((response) => {
        return response.json();
    });
}

export function start() {
    return fetch(`${sessionApiPath}/start`).then((response) => {
        return response.json();
    });
}

export function stop() {
    return fetch(`${sessionApiPath}/stop`).then((response) => {
        return response.json();
    });
}

export function isActive() {
    return fetch(`${sessionApiPath}/is-active`).then((response) => {
        return response.json();
    });
}

export function sessionImport(body) {
    const method = 'POST';
    const headers = {
        'Accept': 'application/json, text-plain, */*',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': window.Laravel.csrfToken
    };
    return fetch('/query-adviser/api/session/import', {
        method,
        body,
        headers
    }).then(response => response.json());
}

export function sessionExport(sessionKey) {
    return fetch(`${sessionApiPath}/${sessionKey}/export`)
        .then((resp) => {
            return resp.blob();
        }).then((blob) => {
            const blobUrl = window.URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = blobUrl;
            link.download = `${sessionKey}.json`;
            link.click();
        });
}

export function show(sessionKey) {
    return fetch(`${sessionApiPath}/${sessionKey}/`)
        .then((response) => {
            return response.json();
        });
}

