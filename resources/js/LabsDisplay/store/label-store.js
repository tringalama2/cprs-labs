import {shallowReactive} from 'vue'

const retrieveLabAndPanelLabels = async function () {
    let url = '/api/labels';
    let response = await fetch(url);
    let labelResult = {};

    if (response.ok) { // if HTTP-status is 200-299
        labelResult = await response.json();
    } else {
        alert("Cannot retrieve lab and panel labels. HTTP-Error: " + response.status);
    }
    return labelResult.data;
}

export const labels = shallowReactive(retrieveLabAndPanelLabels());
