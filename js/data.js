const getData = async (url) => {
    const response = await fetch(url);

    if (!response.ok) {
        throw new Error(`ERROR AT ${url}. STATUS: ${response.status}.`);
    }

    return await response.json();
};

const sendData = async (url, data) => {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: data,
    });

    if (!response.ok) {
        throw new Error(`ERROR AT ${url}. STATUS: ${response.status}.`);
    }

    return await response.json();
};

export {getData, sendData};