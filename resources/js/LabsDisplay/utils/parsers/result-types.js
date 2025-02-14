class ResultFormat {
    constructor(resultPieces) {
        this.resultPieces = resultPieces;
    }
}

class FullResultFormat extends ResultFormat {
    match() {
        return this.resultPieces.length === 9;
    }

    getResultPieces() {
        return {
            name: this.resultPieces[0],
            result: this.resultPieces[2],
            flag: stripFlagFromResult(this.resultPieces[2]),
            units: this.resultPieces[4],
            reference_range: cleanRefRange(this.resultPieces[6]),
            site_code: this.resultPieces[8],
        };
    }
}

class NoUnitsResultFormat extends ResultFormat {
    match() {
        return this.resultPieces.length === 7;
    }

    getResultPieces() {
        return {
            name: this.resultPieces[0],
            result: this.resultPieces[2],
            flag: stripFlagFromResult(this.resultPieces[2]),
            units: '',
            reference_range: cleanRefRange(this.resultPieces[4]),
            site_code: this.resultPieces[6],
        };
    }
}

class NoUnitsOrReferenceRangeResultFormat extends ResultFormat {
    match() {
        return this.resultPieces.length === 5;
    }

    getResultPieces() {
        return {
            name: this.resultPieces[0],
            result: this.resultPieces[2],
            flag: stripFlagFromResult(this.resultPieces[2]),
            units: '',
            reference_range: '',
            site_code: this.resultPieces[4],
        };
    }
}

class NoSpaceAfterReferenceRangeResultFormat extends ResultFormat {
    static availableNames = [
        'HBV CORE AB TOTAL,blood',
        'RPR,blood',
    ];

    match() {
        return NoSpaceAfterReferenceRangeResultFormat.availableNames.some(
            substr => this.resultPieces[0].startsWith(substr)
        );
    }

    getResultPieces() {
        return {
            name: this.resultPieces[0],
            result: this.resultPieces[2],
            flag: stripFlagFromResult(this.resultPieces[2]),
            units: '',
            reference_range: cleanRefRange(this.resultPieces[4].split('[')[0].trim()),
            site_code: this.resultPieces[4].match(/\[([0-9]+?)]$/)[0],
        }
    }
}

class NoSpaceAfterNameResultFormat extends ResultFormat {
    static availableNames = [
        'MRSA SURVL NARES AGAR,E-SWAB',
        'MRSA SURVL NARES DNA,E-SWAB',
        'C. DIFF TOX B GENE PCR,stool',
        'OCCULT BLOOD RANDOM-GUAIAC ',
        'Occult Blood (Fit) #1 Of 1 ',
        'NORHYDROCODONE QUANT,urine ',
        'Heparin Induced Plt Ab,Blood',
    ];

    getMatchedName() {
        for (let name of NoSpaceAfterNameResultFormat.availableNames) {
            if (this.resultPieces[0].startsWith(name)) {
                return name;
            }
        }
        return '';
    }

    match() {
        return this.getMatchedName() !== '';
    }

    getResultPieces() {
        const result = this.resultPieces[0].split(this.getMatchedName())[1];

        return {
            name: this.getMatchedName(),
            result: result,
            flag: stripFlagFromResult(result),
            // check if units are avail based on number of pieces
            units: this.resultPieces[this.resultPieces.length - 3] === this.resultPieces[0] ? '' : this.resultPieces[this.resultPieces.length - 3],
            reference_range: cleanRefRange(this.resultPieces[this.resultPieces.length - 2]),
            site_code: this.resultPieces[this.resultPieces.length - 1],
        };
    }
}

export {
    FullResultFormat,
    NoUnitsResultFormat,
    NoUnitsOrReferenceRangeResultFormat,
    NoSpaceAfterReferenceRangeResultFormat,
    NoSpaceAfterNameResultFormat
};

function stripFlagFromResult(results) {
    const regex = /([H|L]\**)$/;
    return Array.isArray(results.match(regex))
        ? results.match(regex)[0]
        : '';
}

function cleanRefRange(referenceRange) {
    return referenceRange.replace('Ref: ', '');
}
