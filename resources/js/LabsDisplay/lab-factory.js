import { DateTime } from "luxon";
import Lab from "./lab.js";
import UnparsableResult from "./unparsable-result.js";
import {
    FullResultFormat,
    NoUnitsResultFormat,
    NoUnitsOrReferenceRangeResultFormat,
    NoSpaceAfterReferenceRangeResultFormat,
    NoSpaceAfterNameResultFormat
} from "./utils/parsers/result-types.js";

export function LabFactory(allRows, index) {

    let labPieces = getLabData(allRows[index]);
    let metaPieces = getMetaData(allRows, index);

    if (typeof labPieces === 'string' || labPieces instanceof String) {
        // unparsable row
        return new UnparsableResult(labPieces);
    }

    return new Lab(
        metaPieces.specimen_unique_id,
        labPieces.name,
        labPieces.result,
        metaPieces.collection_date,
        metaPieces.released_date,
        labPieces.flag,
        labPieces.units,
        labPieces.reference_range,
        metaPieces.specimen,
        metaPieces.ordering_provider,
        labPieces.site_code,
    );
}

function getLabData(labRow) {
    let resultPieces = labRow.split(/(\s){2,}/)

    let resultFormats = [
        FullResultFormat,
        NoUnitsResultFormat,
        NoSpaceAfterNameResultFormat,
        NoSpaceAfterReferenceRangeResultFormat,
        NoUnitsOrReferenceRangeResultFormat,
    ]

    for (let className of resultFormats) {
        let resultFormat = new className(resultPieces);
        if (resultFormat.match()) {
            return resultFormat.getResultPieces();
        }
    }

    // unparsable row
    return labRow;
}

function getMetaData(allRows, index) {

    while (index > 0) {
        if (allRows[index].startsWith('Specimen Collection Date',4)) {
            break;
        }
        index--;
    }

    return {
        specimen_unique_id: allRows[index-1].match(/Specimen: [A-Z-a-z ]+.[\s]+([A-Z-a-z-0-9 ]+)/)[1],
        collection_date: parseDate(allRows[index]),
        specimen: allRows[index-1].match(/Specimen: ([A-Z-a-z ]+)/)[1],
        ordering_provider: allRows[index-2].substring(10),
        released_date: parseDate(allRows[index-3]),
    }
}

function parseDate(dateTimeString) {
    const dateTimePattern = 'LLL dd, yyyy@HH:mm';
    const altDatePattern = 'LLL dd, yyyy';

    let dt = DateTime.fromFormat(dateTimeString.match(/([A-Za-z]{3} [\d]{2}, [\d]{4}(@[\d]{2}:[\d]{2})?)/)[0], dateTimePattern);

    if (! dt.isValid) {
        let d = DateTime.fromFormat(dateTimeString.match(/([A-Za-z]{3} [\d]{2}, [\d]{4}(@[\d]{2}:[\d]{2})?)/)[0], altDatePattern);

        if (! d.isValid) {
            return '';
        }

        return d;
    }

    return dt;

}
