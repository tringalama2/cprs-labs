import intersectObjects from "./utils/intersectObjects.js";
import {labels} from "./store/label-store.js";

function DisplayBuilder(results) {
    this.results = results;
    this.labels = labels;

    this.getDateTimeHeaders = function () {
        let headers = [];
        let resultKeys = Object.keys(this.results);
        for (let i = 0, len = resultKeys.length; i < len; i++) {
            let key = resultKeys[i];
            if (this.results[key]) {
                headers.push({
                    specimenUniqueId: this.results[key].specimenUniqueId,
                    collectionDate: this.results[key].collectionDate
                })
            }
        }
        return uniqSpecimen(headers).sort((specimen1, specimen2) => specimen2.collectionDate - specimen1.collectionDate);
    }

    this.getLabels = async function () {
        let uniqueLabLabels = {};
        for (const key in this.results) {
            uniqueLabLabels[this.results[key].name] = true;
        }
        let matchedLabels = await intersectObjects(await this.labels, uniqueLabLabels);

        let unMatchedLabs = await this.getUnrecognizedLabs()

        return {...matchedLabels, ...unMatchedLabs};
    }

    this.getUnrecognizedLabs = async function () {
        let result = {};
        let labels = Object.keys(await this.labels);

        for (let lab in this.results) {
            if (!labels.includes(this.results[lab].name)) {
                result[this.results[lab].name] = {
                    name: this.results[lab].name,
                    label: this.results[lab].name,
                    panel: 'Other',
                }
            }
        }

        return result;
    }

    this.getPanels = async function () {
        let panels = {};
        Object.entries(await this.getLabels()).map((x) => x[1]['panel']).forEach(function (item, index, array) {
            panels[item] = array.countBy(item)
        });
        return panels;
    }
}

export default DisplayBuilder;

function uniqSpecimen(a) {
    let seen = {};
    return a.filter(function (item) {
        return seen.hasOwnProperty(item.specimenUniqueId) ? false : (seen[item.specimenUniqueId] = true);
    });
}

Object.defineProperties(Array.prototype, {
    countBy: {
        value: function (query) {
            /*
               Counts number of occurrences of query in array, an integer >= 0
               Uses the javascript == notion of equality.
            */
            var count = 0;
            for (let i = 0; i < this.length; i++)
                if (this[i] === query)
                    count++;
            return count;
        }
    }
});
