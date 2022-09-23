const defaultApiPath = '/query-adviser/api';
export function serverInfo() {
    return fetch(`${defaultApiPath}/server-info`)
        .then((response) => response.json());
}
