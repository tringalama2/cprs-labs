import collect from "collect.js";

export const LabBuilder = (labInput) => {
    const labRows = labInput.split("\n")
    const labCollection = collect()

    const doExternalProcessing = () => {
        // function to be called by the instantiator
    }

    const getLabCollection = () => {
        collect(labRows).each((row) => {
            // check if is result row
            if (isResultRow(row)) {
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
