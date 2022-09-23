export function execute(sessionKey, time, timeKey) {
    return fetch(`/query-adviser/api/session/${sessionKey}/query/${time}/${timeKey}/exec`).then(resp => {
        return resp.json();
    })
};

export function explain(sessionKey, time, timeKey) {
    return fetch(`/query-adviser/api/session/${sessionKey}/query/${time}/${timeKey}/explain`).then(resp => {
        return resp.json();
    })
};
