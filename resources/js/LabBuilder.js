import collect from "collect.js";
import {LabCreator} from "./LabCreator.js";

export const LabBuilder = (labInput) => {
    const labRows = labInput.split("\n")
    const labCollection = collect()

    const getLabCollection = () => {
        collect(labRows).each((row, index) => {
            // check if is result row
            if (isResultRow(row)) {
                lab = LabCreator(labRows, index).getLab();
                labCollection.push(row)
            }

        });

        return labCollection
    }

    let isResultRow = (row) => {
        return !row.startsWith(' ')
            && row.split(/(\s){2,}/).length > 2
            && /(\[([0-9]+?)\]$)/.test(row)
    }

    return {
        getLabCollection,
    }
}
